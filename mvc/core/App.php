<?php
class App {
    protected $controller = "Home";
    protected $action = "index";
    protected $params = [];

    public function __construct() {
        $arr = $this->urlProcess();
        if($arr != NULL) {
            $urlCheck = "";
            $ok = 0;
            foreach($arr as $key => $item) {
                $urlCheck .= "/" . $item . "/";
                $urlCheck = trim($urlCheck, "/");
                $arr_url = explode("/", $urlCheck);
                $arr_url[count($arr_url) - 1] = ucfirst($arr_url[count($arr_url) - 1]);
                $url = implode("/", $arr_url);
                unset($arr[$key]);
                if(file_exists("./mvc/controllers/".$url.".php")) {
                    $ok = 1;
                    require_once "./mvc/controllers/".$url.".php";
                    $this->controller = $arr_url[count($arr_url) - 1];
                    break;
                }
            }
            if($ok == 0) {
                $this->loadError();
                die;
            }
        } else {
            $this->loadError();
        }
        $this->controller = new $this->controller;
        if($arr) {
            $arr = array_values($arr);
        }
        if(isset($arr[0])) {
            $this->action = $arr[0];
            unset($arr[0]);
        }
        $this->params = $arr ? array_values($arr) : [];
        if(method_exists($this->controller,$this->action)) {
            call_user_func_array([$this->controller,$this->action],$this->params);
        }
    }
    
    public function urlProcess() {
        if(isset($_SERVER['PATH_INFO'])) {
            return explode("/", trim($_SERVER['PATH_INFO'], "/"));
        }
    }

    public function loadError($name = '404') {
        require_once './mvc/errors/'.$name.'.php';
    }
}
?>