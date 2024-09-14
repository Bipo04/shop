<?php
class Order extends Controller {
    public $ProductModel;
    public $OrderDetailModel;
    public $OrdersModel;
    
    public function __construct() {
        $this->ProductModel         = $this->model("ProductModels");
        $this->OrderDetailModel     = $this->model('OrderDetailModels');
        $this->OrdersModel          = $this->model('OrdersModels');
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
                    if (isset($_GET['type'])) {
                        $statusMap = [
                            '1' => null,     
                            '2' => 'Chờ xử lí',
                            '3' => 'Đang chuẩn bị',
                            '4' => 'Đang giao hàng',
                            '5' => 'Đã giao hàng',
                            '6' => 'Đã hủy'
                        ];
                    
                        $type = $_GET['type'];
                        $statusCondition = isset($statusMap[$type]) ? $statusMap[$type] : null;
                    
                        if ($statusCondition) {
                            $orders = $this->OrdersModel->findAll(['*'], ['status' => $statusCondition], 'order_date', 'desc');
                        } else {
                            $orders = $this->OrdersModel->findAll(['*'], ['1' => '1'], 'order_date', 'desc'); 
                        }
                    } else {
                        $orders = $this->OrdersModel->findAll(['*'], ['1' => '1'], 'order_date', 'desc');
                    }
                    
                    $type = isset($_GET['type']) ? $_GET['type'] : 1;
                    $this->view('layouts/admin_layout', [
                        'page'      => 'order/index',
                        'title'     => 'Danh sách đơn hàng',
                        'type'      => 'qli',
                        'orders'    => $orders,
                        'typ'       => $type,
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
                        $status = $_POST['status'];
                        $this->OrdersModel->update(['status' => $status], ['id' => $id]);
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

    public function detail() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);

                $auth = $this->Authorzation->checkAuth($verify);
                if($auth == true) {
                    $req = new Request();
                    $data = $req->getFields();
                    $id = $data['id'];
                    $order = $this->OrdersModel->findAll(['*'], ['id' => $id]);
                    $select = ['order_id', 'product_id', 'title', 'num', 'price', 'thumbnail'];
                    $orderDetails = $this->OrderDetailModel->selectJoin($select, null, ['order_id' => $id], 
                    'Product', ['product_id', 'id'],  'LEFT');
                    $this->view('layouts/admin_layout', [
                        'page'          => 'order/details',
                        'title'         => 'Chi tiết đơn hàng',
                        'type'          => 'qli',
                        'order'         => $order[0],
                        'orderDetails'  => $orderDetails
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