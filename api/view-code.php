<?php

define('ACCESS', true);

require dirname(__DIR__) . '/system/_init.php';

$curr_path = postJson('content', 'path');

$curr_file = new SplFileInfo($curr_path);

if (check_path($curr_path)) {
     echo check_path($curr_path);
     return;     
}

$dir = dirname($curr_path);
$name = basename($curr_path);
$site_title = 'Xem tập tin';

$themes = ['a11y-light','a11y-dark','vs','xcode','github-dark-dimmed','github'];
$coder = ['Auto','php','javascript','html','json','text'];

function highlight_string_with_line_numbers($code) {
    $code = str_replace("\r\n", "\n", $code);
    $code = str_replace("\r", "\n", $code);
    $lines = explode("\n", $code);
    $lineCount = count($lines);
    $result = [];
    for ($i = 0; $i < $lineCount; $i++) {
        $result[] = sprintf('<span class="line">%3d</span>', $i + 1);
    }
    $text = '';
    for ($i = ($lineCount-1); $i >= 0; $i--) {
        if(isset($lines[$i]) && $lines[$i] != '') break;
        $text .= '<br /> ';
    }
    return array(
        'line' => implode('',$result),
        'text' => $text
    );
}

function detect_code_type($code) {
    if (strpos((string) $code, "<?php") !== false || strpos((string) $code, "<?=") !== false) {
        return "php";
    } elseif (strpos((string) $code, "const ") !== false || strpos((string) $code, "var ") !== false || strpos((string) $code, "function ") !== false || strpos((string) $code, "document.") !== false) {
        return "javascript";
    } elseif (strpos((string) $code, "background-color") !== false || strpos((string) $code, "background") !== false || strpos((string) $code, "-wekit-") !== false) {
        return "css";
    } elseif (strpos((string) $code, "{\"") !== false && strpos((string) $code, "\"}") !== false && strpos((string) $code, "\":\"") !== false){
        return "json";
    } else {
        return "html";
    }
}

if (!is_file($curr_path)) {
    echo '<div class="list"><span>Đường dẫn không tồn tại</span></div>
    <div class="title">Chức năng</div>
    <ul class="list">
        <li><img src="'. home('icon/list.png') .'"/> <a href="' . action_link(null) . '">Danh sách</a></li>
    </ul>';
} else if (!file_is_text($name) && !file_is_unknown($name)) {
    echo '<div class="list"><span>Tập tin này không phải dạng văn bản</span></div>
    <div class="title">Chức năng</div>
    <ul class="list">
        <li><img src="'. home('icon/list.png') .'"/> <a href="' . action_link(null, ['path' => $dir]) . '">Danh sách</a></li>
    </ul>';
} else {
    $content = file_get_contents($curr_path);
    $hightlight = highlight_string_with_line_numbers($content);
    echo '<div class="list" id="view_code">
        <div id="line_number">'. $hightlight['line'] .'</div>
        <div id="code_content">
            <pre><code class="language-' . detect_code_type($content) .'">'
                . htmlspecialchars($content)
                . $hightlight['text']
            . '</code></pre>
        </div>
    </div>';

    echo '<div class="title">Tùy chỉnh</div>
        <div class="list">
        Giao diện<br />';
    echo '<select id="themes">';
    foreach($themes as $key) {
        echo '<option value="'. $key .'">'.
            $key .'
        </option>';
    }
    echo '</select>
        <hr />
        Cú pháp<br />';
    echo '<select id="coder">';
    foreach($coder as $key) {
        echo '<option value="'. (($key == 'Auto') ? '' : 'language-'. $key) .'">'.
            $key .'
        </option>';
    }
    echo '</select>
        </div>';   
}

