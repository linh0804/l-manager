<?php 
defined('ACCESS') or exit;
use Nightmare\Fs;
?>
<div class="card">
<?php
$curr_path = get_curr_path();
$curr_path = $curr_path ?: config()->get('home');
$curr_path = $curr_path ?: getenv('HOME');
$curr_path = $curr_path ?: ($_SERVER['DOCUMENT_ROOT'] ?? '');
$curr_path = (string) $curr_path;

$page_list = intval(get('page_list'));
$page_list = $page_list < 1 ? 1 : $page_list;
$site_title = 'Danh sách - ' . basename($curr_path);

if (!get('path')) {
    redirect(action_link(null, ['path' => $curr_path]));
}

if (check_path($curr_path)) {
     echo check_path($curr_path);
     echo '</div>';
     return;     
}
if (is_file($curr_path)) {
    redirect(action_link('file/info', ['path' => $curr_path]));
}

// list
$handler = @scandir($curr_path, SCANDIR_SORT_NONE);

if (!is_array($handler)) {
    $handler = [];
}

$lists = [];
$folders = [];
$files = [];

foreach ($handler as $entry) {
    if ($entry === '.' || $entry === '..') {
        continue;
    }

    $entry_path = Fs::join_path($curr_path, $entry);

    if (is_dir($entry_path)) {
        $folders[] = $entry_path;
    } else {
        $files[] = $entry_path;
    }
}

sort_natural($folders);
sort_natural($files);

$lists = array_merge($folders, $files);
$count = count($lists);

if (PAGE_SIZE <= 0) {
    $page_list = 1;
} elseif ($page_list > (int) ceil($count / PAGE_SIZE)) {
    $page_list = 1;
}


?>
<div id="app-index-updater"></div>
<div class="card-body"><?= file_print_path($curr_path, true) ?> <span class="copy-button" data-copy="<?= htmlspecialchars((string) $curr_path) ?>" style="color: pink">[copy]</span></div>

<a href="<?= action_link(null, ['path' => dirname($curr_path), 'page_list' => null]) ?>">
  <div class="list">
    <img src="icon/back.png" style="margin-left: 5px; margin-right: 5px"/> 
    <strong class="back">...</strong>
  </div>
</a>

<?php if (is_app_file($curr_path)): ?>
    <div class="notice_failure">Bạn đang xem thư mục của File Manager!</div>
<?php endif; ?>

<form action="" method="post" name="form">

<?php if ($count <= 0): ?>
    <div class="list"><img src="icon/empty.png"/> <span class="empty">Không có thư mục hoặc tập tin</span></div>
<?php else:
    $display_lists = paging_arr($lists, $page_list, PAGE_SIZE);
?>
    <div class="table-list-file">
        <table class="list-file">
            <?php foreach ($display_lists as $entry_path):
                $file = new SplFileInfo($entry_path);
                $name = $file->getFilename();
                $perms = file_get_chmod($file->getPathname());
            ?>
            <tr>
                <td><input type="checkbox" name="entries[]" value="<?= $name ?>"/></td>
       
                <?php if ($file->isDir()): ?>
                    <td class="name"><b><?= file_get_display_link($file) ?></b></td>
                    <td><span data-act="calc" data-path="<?= $file->getPathname() ?>" class="btn-calc-size size">[...]</span></td>
                <?php else: ?>
                    <td class="name"><?= file_get_display_link($file) ?></td>
                    <td><span class="size"><?= (($file->isReadable())
                        ? Fs::sizen($file->getSize())
                        : '-') ?></span></td>
                <?php endif; ?>

                <td class="chmod"><?= (($file->isReadable()) ? Fs::get_owner_name_by_id($file->getOwner()) : '-') ?></td>
                <td><a href="<?= action_link('file/chmod', ['path' => $file->getPathname()]) ?>" class="chmod"><?= $perms ?></a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td><input id="file-select-all" name="all" value="0" type="checkbox" /></td>
                <td><b><i>Total: <?= $count ?></i></b></td>
            </tr>
        </table>
    </div>

    <div class="list">

        <div id="file-select-opt" style="display: block">
            <button id="mutil_copy" path="<?= $curr_path ?>" class="button"><img src="<?= home('icon/copy.png') ?>"/> Sao chép</button>
            <button id="mutil_move" path="<?= $curr_path ?>" class="button"><img src="<?= home('icon/move.png') ?>"/> Di chuyển</button>
            <button id="mutil_zip" path="<?= $curr_path ?>" class="button"><img src="<?= home('icon/zip.png') ?>"/> Zip</button>
            <button id="mutil_delete" path="<?= $curr_path ?>" class="button"><img src="<?= home('icon/delete.png') ?>"/> Xoá</button>
            <button id="mutil_chmod" path="<?= $curr_path ?>" class="button"><img src="<?= home('icon/access.png') ?>"/> Chmod</button>
            <button id="mutil_rename" path="<?= $curr_path ?>" class="button"><img src="<?= home('icon/rename.png') ?>"/> Đổi tên</button>
        </div>

        <?= paging('', 'page_list', ['path' => $curr_path], $page_list, $count, PAGE_SIZE)?>
    </div>
<?php endif; ?>
</form>


<div class="card-body">Chức năng</div>

<ul class="list">
    <li><a href="<?= action_link('file/create', ['path' => $curr_path]) ?>"><img src="icon/create.png"/> Tạo mới</a></li>
    <li><a href="<?= action_link('file/upload', ['path' => $curr_path]) ?>"><img src="icon/upload.png"/> Tải lên</a></li>
    <li><a href="<?= action_link('file/import', ['path' => $curr_path]) ?>"><img src="icon/import.png"/> Nhập khẩu</a></li>
    <li><a href="<?= action_link('file/find_in_folder', ['path' => $curr_path]) ?>"><img src="icon/search.png"/> Tìm trong thư mục</a></li>
    <li><a href="<?= action_link('webdav.php/'.ltrim($curr_path, '/')) ?>"><img src="icon/rows.png"/> Webdav</a></li>
    <li><a href="<?= action_link('file/info', ['path' => $curr_path]) ?>"><img src="icon/info.png"/> Thông tin</a></li>
</ul>
</div>