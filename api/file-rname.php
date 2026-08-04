<?php

define('ACCESS', true);

require dirname(__DIR__) . '/system/_init.php';

$curr_path = postJson('content', 'path');
$entries = postJson('content', 'entries', []);
$modifier = postJson('content','modifier', []);
$data = [
    'status' => false,
    'error' => [],
    'message' => []
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $data['error'][] = 'Phương thức không hợp lệ';
    response($data);
}

if(count($entries) != count($modifier)) {
    $data['error'][] = 'Đối chiếu không hợp lệ';
    response($data);
}

if (empty($curr_path) || !is_dir(process_directory($curr_path))) {
    $data['error'][] = 'Đường dẫn không tồn tại';
    response($data);
}

if (count($entries) <= 0) {
    $data['error'][] = 'Không có lựa chọn';
    response($data);
}

$site_title = 'Đổi tên';
$curr_path = process_directory($curr_path);

$modifier = $entries;
if (postJson('content','is_action')) {
    $modifier = postJson('content','modifier', []);
    $is_failed  = false;
    $is_succeed = true;

    foreach ($modifier as $k => $e) {
        $entry_path = $curr_path . '/' . $entries[$k];

        if (empty($e)) {
            $is_failed = true;

            $data['error'][] = 'Không được để trống ô nào';
            break;
        } elseif (file_name_valid($e)) {
            $is_failed   = true;
            $entry_label = is_dir($entry_path) ? 'thư mục' : 'tập tin';
            $entry_css   = is_dir($entry_path) ? 'folder' : 'file';

            $data['error'][] = 'Tên ' . $entry_label . ' <strong class="' . $entry_css . '_name_rename_action">' . $entries[$k] . '</strong> <strong>=></strong> <strong class="' . $entry_css . '_name_rename_action">' . $e . '</strong> không hợp lệ';
        } elseif (count_string_array($modifier, strtolower((string) $e), true) > 1 && $e != $entries[$k]) {
            $is_failed   = true;
            $entry_label = is_dir($entry_path) ? 'thư mục' : 'tập tin';
            $entry_css   = is_dir($entry_path) ? 'folder' : 'file';

            $data['error'][] = 'Tên ' . $entry_label . ' <strong class="' . $entry_css . '_name_rename_action">' . $entries[$k] . '</strong> <strong>=></strong> <strong class="' . $entry_css . '_name_rename_action">' . $e . '</strong> này đã tồn tại ở một khung nhập khác';
        } elseif (!is_in_array($entries, strtolower((string) $e), true) && file_exists($curr_path . '/' . $e)) {
            $is_failed   = true;
            $entry_label = is_dir($entry_path) ? 'thư mục' : 'tập tin';
            $entry_css   = is_dir($entry_path) ? 'folder' : 'file';

            $data['error'][] = 'Tên ' . $entry_label . ' <strong class="' . $entry_css . '_name_rename_action">' . $entries[$k] . '</strong> <strong>=></strong> <strong class="' . $entry_css . '_name_rename_action">' . $e . '</strong> này đã tồn tại';
        }
    }

    if (!$is_failed) {
        $is_succeed = true;
        $rand      = md5(rand(1000, 99999) . '-' . $curr_path);
        $rand      = substr($rand, 0, strlen($rand) >> 1);

        foreach ($entries as $e) {
            $entry_path = $curr_path . '/' . $e;

            @rename($entry_path, $entry_path . '-' . $rand);
        }

        foreach ($entries as $k => $e) {
            $k = intval($k);
            if(isset($k) && !isset($modifier[$k])) {
                $data['error'][] = 'Lỗi hệ thống!';
                break;
            }
            $entry_path  = $curr_path . '/' . $e;
            $entry_label = is_dir($entry_path) ? 'thư mục' : 'tập tin';
            $entry_css   = is_dir($entry_path) ? 'folder' : 'file';

            if (!@rename($entry_path . '-' . $rand, $curr_path . '/' . $modifier[$k])) {
                $is_succeed = false;

                $data['error'][] = 'Đổi tên ' . $entry_label . ' <strong class="' . $entry_css . '_name_rename_action">' . $e . '</strong> <strong>=></strong> <strong class="' . $entry_css . '_name_rename_action">' . $modifier[$k] . '</strong> thất bại';
            } else {
                $is_succeed = true;
                $entries[$k] = $modifier[$k];

                $data['message'][] = 'Đổi tên ' . $entry_label . ' <strong class="' . $entry_css . '_name_rename_action">' . $e . '</strong> <strong>=></strong> <strong class="' . $entry_css . '_name_rename_action">' . $modifier[$k] . '</strong> thành công';
            }
        }
    }

    if (!$is_failed && $is_succeed) {
        $data['status'] = true;
    }
}

response($data);