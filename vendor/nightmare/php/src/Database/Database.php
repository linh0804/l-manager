<?php

namespace Nightmare\Database;

use PDO;
use PDOStatement;

class Database
{
    /**
     * @var PDO
     */
    private $driver;

    public function __construct(
        $dsn,
        $username = null,
        $password = null,
        $options = null
    ) {
        if ($dsn instanceof PDO) {
            $this->driver = $dsn;
        } else {
            $this->driver = new PDO(
                $dsn,
                $username,
                $password,
                array_merge([
                    PDO::MYSQL_ATTR_INIT_COMMAND, 'SET sql_mode="ANSI,TRADITIONAL"'
                ], (array) $options)
            );
        }

        $this->driver->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->driver->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->driver->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
        $this->driver->setAttribute(PDO::ATTR_STATEMENT_CLASS, [Statement::class]);

        $this->driver->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    }

    public function driver()
    {
        return $this->driver;
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @return PDOStatement|false
     */
    public function query($sql, $params = null)
    {
        $stmt = $this->driver->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * giống exec() nhưng dùng prepare
     *
     * @param string $sql
     * @param array|null $params
     * @return int
     */
    public function query_count($sql, $params = null)
    {
        $stmt = self::query($sql, $params);

        return $stmt->rowCount();
    }

    /**
     * @param string $table
     * @param array $params
     * @return string
     */
    public function insert($table, $params)
    {
        $sql = 'insert into "' . $table . '"'
            . ' (' . implode(',', $this->buildName(array_keys($params))) . ')'
            . ' values (' . implode(',', array_fill(0, count($params), '?')) . ')';
        //dd($sql);
        $this->query($sql, array_values($params));

        return $this->driver->lastInsertId();
    }

    /**
     * @param string $table
     * @param array $con
     * @param array $arr
     * @return void
     */
    public function update_or_insert($table, $con, $arr)
    {
        $where_conditions = [];
        $where_params = [];

        foreach ($con as $column => $value) {
            $where_conditions[] = sprintf('"%s" = ?', $column);
            $where_params[] = $value;
        }

        $where_clause = implode(' AND ', $where_conditions);
        $check_sql = sprintf('SELECT COUNT(*) FROM "%s" WHERE %s', $table, $where_clause);
        $count = $this->fetch_column($check_sql, $where_params);

        if ($count > 0) {
            $update_parts = [];
            $update_params = [];

            foreach ($arr as $column => $value) {
                $update_parts[] = sprintf('"%s" = ?', $column);
                $update_params[] = $value;
            }

            $update_clause = implode(', ', $update_parts);
            $update_sql = sprintf('UPDATE "%s" SET %s WHERE %s', $table, $update_clause, $where_clause);

            $this->query($update_sql, array_merge($update_params, $where_params));
        } else {
            $this->insert($table, array_merge($con, $arr));
        }
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @return int
     */
    public function update($sql, $params = null)
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @return mixed
     */
    public function fetch($sql, $params = null)
    {
        return $this->query($sql, $params)->fetch();
    }

    public function exec($sql)
    {
        return $this->driver->exec($sql);
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @return array
     */
    public function fetchAll($sql, $params = null)
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @return array
     */
    public function fetch_all($sql, $params = null)
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @param int $column
     * @return mixed
     */
    public function fetchColumn($sql, $params = null, $column = 0)
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn($column);
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @param int $column
     * @return mixed
     */
    public function fetch_column($sql, $params = null, $column = 0)
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn($column);
    }

    /**
     * @param int $page
     * @param int $per_page
     * @return int
     */
    public function getOffset($page, $per_page)
    {
        return $page * $per_page - $per_page;
    }

    /**
     * @param int $page
     * @param int $per_page
     * @return int
     */
    public function get_offset($page, $per_page)
    {
        return $page * $per_page - $per_page;
    }

    /**
     * @param string $str
     * @return string
     */
    public function quote($str)
    {
        return $this->driver->quote($str);
    }

    /**
     * @param array $arr
     * @return array
     */
    public function buildName($arr)
    {
        return array_map(function ($item) {
            return '"' . $item . '"';
        }, $arr);
    }

    /**
     * @param array $arr
     * @return array
     */
    public function build_name($arr)
    {
        return array_map(function ($item) {
            return '"' . $item . '"';
        }, $arr);
    }
}
