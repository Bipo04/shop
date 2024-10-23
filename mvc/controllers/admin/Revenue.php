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
                            $data = $this->OrdersModel->queryExecute("CALL DayRevenue('".$start."','".$end."')");
                            $sold = 0; $totalRevenue = 0; $profit = 0;
                            foreach($data as $item) {
                                $sold += $item['sold'];
                                $totalRevenue += $item['Revenue'];
                                $profit += $item['Profit'];
                            }
                            $data1 = ['Sold' => $sold,
                                      'TotalRevenue' => $totalRevenue,
                                      'Profit' => $profit];
                        }
                        if($_GET['type'] == 'month') {
                            $month = $_GET['month'];
                            $year = $_GET['year'];
                            $a = ['month'       => $month,
                                'year'          => $year,
                                'currentMonth'  => $currentMonth,
                                'currentYear'   => $currentYear];
                            $data = $this->OrdersModel->queryExecute("CALL MonthRevenue(".$year.",".$month.")");
                            $sold = 0; $totalRevenue = 0; $profit = 0;
                            foreach($data as $item) {
                                $sold += $item['sold'];
                                $totalRevenue += $item['Revenue'];
                                $profit += $item['Profit'];
                            }
                            $data1 = ['Sold' => $sold,
                                      'TotalRevenue' => $totalRevenue,
                                      'Profit' => $profit];

                        }
                        if($_GET['type'] == 'year') {
                            $year = $_GET['year'];
                            $a = ['year'        => $year,
                                'currentMonth'  => $currentMonth,
                                'currentYear'   => $currentYear];
                            $data = $this->OrdersModel->queryExecute("CALL YearRevenue(".$year.")");
                            $sold = 0; $totalRevenue = 0; $profit = 0;
                            foreach($data as $item) {
                                $sold += $item['sold'];
                                $totalRevenue += $item['Revenue'];
                                $profit += $item['Profit'];
                            }
                            $data1 = ['Sold' => $sold,
                                      'TotalRevenue' => $totalRevenue,
                                      'Profit' => $profit];
                        }
                    }
                    else {
                        $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                        $date = $now->format('Y-m-d');
                        $a = ['start' => $date,
                                'end' => $date];
                        $data = $this->OrdersModel->queryExecute("CALL DayRevenue('".$date."','".$date."')");
                        $sold = 0; $totalRevenue = 0; $profit = 0;
                        foreach($data as $item) {
                            $sold += $item['sold'];
                            $totalRevenue += $item['Revenue'];
                            $profit += $item['Profit'];
                        }
                        $data1 = ['Sold' => $sold,
                                    'TotalRevenue' => $totalRevenue,
                                    'Profit' => $profit];
                    }
                    $this->view('layouts/admin_layout', [
                        'page'      => 'revenue/index',
                        'type'      => 'bcao',
                        'revenue'   => $data,
                        'tongquat'  => $data1,
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