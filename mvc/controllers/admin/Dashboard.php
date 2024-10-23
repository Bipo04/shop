<?php
class Dashboard extends Controller {
    public $OrdersModel;
    public function __construct() {
        $this->OrdersModel = $this->model('OrdersModels');
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
                    if(isset($_GET['date'])) {
                        $date = $_GET['date'];
                    }
                    else {
                        $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                        $date = $now->format('Y-m-d');
                    }
                    $orderSold = $this->OrdersModel->queryExecute(
                        "SELECT COUNT(*) AS total_orders FROM Orders WHERE DATE(order_date) = '".$date."' AND status <> 'Đã hủy'"
                    );

                    $stmt = $this->OrdersModel->queryExecute("CALL DayRevenue('".$date."', '".$date."')");
                    $totalRevenue = 0;
                    $totalProfit = 0;
                    foreach ($stmt as $row) {
                        $totalRevenue += $row['Revenue'];
                        $totalProfit += ($row['Revenue'] - ($row['Inbound_price'] * $row['sold']));
                    }
                    $kq = [
                        'TotalRevenue' => $totalRevenue,
                        'Profit' => $totalProfit,
                    ];
                    $order = $this->OrdersModel->queryExecute(
                        "SELECT * FROM Orders WHERE DATE(order_date) = '".$date."' AND status <> 'Đã hủy'"
                    );
                    $status = $this->OrdersModel->queryExecute(
                        "SELECT status, COUNT(status) AS StatusCount
                        FROM Orders WHERE DATE(order_date) = '".$date."' AND status <> 'Đã hủy'
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
                        'kq'        => $kq,
                        'order'     => $order,
                        'statusShow'=> $statusShow,
                        'date'      => $date
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