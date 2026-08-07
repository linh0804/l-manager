<?php 

use Nightmare\Fs;

defined('ACCESS') or exit;
$curr_path = get_curr_path();
$curr_file = new SplFileInfo($curr_path);

if (check_path($curr_path)) {
     echo check_path($curr_path);
     return;     
}



$site_title = 'Tải lên tập tin';

echo '<div class="title">' . $site_title . '</div>';

if (is_submit()) {
    $is_empty = true;

    foreach (post('url') as $entry) {
        if (!empty($entry)) {
            $is_empty = false;
            break;
        }
    }

    if ($is_empty) {
        echo '<div class="notice_failure">Chưa nhập url nào cả</div>';
    } else {
        for ($i = 0; $i < count(post('url')); ++$i) {
            if (!empty(post('url')[$i])) {
                if (!is_url(post('url')[$i])) {
                    echo '<div class="notice_failure">URL <strong class="url_import">' . post('url')[$i] . '</strong> không hợp lệ</div>';
                } elseif (file_import($curr_path . '/' . basename((string) post('url')[$i]), post('url')[$i])) {
                    echo '<div class="notice_succeed">Nhập khẩu tập tin <strong class="file_name_import">' . basename((string) post('url')[$i]) . '</strong>, <span class="file_size_import">' . Fs::sizen(filesize($curr_path . '/' . basename((string) post('url')[$i]))) . '</span> thành công</div>';
                } else {
                    echo '<div class="notice_failure">Nhập khẩu tập tin <strong class="file_name_import">' . basename((string) post('url')[$i]) . '</strong> thất bại</div>';
                }
            }
        }
    }
}

echo '<div class="list">
    <span>' . file_print_path($curr_path, true) . '</span><hr/>
    <form action="" method="post">
        <span class="bull">&bull; </span>URL 1:<br/>
        <input type="text" name="url[]" size="18"/><br/>
        <span class="bull">&bull; </span>URL:<br/>
        <input type="text" name="url[]" size="18"/><br/>
        <span class="bull">&bull; </span>URL 3:<br/>
        <input type="text" name="url[]" size="18"/><br/>
        <span class="bull">&bull; </span>URL 4:<br/>
        <input type="text" name="url[]" size="18"/><br/>
        <span class="bull">&bull; </span>URL 5:<br/>
        <input type="text" name="url[]" size="18"/><br/>
        <input type="submit" name="submit" value="Nhập khẩu"/>
    </form>
</div>';

