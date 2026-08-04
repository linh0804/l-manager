<?php
defined('ACCESS') or exit;
$web = false;
$app_name = 'file_manager_' . md5(dirname(__DIR__) . '/system/_init.php');
isset($_COOKIE[$app_name . '_auth']) or exit;

phpinfo();
