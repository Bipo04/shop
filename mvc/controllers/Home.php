<?php
class Home extends Controller {
    public $CategoryModel;
    public $ProductModel;

    public function __construct() {
        $this->CategoryModel    = $this->model("CategoryModels");
        $this->ProductModel     = $this->model("ProductModels");
        $this->Jwtoken          = $this->helper('Jwtoken');
        $this->Authorzation     = $this->helper('Authorzation');
    }
    
    public function index() {
        if(isset($_COOKIE['token'])) {
            $verify = $this->Jwtoken->decodeToken($_COOKIE['token'],KEYS);
            if ($verify != NULL && $verify != 0) {
                unset($verify['exp']);
                $authUser = $this->Authorzation->checkAuthUser($verify);
                if($authUser != true) {
                    setcookie('token', '', time() - 3600, "/");
                    unset($_SESSION['user']);
                    header('location: '.base_url.'/auth/login?expired=true');
                }
            }
            else {
                setcookie('token', '', time() - 3600, "/");
                unset($_SESSION['user']);
                header('location: '.base_url.'/auth/login?expired=true');
            }
        }
        $fields = [
            'Product.id as id',
            'category_id',
            'title',
            'inbound_price',
            'outbound_price',
            'discount',
            'supply_id',
            'thumbnail',
            'description',
            'quantity',
            'sold',
            'hot'
        ];
        $dataGirl = $this->ProductModel->selectJoin($fields, '10', ['gender' => 'girl'], 'Category', ['category_id', 'id'], 'INNER');
        $dataBoy = $this->ProductModel->selectJoin($fields, '10', ['gender' => 'boy'], 'Category', ['category_id', 'id'], 'INNER');
        $dataDiscount = $this->ProductModel->queryExecute(
            'SELECT TOP 10 p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
            FROM Product p INNER JOIN Category c ON p.category_id = c.id
            WHERE p.discount <> 0;'
        );
        $dataTopSale = $this->ProductModel->queryExecute(
            'SELECT TOP 10 * FROM Product 
            WHERE hot = 1'
        );
        $this->view("layouts/client_layout", [
            "page"          => "home/index",
            "title"         => "Trang chủ",
            "css"           => ["main"],
            "data"          => ["dataGirl"      => $dataGirl,
                                "dataBoy"       => $dataBoy,
                                "dataDiscount"  => $dataDiscount,
                                "dataTopSale"   => $dataTopSale],
            "data_title"    => ["Nữ", "Nam","Giảm giá","Top sale"]
        ]);
    }
}
?>