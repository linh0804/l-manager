<?php

namespace Nightmare\Database;

use ArrayObject;

class Row extends ArrayObject
{
    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this[$name];
    }

    /**
     * @param string $name
     * @param mixed $val
     * @return void
     */
    public function __set($name, $val)
    {
        $this[$name] = $val;
    }
}
