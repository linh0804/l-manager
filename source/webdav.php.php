<?php

use Sabre\DAV\Auth\Backend\BasicCallBack;
use Sabre\DAV\Auth\Plugin;
use Sabre\DAV\FS\Directory;
use Sabre\DAV\Server;
use Sabre\DAV\Browser\Plugin as BrowserPlugin;

define('LOGIN_BYPASS_AUTO_REDIRECT', true);
defined('ACCESS') or exit;
$web = false;

$path_info = (string) ($_SERVER["PATH_INFO"] ?? '');



$_SERVER["PATH_INFO"] = $path_info;

$curr_path = @is_dir($path_info) ? $path_info : dirname($path_info);
$curr_path = $curr_path == '/' &&  $path_info ? '' : $curr_path;

$curr_path = str_replace('/webdav.php','', $curr_path);
$base_uri = ($_SERVER["SCRIPT_NAME"] ?? home('index.php')) .'/webdav.php'. rtrim((string) $curr_path, '/');

$auth_backend = new BasicCallBack(function ($username, $password) {
    if (
        strtolower((string) $username) === strtolower((string) config()->get('username'))
        && md5($password) === config()->get('password')
    ) {
        auth_reset_fail_login();
        return true;
    } else {
        auth_increase_login_fail();
    }

    return false;
});

//error_reporting(0);
try {
    $server = new Server(new Directory($curr_path));
    $server->setBaseUri($base_uri);
    $server->addPlugin(new Plugin($auth_backend));
    $server->addPlugin(new BrowserPlugin());
    $server->start();
} catch (Throwable $e) {
    exit($e);
}
