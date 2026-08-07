<?php
use Nightmare\Fs;
define('ACCESS', true);

require dirname(__DIR__) . '/system/_init.php';

$curr_path = postJson('content', 'path');

$curr_file = new SplFileInfo($curr_path);

$is_file = '';
$file_name = basename($curr_path);

if (check_path($curr_path)) {
     echo check_path($curr_path);
     return;     
}

if (is_file($curr_path)) {
    $format = file_get_ext(basename($curr_path));
    $is_image = false;
    $pixel = null;

    if ($format && in_array($format, array('png', 'ico', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'))) {
        $pixel = getimagesize($curr_path);
        $is_image = true;

        $is_file .= '<li><center><img src="' . action_link('file/download_image', ['path' => $curr_path]) . '" width="' . ($pixel[0] > 200 ? 200 : $pixel[0]) . 'px"/></center><br/></li>';
    }

    $is_file .= '<li><span class="bull">&bull; </span><strong>Kích thước</strong>: <span>' . Fs::sizen(filesize($curr_path)) . '</span></li>';

    if ($is_image) {
        $is_file .= '<li><span class="bull">&bull; </span><strong>Độ phân giải</strong>: <span>' . $pixel[0] . 'x' . $pixel[1] . '</span></li>';
    }
}
?>
<ul class="info">
    <li><span class="bull">&bull; </span><strong>Tên</strong>: <span style="color: red"><?= $file_name ?></span></li>
    <?= $is_file ?>
    <li><span class="bull">&bull; </span><strong>Owner</strong>: <span><?= (posix_getpwuid(fileowner($curr_path))['name']) ?></span></li>
    <li><span class="bull">&bull; </span><strong>Chmod</strong>: <span><?= file_get_chmod($curr_path) ?></span></li>
    <li><span class="bull">&bull; </span><strong>Ngày tạo</strong>: <span><?= date('d.m.Y - H:i:s', filectime($curr_path)) ?></span></li>
    <li><span class="bull">&bull; </span><strong>Ngày sửa</strong>: <span><?= date('d.m.Y - H:i:s', filemtime($curr_path)) ?></span></li>
</ul>