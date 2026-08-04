<?php
define('ACCESS', true);
require_once __DIR__ . '/system/_init.php';

preg_match('/([^\/]+\.php)/', $uri_run, $match);
$uri_run = $match[1] ?? $uri_run;

ob_start();
if(file_exists(ROOT_PATH . '/source/' . $uri_run . '.php')) {
    require_once ROOT_PATH . '/system/ACCESS.php';
    require_once ROOT_PATH . '/source/' . $uri_run . '.php';
    if(file_exists(ROOT_PATH . '/javascript/' . $uri_run . '.js')) {
        echo PHP_EOL;
        echo '<script src="'. home('javascript/' . $uri_run . '.js?t='. time()) .'"></script>';
    }
} else {
   echo '<div class="card">';
   echo '<div class="card-body">Lỗi hệ thống</div>';
   echo '<div class="notice_failure">Đường dẫn <b><i>không tồn tại</i></b>!</div>';
   echo '</div>';
}

$content_html = ob_get_contents();
ob_end_clean();
if(!$web) {
    echo $content_html;
    exit;
}
require_once ROOT_PATH . '/system/header.php';
echo $content_html;
echo PHP_EOL;
require_once ROOT_PATH . '/system/footer.php';
    