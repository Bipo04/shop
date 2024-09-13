<?php
class Product extends Controller {
    public $ProductModel;
    public $CategoryModel;
    public $SupplyModel;

    public function __construct() {
        $this->ProductModel     = $this->model('ProductModels');
        $this->CategoryModel    = $this->model('CategoryModels');
        $this->SupplyModel      = $this->model('SupplyModels');
    }

    public function index() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            $products = $this->ProductModel->getdata();
            $this->view('layouts/admin_layout', [
                'page'      => 'product/index',
                'title'     => 'Danh sách sản phẩm',
                'product'   => $products,
                'type'      => 'qli',
            ]);
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }

    public function add() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            if (isset($_POST['btn'])) {
                $req = new Request();
                unset($_POST['btn']);
                $data = $req->postFields();
                $check =    ['name' => $data['category_name'],
                            'gender' => $data['gender']];
                $a = $this->CategoryModel->findAll(['id'], $check);
                $b = $this->SupplyModel->findAll(['id'], ['name' => $data['supply']]);
                unset($data['category_name']);
                unset($data['gender']);
                unset($data['supply']);
                if(!$a) {
                    $mess['danhmuc'] = 'Danh mục và giới tính không tồn tại!';
                }
                if(!$b) {
                    $mess['danhmuc'] = 'Nhà cung cấp không tồn tại!';
                }
                if($a && $b) {
                    $data['category_id'] = $a[0]['id'];
                    $data['supply_id'] = $b[0]['id'];
                    $file = $_FILES['img']['name'];
                    $slug_folder = $req->createSlug($data['title']);
        
                    $public_dir = 'public/clients/images';
                    $new_folder = $public_dir . '/' . $slug_folder;
                    if (!is_dir($new_folder)) {
                        mkdir($new_folder, 0777, true);
                    }
                    $i = 0;
                    foreach($file as $val) {
                        move_uploaded_file($_FILES['img']['tmp_name'][$i++], $new_folder . '/' . $val);
                        $a = $slug_folder. '/' .$val;
                        $a = explode('.', $a);
    
                        $thumb[] = $a[0];
                    }
                    $data['thumbnail'] = implode(',', $thumb);
                    $this->ProductModel->add($data);
                    header('location: http://localhost:8088/web/admin/product');
                } else {
                    $this->view('layouts/admin_layout', [
                        'page'  => 'product/add',
                        'title' => 'Thêm sản phẩm',
                        'type'  => 'qli',
                        'mess'  => $mess
                    ]);
                }
            }
    
            $this->view('layouts/admin_layout', [
                'page'  => 'product/add',
                'title' => 'Thêm sản phẩm',
                'type'  => 'qli',
            ]);
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }

    public function delete() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $req = new Request();
                $data = $req->postFields();
                $id = $data['id'];
                $this->ProductModel->deleteById($id);
            }
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }

    public function update() {
        if(isset($_COOKIE['userId']) && $_SESSION[$_COOKIE['userId']]['role_id'] == '1') {
            if(isset($_GET['id'])) {
                $query = 'SELECT * FROM getProduct('.$_GET['id'].')';
                $product = $this->ProductModel->queryExecute($query);
                $this->view('layouts/admin_layout', [
                    'page'      => 'product/update',
                    'title'     => 'Cập nhật sản phẩm',
                    'type'      => 'qli',
                    'product'   => $product[0],
                    'id'        => $_GET['id']
                ]);
            }
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $req = new Request();
                unset($_POST['category_name']);
                unset($_POST['supply']);
                unset($_POST['gender']);
                unset($_POST['btn']);
                $id = $_POST['id'];
                unset($_POST['id']);
                if($_FILES['img']['name'][0] != '') {
                    $file = $_FILES['img']['name'];
                    $slug_folder = $req->createSlug($_POST['title']);
                    $slug_folder = str_replace('-', '_', $slug_folder);
                    $public_dir = 'public/clients/images';
                    $new_folder = $public_dir . '/' . $slug_folder;
                    if (!is_dir($new_folder)) {
                        mkdir($new_folder, 0777, true);
                    } else {
                        $req->deleteDirectory($new_folder);
                        mkdir($new_folder, 0777, true);
                    }
                    $i = 0;
                    foreach($file as $val) {
                        move_uploaded_file($_FILES['img']['tmp_name'][$i++], $new_folder . '/' . $val);
                        $a = $slug_folder. '/' .$val;
                        $a = explode('.', $a);
    
                        $thumb[] = $a[0];
                    }
                    $_POST['thumbnail'] = implode(',', $thumb);
                }
                $this->ProductModel->update($_POST, ['id' => $id]);
                echo "<script>window.location.href='http://localhost:8088/web/admin/product'</script>";
            }
        } else {
            require_once './mvc/errors/forbidden.php';
        }
    }
}
?>