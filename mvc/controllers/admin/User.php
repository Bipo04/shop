<?php
class User extends Controller {
    public $UserModel;

    public function __construct() {
        $this->UserModel = $this->model('UserModels');
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
                    $select =   ['Users.id', 'fullname', 'username', 'email',
                                'phone_number', 'address', 'Roles.name', 'created_at'];
                    $users = $this->UserModel->selectJoin($select ,null, null, 'Roles', ['role_id', 'id'], 'INNER');
                    
                    $this->view('layouts/admin_layout', [
                        'page'  => 'user/index',
                        'title' => 'Danh sách người dùng',
                        'users' => $users,
                        "type"  => "qli",
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
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $id = $_POST['id'];
                        $role = strtolower($_POST['role']);
                        $query = "EXEC updateUser ".$id.", '".$role."'";
                        echo $query;
                        $this->UserModel->queryExecute($query);
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
                        $this->UserModel->deleteById($id);
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