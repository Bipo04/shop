<?php
class Category extends Controller {
    public $CategoryModel;
    public $ProductModel;
    
    public function __construct() {
        $this->CategoryModel    = $this->model("CategoryModels");
        $this->ProductModel     = $this->model("ProductModels");
        $this->Jwtoken          = $this->helper('Jwtoken');
        $this->Authorzation     = $this->helper('Authorzation');
    }

    public function girl($cate = NULL) {
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
        $categories = $this->CategoryModel->findAll(['name'], ['gender' => 'girl']);
        for($i = 0; $i < count($categories); $i++) {
            $categories[$i]['name-slug'] = $req->createSlug($categories[$i]['name']);
        }
        if($cate != NULL) {
            for($i = 0; $i < count($categories); $i++) {
                if($cate == $categories[$i]['name-slug']) {
                    if(isset($_GET['order']) && $_GET['order'] != 'default') {
                        $products = $this->ProductModel->selectJoin($fields, null, 
                            ['gender' => 'girl', 'name' => $categories[$i]['name']], 
                            'Category', ['category_id', 'id'], 'INNER', 'outbound_price', $_GET['order']);
                    }
                    else {
                        $products = $this->ProductModel->selectJoin($fields, null, 
                            ['gender' => 'girl', 'name' => $categories[$i]['name']], 
                            'Category', ['category_id', 'id'], 'INNER');
                    }
                }
            }
        } else {
            if(isset($_GET['order']) && $_GET['order'] != 'default') {
                $products = $this->ProductModel->selectJoin($fields, null, 
                    ['gender' => 'girl'], 
                    'Category', ['category_id', 'id'], 'INNER', 'outbound_price', $_GET['order']);
            }
            else {
                $products = $this->ProductModel->selectJoin($fields, null, 
                    ['gender' => 'girl'], 
                    'Category', ['category_id', 'id'], 'INNER');
            }
        }
        
        if(isset($_GET['order'])) {
            $order = $_GET['order'];
        }
        else {
            $order = 'default';
        }
        
        $this->view("layouts/client_layout", [
            "page" => "category/girl",
            "css" => ["category"],
            "title" => "Danh sách danh mục",
            "products" => $products,
            "categories" => $categories,
            "cate" => $cate,
            "order" => $order,
        ]);
    }

    public function boy($cate = NULL) {
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
        $categories = $this->CategoryModel->findAll(['name'], ['gender' => 'boy']);
        for($i = 0; $i < count($categories); $i++) {
            $categories[$i]['name-slug'] = $req->createSlug($categories[$i]['name']);
        }
        if($cate != NULL) {
            for($i = 0; $i < count($categories); $i++) {
                if($cate == $categories[$i]['name-slug']) {
                    if(isset($_GET['order']) && $_GET['order'] != 'default') {
                        $products = $this->ProductModel->selectJoin($fields, null, 
                            ['gender' => 'boy', 'name' => $categories[$i]['name']], 
                            'Category', ['category_id', 'id'], 'INNER', 'outbound_price', $_GET['order']);
                    }
                    else {
                        $products = $this->ProductModel->selectJoin($fields, null, 
                            ['gender' => 'boy', 'name' => $categories[$i]['name']], 
                            'Category', ['category_id', 'id'], 'INNER');
                    }
                }
            }
        } else {
            if(isset($_GET['order']) && $_GET['order'] != 'default') {
                $products = $this->ProductModel->selectJoin($fields, null, 
                    ['gender' => 'boy'], 
                    'Category', ['category_id', 'id'], 'INNER', 'outbound_price', $_GET['order']);
            }
            else {
                $products = $this->ProductModel->selectJoin($fields, null, 
                    ['gender' => 'boy'], 
                    'Category', ['category_id', 'id'], 'INNER');
            }
        }

        if(isset($_GET['order'])) {
            $order = $_GET['order'];
        }
        else {
            $order = 'default';
        }

        $this->view("layouts/client_layout", [
            "page"          => "category/boy",
            "css"           => ["category"],
            "title"         => "Danh sách danh mục",
            "products"      => $products,
            "categories"    => $categories,
            "cate"          => $cate,
            "order"         => $order,
        ]);
    }

    public function topsale($cate = NULL) {
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
        if($cate != NULL) {
            if(isset($_GET['order']) && $_GET['order'] != 'default') {
                $products = $this->ProductModel->queryExecute(
                    "SELECT p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
                    FROM Product p INNER JOIN Category c ON p.category_id = c.id
                    WHERE p.hot = 1 AND gender = '".$cate."'
                    ORDER BY p.discount ".$_GET['order'].";"
                );
            }
            else {
                $products = $this->ProductModel->queryExecute(
                    "SELECT p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
                    FROM Product p INNER JOIN Category c ON p.category_id = c.id
                    WHERE p.hot = 1 AND gender = '".$cate."';"
                );
            }
        } else {
            if(isset($_GET['order']) && $_GET['order'] != 'default') {
                $products = $this->ProductModel->queryExecute(
                    "SELECT p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
                    FROM Product p INNER JOIN Category c ON p.category_id = c.id
                    WHERE p.hot = 1
                    ORDER BY p.discount ".$_GET['order'].";"
                );
            }
            else {
                $products = $this->ProductModel->queryExecute(
                    'SELECT p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
                    FROM Product p INNER JOIN Category c ON p.category_id = c.id
                    WHERE p.hot = 1'
                );
            }
        }
        
        if(isset($_GET['order'])) {
            $order = $_GET['order'];
        }
        else {
            $order = 'default';
        }
        
        $this->view("layouts/client_layout", [
            "page"      => "category/topsale",
            "css"       => ["category"],
            "title"     => "Danh sách danh mục",
            "products"  => $products,
            "cate"      => $cate,
            "order"     => $order,
        ]);
    }

    public function discount($cate = NULL) {
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
        if($cate != NULL) {
            if(isset($_GET['order']) && $_GET['order'] != 'default') {
                $products = $this->ProductModel->queryExecute(
                    "SELECT p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
                    FROM Product p INNER JOIN Category c ON p.category_id = c.id
                    WHERE p.discount <> 0 AND gender = '".$cate."'
                    ORDER BY p.discount ".$_GET['order'].";"
                );
            }
            else {
                $products = $this->ProductModel->queryExecute(
                    "SELECT p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
                    FROM Product p INNER JOIN Category c ON p.category_id = c.id
                    WHERE p.discount <> 0 AND gender = '".$cate."';"
                );
            }
        } else {
            if(isset($_GET['order']) && $_GET['order'] != 'default') {
                $products = $this->ProductModel->queryExecute(
                    "SELECT p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
                    FROM Product p INNER JOIN Category c ON p.category_id = c.id
                    WHERE p.discount <> 0
                    ORDER BY p.discount ".$_GET['order'].";"
                );
            }
            else {
                $products = $this->ProductModel->queryExecute(
                    'SELECT p.id, p.title, p.outbound_price, p.discount, c.gender, p.thumbnail
                    FROM Product p INNER JOIN Category c ON p.category_id = c.id
                    WHERE p.discount <> 0;'
                );
            }
        }
        
        if(isset($_GET['order'])) {
            $order = $_GET['order'];
        }
        else {
            $order = 'default';
        }
        
        $this->view("layouts/client_layout", [
            "page"      => "category/discount",
            "css"       => ["category"],
            "title"     => "Danh sách danh mục",
            "products"  => $products,
            "cate"      => $cate,
            "order"     => $order,
        ]);
    }
}
?>