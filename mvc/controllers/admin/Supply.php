<?php
class Supply extends Controller {
    public $SupplyModel;

    public function __construct() {
        $this->SupplyModel = $this->model('SupplyModels');
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
                    $supplies = $this->SupplyModel->getdata();
                    $this->view('layouts/admin_layout', [
                        'page'      => 'supply/index',
                        'title'     => 'Danh sách nhà cung cấp',
                        'supplies'  => $supplies,
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
                        $this->SupplyModel->add($data);
                        header('location: http://localhost:8088/shop/admin/supply');
                    }
            
                    $this->view('layouts/admin_layout', [
                        'page' => 'supply/add',
                        'title' => 'Thêm nhà cung cấp',
                        'type' => 'qli',
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

    public function update() {
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
                        $id = $data['id'];
                        unset($data['id']);
                        $this->SupplyModel->update($data, ['id' => $id]);
                        header('location: http://localhost:8088/shop/admin/supply');
                    }
                    $id = $_GET['id'];
                    $supply = $this->SupplyModel->findAll(['*'], ['id' => $id]);
                    $this->view('layouts/admin_layout', [
                        'page' => 'supply/update',
                        'title' => 'Cập nhật thông tin nhà cung cấp',
                        'type' => 'qli',
                        'supply' => $supply[0]
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
}
?>