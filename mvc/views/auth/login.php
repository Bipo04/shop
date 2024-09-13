<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo _WEB_ROOT ?>/public/clients/css/log_styles.css">
</head>

<body>
    <div class="navbar">
        <div class="navbar-container">
            <div class="brand-container">
                <a href="http://localhost:8088/web/home">
                    <h1 class="name-shop"> <img src="<?=_WEB_ROOT?>/public/clients/images/logo-01.jpg" alt=""
                            style="height:50px;">
                        <div class="name-1">Angel</div>
                        <div class="name-2">Babie</div>
                    </h1>
                </a>
            </div>
            <div class="tittle">
                Đăng nhập
            </div>
        </div>
    </div>
    <div class="container">
        <div class="login form">
            <header>Login</header>
            <form action="" method="post">
                <?php
if(isset($_SESSION['log']) && $_SESSION['log'] == 'false')
{
    $kq = '<p style="color:red; font-size: 17px">Sai tài khoản hoặc mật khẩu</p>';
    echo $kq;
    unset($_SESSION['log']);
}
?>
                <input required="true" type="text" name="username" id="user_log" placeholder="Enter your username">
                <input required="true" type="password" name="password" id="pass_log" placeholder="Enter your password">
                <input required="true" type="submit" name="btn_log" class="button" value="Login">
            </form>
            <div class="signup">
                <span class="signup">Don't have an account?
                    <a style="text-decoration: none; font-size: 17px" href="register">Đăng ký</a>
                </span>
            </div>
        </div>
    </div>
</body>

</html>