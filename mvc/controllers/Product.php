<?php
class Product extends Controller {
    public $ProductModel;
    
    public function __construct() {
        $this->ProductModel = $this->model("ProductModels");
    }

    public function index() {
        $req = new Request();
        $data = $req->getFields();
        $product = $this->ProductModel->findAll(['*'], $data);
        $this->view("layouts/client_layout", [
            "page"      => "product/index",
            "title"     => "Danh sách danh mục",
            "css"       => ["product"],
            "product"   => $product[0],
        ]);
    }
}

?>