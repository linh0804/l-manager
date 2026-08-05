<?php
define('ACCESS', true);

require dirname(__DIR__) . '/system/_init.php';

$curr_path = postJson('content', 'path');
$entries = postJson('content', 'entries', []);
$new_path = postJson('content', 'new_path');
$data = [
    'status' => false,
    'error' => ''
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $data['error'] = 'Phương thức không hợp lệ';
    response($data);
}

function chmods($curr_path, $entrys, $folder, $file)
{
    $folder = intval($folder, 8);
    $file   = intval($file, 8);

    foreach ($entrys as $e) {
        $entry_path = $curr_path . '/' . $e;

        if (@is_file($entry_path)) {
            if (!@chmod($entry_path, $file)) {
                return false;
            }
        } elseif (@is_dir($entry_path)) {
            if (!@chmod($entry_path, $folder)) {
                return false;
            }
        } else {
            return false;
        }
    }

    return true;
}

$site_title = 'Chmod';
$curr_path = process_directory($curr_path);


if (postJson('content','is_action')) {
    if (empty(postJson('content','folder')) || empty(postJson('content','file'))) {
        $data['error'] = 'Chưa nhập đầy đủ thông tin';
    } elseif (!chmods($curr_path, $entries, postJson('content','folder'), postJson('content','file'))) {
        $data['error'] = 'Chmod thất bại';
    } else {
        $data['status'] = true;
    }
}
response($data);    