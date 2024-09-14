<?php
class Product extends Controller {
    public $ProductModel;
    
    public function __construct() {
        $this->ProductModel = $this->model("ProductModels");
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