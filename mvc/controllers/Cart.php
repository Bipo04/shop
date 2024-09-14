<?php
class Cart extends Controller {
    public $ProductModel;
    public $OrderDetailModel;
    public $OrdersModel;
    
    public function __construct() {
        $this->ProductModel        = $this->model("ProductModels");
        $this->OrderDetailModel    = $this->model('OrderDetailModels');
        $this->OrdersModel         = $this->model('OrdersModels');
        $this->Jwtoken          = $this->helper('Jwtoken');
        $this->Authorzation     = $this->helper('Authorzation');
    }

    public function index() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    $this->view("layouts/client_layout", [
                        "page" => "cart/index",
                        "css" => ['shopping'],
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
    
    public function add() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    if(isset($_POST["add-card-btn"])) {
                        unset($_POST["add-card-btn"]);
                        $request = new Request();
                        $data = $request->postFields();
                        if(isset($_SESSION['cart'])) {
                            $found = false;
                            for($i = 0; $i < count($_SESSION['cart']); $i++) {
                                if($_SESSION['cart'][$i]['id'] == $data['id']) {
                                    $_SESSION['cart'][$i]['quantity'] += $data['quantity'];
                                    $found = true;
                                    break;
                                }
                            }
                            if(!$found) {
                                $_SESSION['cart'][] = $data;
                            }
                        }
                        else {
                            $_SESSION['cart'][] = $data;
                        }
                    }
                    header('location: http://localhost:8088/shop/cart');
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

    public function update() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);
                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $index = $_POST['index'];
                        $quantity = $_POST['quantity'];
                        $_SESSION['cart'][$index]['quantity'] = $quantity;
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
            header('location: http://localhost:8088/shop/auth/login');
        }
    }

    public function delete() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);
                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    $index = $_GET['index'];
                    unset($_SESSION['cart'][$index]);
                    header('location: http://localhost:8088/shop/cart');
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

    public function checkout() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);
                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if(isset($_POST['type']) && $_POST['type'] == 'change') {
                            $_SESSION['temp']['fullname'] = $_POST['fullname'];
                            $_SESSION['temp']['phone_number'] = $_POST['phone_number'];
                            $_SESSION['temp']['address'] = $_POST['address'];
                        }
                        if(isset($_POST['buy-btn'])) {
                            unset($_POST['buy-btn']);
                            $res = new Request();
                            $_SESSION['buy1'][] = $res->postFields();
                        }
                        $cart = null;
                        if(isset($_SESSION['buy1'])) {
                            $cart = $_SESSION['buy1'];
                            $_SESSION['buy2'] = $_SESSION['buy1'];
                            unset($_SESSION['buy1']);
                        }
                        $this->view("layouts/client_layout", [
                            "page"  => "cart/checkout",
                            "css"   => ['formbuy'],
                            "cart"  => $cart
                        ]);
                    }
                    else {
                        require_once './mvc/errors/404.php';
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
            header('location: http://localhost:8088/shop/auth/login');
        }
    }
    // id, user_id, order_date, status, consignee_name, address, phone_number
    public function buy() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);
                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser == true) {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['type']) && $_POST['type'] == 'buy') {
                        $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                        $time = $now->format('Y-m-d H:i:s');
                        $data = [   'user_id'           => $_SESSION['user']['id'],
                                    'order_date'        => $time,
                                    'total_money'       => $_POST['totalprice'],
                                    'consignee_name'    => $_POST['consignee_name'],
                                    'address'           => $_POST['address'],
                                    'phone_number'      => $_POST['phone_number']
                        ];
                        $kq = $this->OrdersModel->add($data);
                        $result = json_decode($kq, true);
                        if($result['type'] == 'success') {
                            if(isset($_SESSION['buy2'])) {
                                $products = $_SESSION['buy2'];
                                unset($_SESSION['buy2']);
                            }
                            else {
                                $products = $_SESSION['cart'];
                                unset($_SESSION['cart']);
                            }
                            foreach($products as $item) {
                                $d = [  'order_id' => $result['id'],
                                        'product_id' => $item['id'],
                                        'num' => $item['quantity'],
                                        'price' => $item['price']
                                ];
                                $this->OrderDetailModel->add($d);
                            }
                        }
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
            header('location: http://localhost:8088/shop/auth/login');
        }
    }
}
?>