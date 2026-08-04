<?php 
defined('ACCESS') or exit;
$curr_path = get_curr_path();
$curr_file = new SplFileInfo($curr_path);
$act = get('act') ?? '';

$site_title = 'File';

if (check_path($curr_path)) {
     echo check_path($curr_path);
     echo '</div>';
     return;     
}
if (
    is_file($curr_path)
    && ($info = getimagesize($curr_path)) !== false
) {
    header('Content-Type: ' . $info['mime']);
    header('Content-Length: ' . filesize($curr_path));
    readfile($curr_path);
} else {
    exit('Not read image');
}
$web = false;