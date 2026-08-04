<?php
defined('ACCESS') or exit;
Class System {
    public $uri;
    public function __construct() {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}