<?php
class Topsale extends Controller {
    public $OrdersModel;
    public $ProductModel;

    public function __construct() {
        $this->OrdersModel      = $this->model('OrdersModels');
        $this->ProductModel     = $this->model('ProductModels');
    }

    public function index() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            $kq = $this->OrdersModel->queryExecute(
                "SELECT * FROM v_TopSelling"
            );
            foreach($kq as $item) {
                $idArray[] = $item['Id'];
            }
            $id = array_values($idArray);
            $id = implode(',', $id);
            $kq2 = $this->OrdersModel->queryExecute2(
                'UPDATE Product
                SET hot=0

                UPDATE Product
                SET hot=1
                WHERE id IN ('.$id.')'
            );
            $this->view('layouts/admin_layout', [
                'page'  => 'topsale/index',
                'title' => 'Top sản phẩm bán chạy',
                'type'  => 'bcao',
                'kq'    => $kq,
            ]);
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }
}
?>