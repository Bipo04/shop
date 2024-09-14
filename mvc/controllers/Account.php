<?php
class Account extends Controller {
    public $UserModel;
    public $OrdersModel;
    
    public function __construct() {
        $this->UserModel       = $this->model('UserModels');
        $this->OrdersModel     = $this->model('OrdersModels');
        $this->Jwtoken          = $this->helper('Jwtoken');
        $this->Authorzation     = $this->helper('Authorzation');
    }

    public function profile() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $req = new Request();
                        $data = $req->postFields();
                        $result = $this->UserModel->update($data, ['id' => $_SESSION['user']['id']]);
                        $check = json_decode($result,true);
                        print_r($check);
                        die;
                        $_SESSION['user']['fullname'] = $data['fullname'];
                        $_SESSION['user']['email'] = $data['email'];
                        $_SESSION['user']['phone_number'] = $data['phone_number'];
                        $_SESSION['user']['address'] = $data['address'];
                    }
                    $this->view('layouts/client_layout', [
                        'page'  => 'account/profile',
                        'css'   => ['account']
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
            header('location: http://localhost:8088/shop/auth/login');
        }
    }

    public function purchase() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->OrdersModel->update(['status' => 'Đã hủy'], ['id' => $_POST['id']]);
                    }
                    
                    $type = isset($_GET['type']) ? $_GET['type'] : 1;
                    $statusFilter = [
                        '1' => null,
                        '2' => 'Chờ xử lí',
                        '3' => 'Đang chuẩn bị',
                        '4' => 'Đang giao hàng',
                        '5' => 'Đã giao hàng',
                        '6' => 'Đã hủy',
                    ];
                    
                    $statusCondition = isset($statusFilter[$type]) ? $statusFilter[$type] : null;
                    $userId = $_SESSION['user']['id'];
                    
                    $conditions = ['user_id' => $userId];
                    if ($statusCondition) {
                        $conditions['status'] = $statusCondition;
                    }
        
                    $kq = $this->OrdersModel->findAll(['*'], $conditions, 'order_date', 'desc');
                    
                    $data = $this->OrdersModel->queryExecute('SELECT * FROM dbo.getPurchase(' . $userId . ')');
                    foreach ($kq as &$order) {
                        $order['products'] = [];
                        foreach ($data as $a) {
                            if ($a['order_id'] == $order['id']) {
                                $order['products'][] = $a;
                            }
                        }
                    }
                    
                    $this->view('layouts/client_layout', [
                        'page'      => 'account/purchase',
                        'css'       => ['account'],
                        'purchase'  => $kq,
                        'type'      => $type,
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
            header('location: http://localhost:8088/shop/auth/login');
        }
    }
    
}
?>