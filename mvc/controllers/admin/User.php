<?php
class User extends Controller {
    public $UserModel;

    public function __construct() {
        $this->UserModel = $this->model('UserModels');
    }

    public function index() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            $select =   ['Users.id', 'fullname', 'username', 'email',
                        'phone_number', 'address', 'Roles.name', 'created_at'];
            $users = $this->UserModel->selectJoin($select ,null, null, 'Roles', ['role_id', 'id'], 'INNER');
            
            $this->view('layouts/admin_layout', [
                'page'  => 'user/index',
                'title' => 'Danh sách người dùng',
                'users' => $users,
                "type"  => "qli",
            ]);
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }

    public function update() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST['id'];
                $role = strtolower($_POST['role']);
                $query = "EXEC updateUser ".$id.", '".$role."'";
                echo $query;
                $this->UserModel->queryExecute($query);
            }
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }

    public function delete() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $req = new Request();
                $data = $req->postFields();
                $id = $data['id'];
                $this->UserModel->deleteById($id);
                echo 'da xoa';
            }
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }
}
?>