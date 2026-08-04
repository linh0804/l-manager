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

if (empty($curr_path) || !is_dir(process_directory($curr_path))) {
    $data['error'] = 'Đường dẫn không tồn tại';
    response($data);
}

if (count($entries) <= 0) {
    $data['error'] = 'Không có lựa chọn';
    response($data);
}


function multi_move($entrys, $dir, $path)
{
    foreach ($entrys as $e) {
        $pa = $dir . '/' . $e;

        if (@is_file($pa)) {
            if (!@rename($pa, $path . '/' . $e)) {
                return false;
            }
        } elseif (@is_dir($pa)) {
            if (!movedir($pa, $path)) {
                return false;
            }
        } else {
            return false;
        }
    }

    return true;
}

$site_title = 'Di chuyển';

$entry_checkbox = '';

if (postJson('content','is_action')) {
    if (empty($new_path)) {
        $data['error'] = 'Chưa nhập đầy đủ thông tin';
    } elseif ($curr_path == process_directory($new_path)) {
        $data['error'] = 'Đường dẫn mới phải khác đường dẫn hiện tại';
    } elseif (!is_dir($new_path)) {
        $data['error'] = 'Đường dẫn mới không tồn tại';
    } elseif (!multi_move($entries, $curr_path, process_directory($new_path))) {
        $data['error'] = 'Di chuyển thất bại';
    } else {
        $data['status'] = true;
    }
}

response($data);