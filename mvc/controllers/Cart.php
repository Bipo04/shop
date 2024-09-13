<?php
class Cart extends Controller {
    public $ProductModel;
    public $OrderDetailModel;
    public $OrdersModel;
    
    public function __construct() {
        $this->ProductModel        = $this->model("ProductModels");
        $this->OrderDetailModel    = $this->model('OrderDetailModels');
        $this->OrdersModel         = $this->model('OrdersModels');
    }

    public function index() {
        if(isset($_COOKIE['userId'])) {
            $this->view("layouts/client_layout", [
                "page" => "cart/index",
                "css" => ['shopping'],
            ]);
        }
        else {
            header('location: http://localhost:8088/web/auth/login');
        }
    }
    
    public function add() {
        if(isset($_COOKIE['userId'])) {
            if(isset($_POST["add-card-btn"])) {
                unset($_POST["add-card-btn"]);
                $request = new Request();
                $data = $request->postFields();
                if(isset($_SESSION[$_COOKIE['userId']]['cart'])) {
                    $found = false;
                    for($i = 0; $i < count($_SESSION[$_COOKIE['userId']]['cart']); $i++) {
                        if($_SESSION[$_COOKIE['userId']]['cart'][$i]['id'] == $data['id']) {
                            $_SESSION[$_COOKIE['userId']]['cart'][$i]['quantity'] += $data['quantity'];
                            $found = true;
                            break;
                        }
                    }
                    if(!$found) {
                        $_SESSION[$_COOKIE['userId']]['cart'][] = $data;
                    }
                }
                else {
                    $_SESSION[$_COOKIE['userId']]['cart'][] = $data;
                }
            }
            header('location: http://localhost:8088/web/cart');
        }
        else {
            header('location: http://localhost:8088/web/auth/login');
        }
    }

    public function update() {
        if(isset($_COOKIE['userId'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $index = $_POST['index'];
                $quantity = $_POST['quantity'];
                $_SESSION[$_COOKIE['userId']]['cart'][$index]['quantity'] = $quantity;
            }
        }
        else {
            header('location: http://localhost:8088/web/auth/login');
        }
    }

    public function delete() {
        if(isset($_COOKIE['userId'])) {
            $index = $_GET['index'];
            unset($_SESSION[$_COOKIE['userId']]['cart'][$index]);
            header('location: http://localhost:8088/web/cart');
        }
        else {
            header('location: http://localhost:8088/web/auth/login');
        }
    }

    public function checkout() {
        if(isset($_COOKIE['userId'])) {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(isset($_POST['type']) && $_POST['type'] == 'change') {
                    $_SESSION[$_COOKIE['userId']]['temp']['fullname'] = $_POST['fullname'];
                    $_SESSION[$_COOKIE['userId']]['temp']['phone_number'] = $_POST['phone_number'];
                    $_SESSION[$_COOKIE['userId']]['temp']['address'] = $_POST['address'];
                }
                if(isset($_POST['buy-btn'])) {
                    unset($_POST['buy-btn']);
                    $res = new Request();
                    $_SESSION[$_COOKIE['userId']]['buy1'][] = $res->postFields();
                }
                $cart = null;
                if(isset($_SESSION[$_COOKIE['userId']]['buy1'])) {
                    $cart = $_SESSION[$_COOKIE['userId']]['buy1'];
                    $_SESSION[$_COOKIE['userId']]['buy2'] = $_SESSION[$_COOKIE['userId']]['buy1'];
                    unset($_SESSION[$_COOKIE['userId']]['buy1']);
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
        else {
            header('location: http://localhost:8088/web/auth/login');
        }
    }
    // id, user_id, order_date, status, consignee_name, address, phone_number
    public function buy() {
        if(isset($_COOKIE['userId'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['type']) && $_POST['type'] == 'buy') {
                $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                $time = $now->format('Y-m-d H:i:s');
                $data = [   'user_id'           => $_SESSION[$_COOKIE['userId']]['id'],
                            'order_date'        => $time,
                            'total_money'       => $_POST['totalprice'],
                            'consignee_name'    => $_POST['consignee_name'],
                            'address'           => $_POST['address'],
                            'phone_number'      => $_POST['phone_number']
                ];
                $kq = $this->OrdersModel->add($data);
                $result = json_decode($kq, true);
                if($result['type'] == 'success') {
                    if(isset($_SESSION[$_COOKIE['userId']]['buy2'])) {
                        $products = $_SESSION[$_COOKIE['userId']]['buy2'];
                        unset($_SESSION[$_COOKIE['userId']]['buy2']);
                    }
                    else {
                        $products = $_SESSION[$_COOKIE['userId']]['cart'];
                        unset($_SESSION[$_COOKIE['userId']]['cart']);
                        echo 'da xoa';
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
        else {
            header('location: http://localhost:8088/web/auth/login');
        }
    }
}
?>