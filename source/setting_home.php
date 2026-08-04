<?php
defined('ACCESS') or exit;

$home = post('home', config()->get('home', ''));

if (is_submit()) {
    if ($home) {
        config()->set(['home' => $home]);
    } else {
        config()->unset('home');
    }
    
    redirect(action_link(null));
}

$site_title = 'Sửa Trang chủ';


echo '<div class="title">' . $site_title . '</div>';

echo '<div class="list">';

echo '<form method="post">
    <span>Thư mục:</span><br />
    <input type="text" name="home" value="' . htmlspecialchars((string) $home) . '" /><br />

   <input type="submit" name="submit" value="OK" />
</form>';

echo '</div>';
