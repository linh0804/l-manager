<?php
use Nightmare\Json;

defined('ACCESS') or exit;
define('ROOT_PATH', dirname(__DIR__));
$manager = explode('/', $_SERVER['REQUEST_URI'])[1];
define('MANAGER_NAME', $manager);

// constants
define('APP_NAME', 'file_manager_' . md5(__FILE__));
define('APP_CONFIG_FILE', __DIR__ . '/_env.php');

define('LOGIN_USERNAME_DEFAULT', 'admin');
define('LOGIN_PASSWORD_DEFAULT', '!!!123456789!!!');
define('LOGIN_MAX', 10);
define('LOGIN_WAIT', 3600);

define('PAGE_SIZE', 200);
define('PAGE_NUMBER', 10);

define('COMMON_FILE_FORMAT', [
    'image' => ['png', 'ico', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'],
    'text' => ['cpp', 'css', 'csv', 'h', 'htaccess', 'html', 'java', 'js', 'lng', 'pas', 'php', 'pl', 'py', 'rb', 'rss', 'sh', 'svg', 'tpl', 'txt', 'xml', 'ini', 'cnf', 'config', 'conf', 'conv'],
    'archive' => ['7z', 'rar', 'tar', 'tarz', 'zip'],
    'audio' => ['acc', 'm4a', 'midi', 'mp3', 'mp4', 'swf', 'wav'],
    'font' => ['afm', 'bdf', 'otf', 'pcf', 'snf', 'ttf'],
    'binary' => ['pak', 'deb', 'dat'],
    'document' => ['pdf'],
    'source' => ['changelog', 'copyright', 'license', 'readme'],
    'zip' => ['zip', 'jar', 'rar'],
    'other' => ['rpm', 'sql']
]);
define('COMMON_FILE_EXCLUDES', [
    '.git/',
    'node_modules/',
    'vendor/',
    'asset/',
    'assets/',
    'files/'
]);


// báo lỗi
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
//ini_set('html_errors', true);
error_reporting(E_ALL);

// session
session_start();

require ROOT_PATH . '/vendor/autoload.php';
require_once __DIR__ . '/classes/FmConfig.php';
require_once __DIR__ . '/_function.php';
require_once __DIR__ . '/classes/System.php';

$system = new System();
$uri = $system->uri;
$site_title = 'MANAGER';


$version = Json::decode_file(__DIR__ . '/version.json');
define('APP_VERSION', $version['version']);


$uri_run = substr($uri, strlen('/' . MANAGER_NAME . '/index.php/'));
if(!$uri_run) $uri_run = 'main';


if($uri_run == 'login') define('LOGIN_BYPASS_AUTO_REDIRECT', true);

// check cấu hình
if (empty(config()->get('username'))
    || empty(config()->get('password'))
) {
    define('IS_CONFIG_ERROR', true);
} else {
    define('IS_CONFIG_ERROR', false);
}

// Kiểm tra đăng nhập
if (IS_CONFIG_ERROR) {
    define('IS_LOGIN', false);
} else {
    $is_login_cookie = isset($_COOKIE[APP_NAME . '_auth']);
    $is_login = $is_login_cookie && $_COOKIE[APP_NAME . '_auth'] === config()->get('password');

    if (getenv('FILE_MANAGER_PHP_AUTO_LOGIN') === 'on') {
        $is_login = true;
        
        if (!$is_login_cookie) {
            setcookie(APP_NAME . '_auth', 'autologin', time() + 3600 * 24 * 365, '/');
        }
    }

    define('IS_LOGIN', $is_login);
}
if (!IS_LOGIN && !auth_can_login()) {
    exit('đăng nhập sai nhiều lần, cấm 1 giờ');
}
if (!IS_LOGIN) {
    if (!defined('LOGIN_BYPASS_AUTO_REDIRECT')) {
        redirect(action_link('login'));
    }
}     
$web = true;