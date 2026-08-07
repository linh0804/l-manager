<?php 
defined('ACCESS') or exit;

$curr_path = get_curr_path();
$curr_file = new SplFileInfo($curr_path);

if (check_path($curr_path)) {
     echo check_path($curr_path);
     return;     
}

defined('ACCESS') or exit;

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($curr_path));
header('Content-Length: ' . filesize($curr_path));
readfile($curr_path);
