<?php

define('ACCESS', true);

use Nightmare\Zip;

require dirname(__DIR__) . '/system/_init.php';

$curr_path = postJson('content', 'path');
$entries = postJson('content', 'entries', []);
$new_path = postJson('content', 'new_path');
$name = postJson('content', 'name');

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


function multi_zip($dir, $entrys, $file, $isDelete = false)
{
    if (@is_file($file)) {
        @unlink($file);
    }

    $zip = new Zip();
    if ($zip->open($file, ZipArchive::CREATE) !== true) {
        return false;
    }
    foreach ($entrys as $entry) {
        $path = "$dir/$entry";
        $zip->add($path, $dir);

        if (is_dir($path)) {
            $files = read_full_dir($path);

            foreach ($files as $value) {
                $zip->add($value->getPathname(), $dir);
            }
        }
    }
    $zip->close();

    if ($isDelete) {
        multi_remove($entrys, $dir);
    }

    return true;
}

$nameG = get('name', null);

$site_title = 'Nén zip';
$curr_path = process_directory($curr_path);

if (postJson('content','is_action')) {
    if (empty($name) || empty($new_path)) {
        $data['error'] = 'Chưa nhập đầy đủ thông tin';
    } elseif (postJson('content','is_delete') && process_directory($new_path) == $curr_path . '/' . ($nameG ?? '')) {
        $data['error'] = 'Nếu chọn xóa thư mục bạn không thể lưu tập tin nén ở đó';
    } elseif (file_name_valid($name)) {
        $data['error'] = 'Tên tập tin zip không hợp lệ';
    } elseif (file_exists(process_directory($new_path . '/' . $name))) {
        $data['error'] = 'Tập tin đã tồn tại, vui lòng đổi tên!';
    } elseif (!multi_zip($curr_path, $entries, process_directory($new_path . '/' . $name), postJson('content','is_delete'))) {
        $data['error'] = 'Nén zip thất bại';
    } else {
        $data['status'] = true;
    }
}

response($data);