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
$act = preg_replace('/[^a-z0-9_]/', '', $act);
echo '<div class="card">';
if (empty($act) || !file_exists(ROOT_PATH . '/source/file/' . $act . '.php')) {
    echo '<div class="card-body">' . $site_title . '</div>
        <div class="list"><span>Không có hành động</span></div>
        <div class="title">Chức năng</div>
        <ul class="list">
            <li><img src="icon/list.png" alt=""/> <a href="' . action_link(null, ['path' => $curr_path]) . '">Danh sách</a></li>
        </ul>';
    echo '</div>';
    return;
}

require ROOT_PATH . '/source/file/' . $act . '.php';

echo '</div>';