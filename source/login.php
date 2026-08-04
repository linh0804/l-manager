<?php 
defined('ACCESS') or exit;
    if (IS_LOGIN) redirect(action_link());
    $notice = '';
    if (IS_CONFIG_ERROR) {
        $notice .= '<div class="notice_failure">Cấu hình bị lỗi sẽ đưa về mặc định</div>';
        config()->set([
            'username' => LOGIN_USERNAME_DEFAULT,
            'password' => md5(LOGIN_PASSWORD_DEFAULT),
        ]);

        $notice .= '<div class="notice_info">Tài khoản: <strong>' . LOGIN_USERNAME_DEFAULT . '</strong>, Mật khẩu: <strong>' . LOGIN_PASSWORD_DEFAULT . '</strong></div>';
    }
    $username = (string) post('username', '');
    $password = (string) post('password');
    if (is_submit()) {       
        if (empty($username) || empty($password)) {
            $notice = 'Chưa nhập đầy đủ thông tin';
        } elseif (
            strtolower($username) != strtolower((string) config()->get('username'))
            || md5($password) != config()->get('password')
        ) {
            $notice = 'Sai tài khoản hoặc mật khẩu.';
            // khoá đăng nhập sau 5 lần
            auth_increase_login_fail();
            $notice = 'Bạn còn ' . (LOGIN_MAX - auth_get_login_fail()) . ' lần thử!';
        } else {
            auth_reset_fail_login();
            setcookie(APP_NAME . '_auth', (string) md5($password), time() + 3600 * 24 * 365, '/');

            redirect(action_link());
        }
    }
?>
<div class="card">
    <div class="card-body">
        Đăng nhập
    </div>
    <?= $notice ?>
    <form class="form-login" action="<?= home('index.php/login') ?>" method="POST">
        <div class="form-control">
            <input type="text" name="username" value="<?= $username ?>" size="18" required/>
            <label><span>Tài Khoản</span></label>
        </div>
        <div class="form-control">
            <input type="password" id="password" name="password" value="" size="18" required/>
            <label><span>Mật Khẩu</span></label>
            <div class="eyePass"><img id="eyePass" src="https://www.svgrepo.com/show/380010/eye-password-show.svg" /></div>
        </div>
        <input type="submit" name="submit" value="Đăng nhập"/>
    </form>
</div>