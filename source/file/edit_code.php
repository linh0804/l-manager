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

$content = (string) file_get_contents($curr_path);
$file_ext = file_get_ext($name);
$editor_path = base64_encode($curr_path);
$is_execute = function_can_use('exec');
$can_format = file_can_format_code($name);
$can_syntax = $is_execute && $file_ext === 'php';

$code_lang = 'text';
$code_langs = [
    'text' => 'text',
    'js' => 'javascript',
    'html' => 'html',
    'php' => 'php',
    'txt' => 'text',
    'sql' => 'sql',
    'json' => 'json',
    'css' => 'css',
    'twig' => 'jinja',
    'md' => 'markdown',
    'yml' => 'yaml',
    'yaml' => 'yaml'
];
ksort($code_langs);

if (array_key_exists($file_ext, $code_langs)) {
    $code_lang = $file_ext;
}
?>

<div class="title"><?= file_print_path($dir, true) ?></div>

<style>
    #code_check_message {
        display: none;
    }

    .cm-editor {
        height: 100%;
        font-size: 12px;
        line-height: 1.25;
    }
    
    .cm-panel {
        padding: 5px 10px;
        font-family: monospace;
      }
</style>

<div class="list">
    <div class="break-word">
        <span class="bull">&bull; </span>Tập tin: <strong class="file_name_edit"><?= $name ?></strong>
    </div><hr>

    <div id="editor-panel" style="display:block; width:100%; overflow-x:auto; white-space:nowrap; padding-bottom: 4px">
        <button type="button" class="button" id="editor-save">
            Lưu lại
        </button>

        <a href="<?= action_link('file/edit_text', ['path' => $curr_path]) ?>">
            <button type="button" class="button">[Text]</button>
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
        
        <select id="code_lang">
            <?php foreach ($code_langs as $code_type_key => $code_type_value): ?>
                <option value="<?= $code_type_value ?>" <?= $code_lang === $code_type_key ? 'selected="selected"' : '' ?>>Mode: <?= $code_type_key ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <form
        id="code_form"
        action="javascript:void(0)"
        data-path="<?= htmlspecialchars($editor_path, ENT_QUOTES) ?>"
        data-file-ext="<?= htmlspecialchars($file_ext, ENT_QUOTES) ?>"
    >
        <textarea id="editor-content" style="display: none"><?= PHP_EOL . htmlspecialchars($content) ?></textarea>
        <div id="editor"></div>
    </form>
</div>

<div id="code_check_message" class="list"></div>

<script>window.EditContext = false</script>
<script src="<?= home('asset/edit_code.bundle.js') ?>"></script>


<script>edit_recent.add('<?= htmlspecialchars($curr_path, ENT_QUOTES) ?>');</script>

<?php file_display_actions($curr_path); ?>