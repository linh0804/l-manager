<?php
defined('ACCESS') or exit;

$curr_path = get_curr_path();

if (IS_LOGIN) {
    if (auth_get_login_fail()) {
        $site_sidebar .= '<div class="list" style="font-size: small; font-style: italic">
            fail login: <span style="color: red; font-weight: bold">' . auth_get_login_fail() . '</span>
        </div>';
    }
    // function
    $site_sidebar .= '<div class="title">Chức năng</div>
    <ul class="list">
        <li><img src="'. home('icon/home.png') .'"/> <a href="' . action_link('setting_home') . '">Sửa Trang chủ</a></li>
        <li><img src="'. home('icon/search.png') .'"/> <a href="' . action_link('folder_compare_tree') . '">So sánh thư mục</a></li>
        <li><img src="'. home('icon/mime/unknown.png') .'"/> <a href="' . action_link('command', ['path' => $curr_path]) . '">Chạy lệnh</a></li>
        <li><img src="'. home('icon/mime/unknown.png') .'"/> <a href="' . action_link('composer', ['path' => $curr_path]) . '">Chạy lệnh Composer</a></li>
        <li><img src="'. home('icon/mime/unknown.png') .'"/> <a href="' . action_link('file_fix_perms', ['path' => $curr_path]) . '">Fix chown/chmod</a></li>
        <li><img src="'. home('icon/rows.png') .'"/> <a href="'. action_link('webdav.php/'.ltrim($curr_path, '/')) .'">Webdav</a></li>
        <li><img src="'. home('icon/mime/php.png') .'"/> <a href="' . action_link('phpinfo') . '">phpinfo()</a></li>
    </ul>';
    
    // filelist
    $site_sidebar .= '<div class="title">Sửa gần đây</div>';
    $site_sidebar .= '<div class="list" id="fm_edit_recent_list"></div>';
    // end filelist

    $site_sidebar .= '<div class="list" style="font-size: small; font-style: italic">
        run on: ' . get_current_user() . ' (' . getmyuid() . ')
    </div>';

    echo '<div class="menu-toggle">' . $site_sidebar . '</div>';
}
?>
</div>
<div id="app-footer">
    <span>Version: <a href="https://github.com/linh0804/file-manager"><?= APP_VERSION ?></a></span>
    <br><br>[ <a href="<?= action_link('logout') ?>">Đăng Xuất</a> ]
</div>
<div id="toast-container"></div>
<script>nightmare_scrolltop.init();</script>

<div id="menu-overlay"></div>
    <div id="box-overlay"></div>

<script>edit_recent.render("fm_edit_recent_list");</script>

</div>

<script>
    if (!sessionStorage.getItem("fm_cron")) {
        sessionStorage.setItem("fm_cron", "true");
        
        $.getJSON("cron.php", function(response) {
            $("#app-index-updater").html(response.data);
        });
    }
</script>
   <script src="<?= home('asset/web.js?t=' . time()) ?>"></script>
</body>
</html>