<?php
class Auth extends Controller {
    public $AuthModel;
    
    public function __construct() {
        $this->AuthModel = $this->model("AuthModels");
    }

    public function index() {
        echo "ERROR";
    }

    public function login() {
        if(isset($_COOKIE['userId'])) {
            echo 1;
            if($_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
                header('location: http://localhost:8088/web/admin/dashboard');
                die;
            }
            else {
                header('location: http://localhost:8088/web/home');
                die;
            }
        }
        else {
            if(isset($_POST['btn_log']) && $_POST['btn_log']) {
                unset($_POST['btn_log']);
                $request = new Request;
                $data = $request->postFields();           
                $result = $this->AuthModel->login($data);
                $check = json_decode($result,true);
                if($check['type'] == 'success') {
                    $id = 'id_' . $check['id'];
                    session_unset(); // Properly clear the session
                    setcookie('userId', $id, time() + (86400 * 30), "/");
                    $_SESSION[$id] = $check['data'];
                    if ($check['role'] == 'admin') {
                        header("Location: http://localhost:8088/web/admin/dashboard");
                        exit(); // Ensure no further code execution
                    }
                    if ($check['role'] == 'user') {
                        if($_SESSION[$id]['email'] == '' && $_SESSION[$id]['phone_number'] =='' && $_SESSION[$id]['address'] =='')
                            header("Location: http://localhost:8088/web/account/profile");
                        else
                            header("Location: http://localhost:8088/web/home");
                        exit(); // Ensure no further code execution
                    }
            
                } else {
                    $_SESSION['log'] = 'false';
                }
            }
                $this->view("auth/login");
        }
    }

    public function register() {
        if(isset($_COOKIE['userId'])) {
            if($_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
                header('location: http://localhost:8088/web/admin/dashboard');
            }
            else {
                header('location: http://localhost:8088/web/home');
            }
        }
        else {
            $arr = array();
            if(isset($_POST['btn_reg']) && $_POST['btn_reg']) {
                unset($_POST['btn_reg']);
                $request = new Request;
                $data = $request->postFields();
                $result = $this->AuthModel->register($data);
                $result = json_decode($result, true);
                if($result['type'] == 'success')
                    $_SESSION['reg'] = 'true';
                else if($result['type'] == 'fail') {
                    $_SESSION['reg'] = 'false';
                }
            }
            $this->view("auth/register");
        }
    }

    public function logout() {
        unset($_SESSION[$_COOKIE['userId']]);
        setcookie('userId', $id, 0, "/");
        header('location: http://localhost:8088/web/auth/login');
    }
}

?>