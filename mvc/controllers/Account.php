<?php
class Account extends Controller {
    public $UserModel;
    public $OrdersModel;
    
    public function __construct() {
        $this->UserModel       = $this->model('UserModels');
        $this->OrdersModel     = $this->model('OrdersModels');
    }

    public function profile() {
        if(isset($_COOKIE['userId'])) {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $req = new Request();
                $data = $req->postFields();
                $this->UserModel->update($data, ['id' => $_SESSION[$_COOKIE['userId']]['id']]);
                $_SESSION[$_COOKIE['userId']]['fullname'] = $data['fullname'];
                $_SESSION[$_COOKIE['userId']]['email'] = $data['email'];
                $_SESSION[$_COOKIE['userId']]['phone_number'] = $data['phone_number'];
                $_SESSION[$_COOKIE['userId']]['address'] = $data['address'];
            }
            $this->view('layouts/client_layout', [
                'page'  => 'account/profile',
                'css'   => ['account']
            ]);
        }
        else {
            header('location: http://localhost:8088/web/auth/login');
        }
    }

    public function purchase() {
        if(isset($_COOKIE['userId'])) {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->OrdersModel->update(['status' => 'Đã hủy'], ['id' => $_POST['id']]);
            }
            $type = 1;
            if(isset($_GET['type'])) {
                $type = $_GET['type'];
                if($_GET['type'] == '1') {
                    $kq = $this->OrdersModel->findAll(['*'], ['user_id' => $_SESSION[$_COOKIE['userId']]['id']], 'order_date', 'desc');
                    $data = $this->OrdersModel->queryExecute('SELECT * FROM dbo.getPurchase('.$_SESSION[$_COOKIE['userId']]['id'].')');
                    for($i = 0; $i < count($kq); $i++) {
                        foreach($data as $a) {
                            if($a['order_id'] == $kq[$i]['id']) {
                                $kq[$i]['products'][] = $a;
                            }
                        }
                    }
                }
                if($_GET['type'] == '2') {
                    $kq = $this->OrdersModel->findAll(['*'], ['user_id' => $_SESSION[$_COOKIE['userId']]['id'], 'status' => 'Chờ xử lí'], 'order_date', 'desc');
                    $data = $this->OrdersModel->queryExecute('SELECT * FROM dbo.getPurchase('.$_SESSION[$_COOKIE['userId']]['id'].')');
                    for($i = 0; $i < count($kq); $i++) {
                        foreach($data as $a) {
                            if($a['order_id'] == $kq[$i]['id']) {
                                $kq[$i]['products'][] = $a;
                            }
                        }
                    }
                }
                if($_GET['type'] == '3') {
                    $kq = $this->OrdersModel->findAll(['*'], ['user_id' => $_SESSION[$_COOKIE['userId']]['id'], 'status' => 'Đang chuẩn bị'], 'order_date', 'desc');
                    $data = $this->OrdersModel->queryExecute('SELECT * FROM dbo.getPurchase('.$_SESSION[$_COOKIE['userId']]['id'].')');
                    for($i = 0; $i < count($kq); $i++) {
                        foreach($data as $a) {
                            if($a['order_id'] == $kq[$i]['id']) {
                                $kq[$i]['products'][] = $a;
                            }
                        }
                    }
                }
                if($_GET['type'] == '4') {
                    $kq = $this->OrdersModel->findAll(['*'], ['user_id' => $_SESSION[$_COOKIE['userId']]['id'], 'status' => 'Đang giao hàng'], 'order_date', 'desc');
                    $data = $this->OrdersModel->queryExecute('SELECT * FROM dbo.getPurchase('.$_SESSION[$_COOKIE['userId']]['id'].')');
                    for($i = 0; $i < count($kq); $i++) {
                        foreach($data as $a) {
                            if($a['order_id'] == $kq[$i]['id']) {
                                $kq[$i]['products'][] = $a;
                            }
                        }
                    }
                }
                if($_GET['type'] == '5') {
                    $kq = $this->OrdersModel->findAll(['*'], ['user_id' => $_SESSION[$_COOKIE['userId']]['id'], 'status' => 'Đã giao hàng'], 'order_date', 'desc');
                    $data = $this->OrdersModel->queryExecute('SELECT * FROM dbo.getPurchase('.$_SESSION[$_COOKIE['userId']]['id'].')');
                    for($i = 0; $i < count($kq); $i++) {
                        foreach($data as $a) {
                            if($a['order_id'] == $kq[$i]['id']) {
                                $kq[$i]['products'][] = $a;
                            }
                        }
                    }
                }
                if($_GET['type'] == '6') {
                    $kq = $this->OrdersModel->findAll(['*'], ['user_id' => $_SESSION[$_COOKIE['userId']]['id'], 'status' => 'Đã hủy'], 'order_date', 'desc');
                    $data = $this->OrdersModel->queryExecute('SELECT * FROM dbo.getPurchase('.$_SESSION[$_COOKIE['userId']]['id'].')');
                    for($i = 0; $i < count($kq); $i++) {
                        foreach($data as $a) {
                            if($a['order_id'] == $kq[$i]['id']) {
                                $kq[$i]['products'][] = $a;
                            }
                        }
                    }
                }
            }
            else {
                $kq = $this->OrdersModel->findAll(['*'], ['user_id' => $_SESSION[$_COOKIE['userId']]['id']], 'order_date', 'desc');
                $data = $this->OrdersModel->queryExecute('SELECT * FROM dbo.getPurchase('.$_SESSION[$_COOKIE['userId']]['id'].')');
                for($i = 0; $i < count($kq); $i++) {
                    foreach($data as $a) {
                        if($a['order_id'] == $kq[$i]['id']) {
                            $kq[$i]['products'][] = $a;
                        }
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
        else {
            header('location: http://localhost:8088/web/auth/login');
        }
    }
    
}
?>