<?php

namespace Nightmare\Database;

use PDOStatement;
use PDO;

class Statement extends PDOStatement
{
    protected function __construct()
    {
        $this->setFetchMode(PDO::FETCH_CLASS, Row::class);
    }
}
