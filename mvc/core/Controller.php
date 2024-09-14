<?php
class Controller {
    public function view($view, $data = []) {
        if(file_exists("./mvc/views/".$view.".php")) {
            require_once "./mvc/views/".$view.".php";
        }
    }
    
    public function model($model) {
        if(file_exists("./mvc/models/".$model.".php")) {
            require_once "./mvc/models/".$model.".php";
            return new $model;
        }
    }

    function helper($helper){
        require_once "./mvc/helpers/".$helper.".php";
        return new $helper;
    }
}
?>