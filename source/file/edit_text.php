<?php 
defined('ACCESS') or exit;

$curr_path = get_curr_path();
$curr_file = new SplFileInfo($curr_path);

if (check_path($curr_path)) {
     echo check_path($curr_path);
     return;     
}

$dir = dirname($curr_path);
$name = basename($curr_path);
$site_title = 'Sửa: ' . $name;
?>
<style>
    #code_check_message {
        display:none;
    }
</style>

<div class="title"><?= file_print_path($dir, true) ?></div>

<?php
$total = 0;
$dir = process_directory($dir);
$content = file_get_contents($curr_path);
$is_execute = function_can_use('exec');
$editor_path = base64_encode($curr_path);
$file_ext = file_get_ext($name);
$can_format = file_can_format_code($name);
$can_syntax = $is_execute && $file_ext === 'php';
?>

<div class="list">
    <span class="bull">&bull; </span>Tập tin: <strong class="file_name_edit"><?= $name ?></strong>
    <hr>

    <div id="editor-panel" style="display:block; width:100%; overflow-x:auto; white-space:nowrap; padding-bottom: 0px">
        <button type="button" class="button" id="editor-save">
            Lưu lại
        </button>

        <a href="<?= action_link('file/edit_code', [
            'path' => $curr_path
        ]) ?>">
            <button type="button" class="button">[Code]</button>
        </a>

        <button type="button" class="button" id="editor-wrap">
            Wrap
        </button>

        <button
            type="button"
            class="button"
            id="editor-syntax"
            <?= $can_syntax ? '' : 'disabled' ?>
        >
            Syntax
        </button>

        <button
            type="button"
            class="button"
            id="editor-format"
            <?= $can_format ? '' : 'disabled' ?>
        >
            Format
        </button>
    </div>

    <form action="javascript:void(0)" id="code_form" method="post">
        <div class="parent_box_edit">
            <textarea id="editor" wrap="soft" style="white-space: nowrap;" class="box_edit" name="content"><?= PHP_EOL . htmlspecialchars($content) ?></textarea>
        </div>
    </form>
</div>

<div id="code_check_message" class="list"></div>

<script>
    Manager.text = <?= json_encode($file_ext) ?>;
    const editorPath = <?= json_encode($editor_path) ?>;
    edit_recent.add('<?= htmlspecialchars($curr_path, ENT_QUOTES) ?>');
</script>

<?php file_display_actions($curr_path); ?>
