<?php
class Dashboard extends Controller {
    public $OrdersModel;
    public function __construct() {
        $this->OrdersModel = $this->model('OrdersModels');
    }
    
    public function index() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            if(isset($_GET['date'])) {
                $date = $_GET['date'];
            }
            else {
                $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                $date = $now->format('Y-m-d');
            }
            $orderSold = $this->OrdersModel->queryExecute(
                "SELECT COUNT(*) as sold FROM getOrderByDate('".$date."')"
            );
            $kq = $this->OrdersModel->queryExecute(
                "SELECT 
                    SUM(Revenue) AS TotalRevenue, 
                    SUM(Revenue - Inbound_price * sold) AS Profit
                FROM DayRevenue('".$date."','".$date."');"
            );
            $order = $this->OrdersModel->queryExecute(
                "SELECT * FROM getOrderByDate('".$date."')"
            );
            $status = $this->OrdersModel->queryExecute(
                "SELECT status, COUNT(status) AS StatusCount
                FROM getOrderByDate('".$date."')
                GROUP BY status"
            );
            $statusShow['Chờ xử lí'] = 0;
            $statusShow['Đang chuẩn bị'] = 0;
            $statusShow['Đang giao hàng'] = 0;
            $statusShow['Đã giao hàng'] = 0;
            foreach($status as $item) {
                $statusShow[$item['status']] = $item['StatusCount'];
            }
            $this->view('layouts/admin_layout', [
                'page'      => 'dashboard/index',
                'title'     => 'Dashboard',
                'type'      => 'none',
                'orderSold' => $orderSold[0],
                'kq'        => $kq[0],
                'order'     => $order,
                'statusShow'=> $statusShow,
                'date'      => $date
            ]);
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }
}
?>