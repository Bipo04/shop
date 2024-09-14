<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo _WEB_ROOT ?>/public/clients/css/log_styles.css">
    <style>
        #notification {
            display: none; 
            align-items: center; 
            justify-content: center; 
            position: fixed;
            top: 0;
            left: 50%; 
            transform: translate(-50%); 
            width: 50%;
            background-color: red;
            color: white;
            text-align: center;
            padding: 15px;
            z-index: 1000; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 20px;
            height: 65px;
        }
    </style>
</head>

<body>
    <div id="notification">Session của bạn đã hết hạn. Vui lòng đăng nhập lại.</div>
    <div class="navbar">
        <div class="navbar-container">
            <div class="brand-container">
                <a href="http://localhost:8088/shop/home">
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
                if(isset($_SESSION['log']) && $_SESSION['log'] == 'false') {
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($data['mess'])): ?>
                var notification = document.getElementById('notification');
                notification.style.display = 'flex'; // Hiển thị thông báo
                setTimeout(function() {
                    notification.style.display = 'none'; // Ẩn thông báo sau 5 giây
                }, 5000); // 5000ms = 5 giây
            <?php endif; ?>
        });
    </script>
</body>
</html>
