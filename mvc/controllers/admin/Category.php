<?php
class Category extends Controller {
    public $CategoryModel;
    public function __construct() {
        $this->CategoryModel = $this->model('CategoryModels');
        $this->Jwtoken          = $this->helper('Jwtoken');
        $this->Authorzation     = $this->helper('Authorzation');
    }
    
    public function index() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $auth = $this->Authorzation->checkAuth($verify);
                if($auth == true) {
                    $categories = $this->CategoryModel->getdata();
                    $this->view('layouts/admin_layout', [
                        'page'      => 'category/index',
                        'title'     => 'Danh sách danh mục',
                        'category'  => $categories,
                        'type'      => 'qli',
                    ]);
                }
            }
            else {
                setcookie('token', '', time() - 3600, "/");
                unset($_SESSION['user']);
                header('location: '.base_url.'/auth/login?expired=true');
            }
        }
        else {
            require_once './mvc/errors/forbidden.php';
        }
    }
    
    public function add() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $auth = $this->Authorzation->checkAuth($verify);
                if($auth == true) {
                    if(isset($_POST['btn'])) {
                        $req = new Request();
                        unset($_POST['btn']);
                        $data = $req->postFields();
                        $this->CategoryModel->add($data);
                        header('location: http://localhost:8088/shop/admin/category');
                    }
            
                    $this->view('layouts/admin_layout', [
                        'page'      => 'category/add',
                        'title'     => 'Thêm danh mục',
                        'type'      => 'qli',
                    ]);
                }
            }
            else {
                setcookie('token', '', time() - 3600, "/");
                unset($_SESSION['user']);
                header('location: '.base_url.'/auth/login?expired=true');
            }
        }
        else {
            require_once './mvc/errors/forbidden.php';
        }
    }
    
    public function delete() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $auth = $this->Authorzation->checkAuth($verify);
                if($auth == true) {
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $req = new Request();
                        $data = $req->postFields();
                        $id = $data['id'];
                        $this->CategoryModel->deleteById($id);
                    }
                }
            }
            else {
                setcookie('token', '', time() - 3600, "/");
                unset($_SESSION['user']);
                header('location: '.base_url.'/auth/login?expired=true');
            }
        }
        else {
            require_once './mvc/errors/forbidden.php';
        }
    }

    public function update() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $auth = $this->Authorzation->checkAuth($verify);
                if($auth == true) {
                    $req = new Request();
                    if(isset($_POST['btn']) && $_POST['btn']) {
                        unset($_POST['btn']);
                        $id = $_POST['id'];
                        unset($_POST['id']);
                        $data = $req->postFields();
                        $this->CategoryModel->update($data, ['id' => $id]);
                        header('location: http://localhost:8088/shop/admin/category');
                        die;
                    }
                    $data = $req->getFields();
                    $a = $this->CategoryModel->findAll(['*'], ['id' => $data['id']]);
                    $this->view('layouts/admin_layout', [
                        'page'      => 'category/update',
                        'title'     => 'Cập nhật danh mục',
                        'category'  => $a,
                        'type'      => 'qli',
                    ]);
                }
            }
            else {
                setcookie('token', '', time() - 3600, "/");
                unset($_SESSION['user']);
                header('location: '.base_url.'/auth/login?expired=true');
            }
        }
        else {
            require_once './mvc/errors/forbidden.php';
        }
    }

}
?>