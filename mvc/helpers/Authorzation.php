<?php 
class Authorzation extends Controller {
    public $UserModel;
    function __construct(){
        $this->UserModel       =  $this->model('UserModels');
    }

    function checkAuth($array){
        $id = $array['id'];
        $username = $array['username'];
        $checkUS = $this->UserModel->findALL(['*'],['id' => $id,'username' => $username, 'role_id' => '1']);
        if ($checkUS != NULL && count($checkUS) > 0) {
            return true;
        }
        else{
            return false;
        }
    }
     function checkAuthUser($array){
        $id = $array['id'];
        $username = $array['username'];
        $checkUS = $this->UserModel->findALL(['*'],['id' => $id,'username' => $username]);
        if ($checkUS != NULL && count($checkUS) > 0) {
            return true;
        }
        else{
            return false;
        }
    }
}