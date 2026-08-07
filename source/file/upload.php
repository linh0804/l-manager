<?php 
defined('ACCESS') or exit;

$curr_path = get_curr_path();
$curr_file = new SplFileInfo($curr_path);


if (check_path($curr_path)) {
     echo check_path($curr_path);
     return;     
}

if (files('file')) {
    $data = [];
    $data['error'] = 'Tập tin bị lỗi!';

    if (!empty(files('file')['name'])) {
        if (files('file')['error'] == UPLOAD_ERR_INI_SIZE) {
            $data['error'] = 'Tập tin ' . files('file')['name'] . ' vượt quá kích thước cho phép';
        } else {
            $newName = $curr_path . '/' . files('file')['name'];

            if (move_uploaded_file(files('file')['tmp_name'], $newName)) {
                $data['error'] = '';
            }
        }
    }   
    
    response($data);
}

$action = action_link('file/upload', ['path' => $curr_path]);
$site_title = 'Tải lên tập tin';



echo '<div class="title">' . $site_title . '</div>';

echo '<div class="list">
  <span>' . file_print_path($curr_path, true) . '</span><hr/>
  <form enctype="multipart/form-data">        
    <div id="fileList"></div>
    <input id="files" type="file" multiple style="display:none">
 
    <button id="buttonChoose" class="button"><img src="'. home('icon/file.png') .'" alt=""/> Chọn file</button>
    <button id="buttonReset" class="button"><img src="'. home('icon/delete.png') .'" alt=""/> Reset</button>
    <br>
    <button id="buttonUpload" class="button"><img src="'. home('icon/upload.png') .'" alt=""/> Tải lên</button>
  </form>
</div>';

?>

<script>
    Manager.action = "<?= $action ?>";
</script>