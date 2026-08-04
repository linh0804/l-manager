<?php

define('ACCESS', true);

require dirname(__DIR__) . '/system/_init.php';

$curr_path = postJson('content', 'path');
$entries = postJson('content', 'entries', []);

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


$site_title = 'Xóa';
$curr_path = process_directory($curr_path);

if (postJson('content','is_action')) {
    if (!multi_remove($entries, $curr_path)) {
        $data['error'] =  'Xóa thất bại';
    } else {
        $data['status'] = true;
    }
} 

response($data);