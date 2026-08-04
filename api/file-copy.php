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

$site_title = 'Sao chép';

if (empty($curr_path) || !is_dir(process_directory($curr_path))) {
    $data['error'] = 'Đường dẫn không tồn tại';
    response($data);
}

if (count($entries) <= 0) {
    $data['error'] = 'Không có lựa chọn';
    response($data);
}

$curr_path = process_directory($curr_path);

function copys($entrys, $curr_path, $path_new)
{
    foreach ($entrys as $e) {
        $entry_path = $curr_path . '/' . $e;

        if (@is_file($entry_path)) {
            if (!@copy($entry_path, $path_new . '/' . $e)) {
                return false;
            }
        } elseif (@is_dir($entry_path)) {
            if (!copydir($entry_path, $path_new)) {
                return false;
            }
        } else {
            return false;
        }
    }

    return true;
}

if (postJson('content','is_action')) {    
    if (empty($new_path)) {
        $data['error'] = 'Chưa nhập đầy đủ thông tin';
    } elseif ($curr_path == process_directory($new_path)) {
        $data['error'] = 'Đường dẫn mới phải khác đường dẫn hiện tại';
    } elseif (!is_dir($new_path)) {
        $data['error'] = 'Đường dẫn mới không tồn tại';
    } elseif (!copys($entries, $curr_path, process_directory($new_path))) {
        $data['error'] = 'Sao chép thất bại';
    } else {
        $data['status'] = true;
    }

}

response($data);