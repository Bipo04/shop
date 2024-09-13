<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                Đăng ký
            </div>
        </div>
    </div>
    <div class="container">
        <div class="login form">
            <header>Sign up</header>
            <form action="" method="post">

                <?php
if(isset($_SESSION['reg']))
{
    if($_SESSION['reg'] == 'true')
    {
        $kq = '<p style="color:red; font-size: 17px">Đăng ký thành công.</p>';
        echo $kq;
    }
    else if($_SESSION['reg'] == 'false')
    {
        $kq = '<p style="color:red; font-size: 17px">Tài khoản đã tồn tại.</p>';
        echo $kq;
    }
    unset($_SESSION['reg']);
}
?>

                <input required="true" type="text" name="fullname" id="name_reg" placeholder="Enter your fullname">
                <input required="true" type="text" name="username" id="user_reg" placeholder="Enter your username">
                <input required="true" type="text" name="password" id="pass_reg" placeholder="Create a password">
                <input type="submit" name="btn_reg" class="button" value="Sign up">
            </form>
            <div class="signup">
                <span class="signup">Already have an account?
                    <a style="text-decoration: none; font-size: 17px" href="login">Đăng nhập</a>
                </span>
            </div>
        </div>
    </div>
</body>

</html>