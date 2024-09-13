<?php
class Order extends Controller {
    public $ProductModel;
    public $OrderDetailModel;
    public $OrdersModel;
    
    public function __construct() {
        $this->ProductModel         = $this->model("ProductModels");
        $this->OrderDetailModel     = $this->model('OrderDetailModels');
        $this->OrdersModel          = $this->model('OrdersModels');
    }

    public function index() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            if(isset($_GET['type'])) {
                if($_GET['type'] == '1') {
                    $orders = $this->OrdersModel->findAll(['*'], ['1' => '1'], 'order_date', 'desc');
                }
                if($_GET['type'] == '2') {
                    $orders = $this->OrdersModel->findAll(['*'], ['status' => 'Chờ xử lí'], 'order_date', 'desc');
                }
                if($_GET['type'] == '3') {
                    $orders = $this->OrdersModel->findAll(['*'], ['status' => 'Đang chuẩn bị'], 'order_date', 'desc');
                }
                if($_GET['type'] == '4') {
                    $orders = $this->OrdersModel->findAll(['*'], ['status' => 'Đang giao hàng'], 'order_date', 'desc');
                }
                if($_GET['type'] == '5') {
                    $orders = $this->OrdersModel->findAll(['*'], ['status' => 'Đã giao hàng'], 'order_date', 'desc');
                }
                if($_GET['type'] == '6') {
                    $orders = $this->OrdersModel->findAll(['*'], ['status' => 'Đã hủy'], 'order_date', 'desc');
                }
            }
            else {
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
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }

    public function update() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST['id'];
                $status = $_POST['status'];
                $this->OrdersModel->update(['status' => $status], ['id' => $id]);
            }
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }

    public function detail() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
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
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }
}
?>