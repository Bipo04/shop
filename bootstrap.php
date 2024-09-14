<?php
$web_root = "http://" . $_SERVER['HTTP_HOST']."/shop";
define('_WEB_ROOT', $web_root);
require_once "./mvc/core/App.php";
require_once "./mvc/core/Controller.php";
require_once "./mvc/core/Db.php";
require_once "./mvc/core/Constant.php"; //Set up database parameters and JWT key
require_once "./mvc/core/Request.php";
?>