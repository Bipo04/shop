<?php
class Topsale extends Controller {
    public $OrdersModel;
    public $ProductModel;

    public function __construct() {
        $this->OrdersModel      = $this->model('OrdersModels');
        $this->ProductModel     = $this->model('ProductModels');
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
                    $kq = $this->OrdersModel->queryExecute("CALL GetTopSellingProducts;");
                    foreach($kq as $item) {
                        $idArray[] = $item['id'];
                    }
                    $id = array_values($idArray);
                    $id = implode(',', $id);
                    $this->OrdersModel->queryExecute2(
                        'UPDATE Product
                        SET hot=0'
                    );
                    $this->OrdersModel->queryExecute2(
                        'UPDATE Product
                        SET hot=1
                        WHERE id IN ('.$id.')'
                    );
                    $this->view('layouts/admin_layout', [
                        'page'  => 'topsale/index',
                        'title' => 'Top sản phẩm bán chạy',
                        'type'  => 'bcao',
                        'kq'    => $kq,
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