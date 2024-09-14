<?php
class Auth extends Controller {
    public $AuthModel;
    
    public function __construct() {
        $this->AuthModel        = $this->model('AuthModels');
        $this->Jwtoken          = $this->helper('Jwtoken');
        $this->Authorzation     = $this->helper('Authorzation');
    }

    public function index() {
        echo "ERROR";
    }

    public function login() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);
                $auth = $this->Authorzation->checkAuth($verify);
                if ($auth == true) {
                    header('location: '.base_url_admin.'/dashboard');
                    die;
                }
                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    header('location: '.base_url.'/home');
                    die;
                }
            }
            else {
                setcookie('token', '', time() - 3600, "/");
                unset($_SESSION['user']);
                unset($_SESSION['role']);
                $this->view("auth/login", ['mess' => 'session expired']);
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
                    $array = [
                        'time'      => time() + (3600 * 24),
                        'keys'      => KEYS,
                        'info'      => [
                            'id'        => $check['id'],
                            'username'  => $check['username']
                        ]
                        
                    ];
                    $jwt = $this->Jwtoken->CreateToken($array);
                    session_unset();
                    setcookie('token', $jwt, [
                        'expires' => time() + (3600 * 24),
                        'path' => '/',
                        'httponly' => true,
                    ]);
                    if ($check['role'] == 'admin') {
                        $_SESSION['user'] = ['role' => 1,
                                            'id' => $check['id'],
                                            'fullname' => $check['fullname'], 
                                            'phone_number' => $check['phone_number'],
                                            'address' => $check['address'],
                                            'email' => $check['email']];
                        header('location: http://localhost:8088/shop/admin/dashboard');
                        exit(); 
                    }
                    if ($check['role'] == 'user') {
                        $_SESSION['user'] = ['role' => 2, 
                                            'id' => $check['id'],
                                            'fullname' => $check['fullname'], 
                                            'phone_number' => $check['phone_number'],
                                            'address' => $check['address'],
                                            'email' => $check['email']];
                        header('location: http://localhost:8088/shop/home');
                        exit();
                    }
            
                } else {
                    $_SESSION['log'] = 'false';
                }
            }
            if(isset($_GET['expired'])) {
                $this->view("auth/login", ['mess' => 'session expired']);
            }
            else
                $this->view("auth/login");
        }
    }

    public function register() {
        if(isset($_COOKIE['userId'])) {
            if($_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
                header('location: http://localhost:8088/shop/admin/dashboard');
            }
            else {
                header('location: http://localhost:8088/shop/home');
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
        setcookie('token', '', time() - 3600, "/");
        unset($_SESSION['user']);
        unset($_SESSION['role']);
        header('location: http://localhost:8088/shop/auth/login');
        exit();
    }
}

?>