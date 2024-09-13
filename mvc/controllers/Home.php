<?php
class Home extends Controller {
    public $CategoryModel;
    public $ProductModel;

    public function __construct() {
        $this->CategoryModel    = $this->model("CategoryModels");
        $this->ProductModel     = $this->model("ProductModels");
    }
    
    public function index() {
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