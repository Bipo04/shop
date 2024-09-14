<?php
class Revenue extends Controller {
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
                    $kind = 'day';
                    $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                    $currentMonth = $now->format('m') /10*10;
                    $currentYear = $now->format('Y');
                    if(isset($_GET['type'])) {
                        $kind = $_GET['type'];
                        if($_GET['type'] == 'day') {
                            $start = $_GET['date-start'];
                            $end = $_GET['date-end'];
                            $a = ['start'       => $start,
                                'end'           => $end,
                                'currentMonth'  => $currentMonth,
                                'currentYear'   => $currentYear];
                            $data = $this->OrdersModel->queryExecute("SELECT Id, Name, Inbound_price, sold, Revenue,  
                                                                    (Revenue - Inbound_price * sold) as Profit
                                                                    FROM DayRevenue('".$start."','".$end."')
                                                                    ORDER BY Id"
                                                                    );
                            $data1 = $this->OrdersModel->queryExecute("SELECT
                                                                    SUM(sold) AS Sold,
                                                                    SUM(Revenue) AS TotalRevenue, 
                                                                    SUM(Revenue - Inbound_price * sold) AS Profit
                                                                    FROM DayRevenue('".$start."','".$end."');"
                                                                    );
                        }
                        if($_GET['type'] == 'month') {
                            $month = $_GET['month'];
                            $year = $_GET['year'];
                            $a = ['month'       => $month,
                                'year'          => $year,
                                'currentMonth'  => $currentMonth,
                                'currentYear'   => $currentYear];
                            $data = $this->OrdersModel->queryExecute("SELECT Id, Name, Inbound_price, sold, Revenue,  
                                                                    (Revenue - Inbound_price * sold) as Profit
                                                                    FROM MonthRevenue(".$year.",".$month.")
                                                                    ORDER BY Id"
                                                                    );
                            $data1 = $this->OrdersModel->queryExecute("SELECT
                                                                    SUM(sold) AS Sold,
                                                                    SUM(Revenue) AS TotalRevenue, 
                                                                    SUM(Revenue - Inbound_price * sold) AS Profit
                                                                    FROM MonthRevenue(".$year.",".$month.")"
                                                                    );
                        }
                        if($_GET['type'] == 'year') {
                            $year = $_GET['year'];
                            $a = ['year'        => $year,
                                'currentMonth'  => $currentMonth,
                                'currentYear'   => $currentYear];
                            $data = $this->OrdersModel->queryExecute("SELECT Id, Name, Inbound_price, sold, Revenue,  
                                                                    (Revenue - Inbound_price * sold) as Profit
                                                                    FROM YearRevenue(".$year.")
                                                                    ORDER BY Id"
                                                                    );
                            $data1 = $this->OrdersModel->queryExecute("SELECT
                                                                    SUM(sold) AS Sold,
                                                                    SUM(Revenue) AS TotalRevenue, 
                                                                    SUM(Revenue - Inbound_price * sold) AS Profit
                                                                    FROM YearRevenue(".$year.")"
                                                                    );
                        }
                    }
                    else {
                        $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                        $date = $now->format('Y-m-d');
                        $a = ['start' => $date,
                                'end' => $date];
                        $data = $this->OrdersModel->queryExecute("SELECT Id, Name, Inbound_price, sold, SUM(Revenue) as Revenue,  
                                                                SUM((Revenue - Inbound_price * sold)) as Profit
                                                                FROM DayRevenue('".$date."','".$date."')
                                                                GROUP BY Id, Name, Inbound_price, sold"
                                                                );
                        $data1 = $this->OrdersModel->queryExecute("SELECT
                                                                SUM(sold) AS Sold,
                                                                SUM(Revenue) AS TotalRevenue, 
                                                                SUM(Revenue - Inbound_price * sold) AS Profit
                                                                FROM DayRevenue('".$date."','".$date."');"
                                                                );
                    }
                    $this->view('layouts/admin_layout', [
                        'page'      => 'revenue/index',
                        'type'      => 'bcao',
                        'revenue'   => $data,
                        'tongquat'  => $data1[0],
                        'kind'      => $kind,
                        'a'         => $a,
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