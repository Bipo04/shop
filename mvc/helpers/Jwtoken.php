<?php 

class Jwtoken{

    public $key = '';
    function CreateToken($array){
        $header = json_encode(['alg' => 'HS256', 'type' =>'JWT']);
        $Base64UrlTime = str_replace(['+','/','='],['-','_',''],base64_encode($header));
        $this->key = $array['keys'];
        $info = json_encode(['id' => $array['info']['id'],'username' => $array['info']['username'], 'exp' => $array['time']]);
        $Base64Urlinfo = str_replace(['+','/','='],['-','_',''],base64_encode($info));
        $hash = hash_hmac('sha256',$Base64UrlTime.'.'.$Base64Urlinfo,$this->key,false);
        $jwt = $Base64UrlTime.'.'.$Base64Urlinfo.'.'.$hash;
        return $jwt;
    }
    function decodeToken(String $token,$keys){
       $array = explode('.',$token);
       $header = $array[0];
       $userID = $array[1];
       $hash = $array[2];
       if (hash_equals(hash_hmac('sha256',$header.'.'.$userID,$keys,false),$hash)) {
           $info = base64_decode($userID);
           $decodeJS = json_decode($info,true);
            if ($decodeJS['exp'] >= time()) {
                return json_decode($info,true);
            }
            else{
                return 0;
            }
       }
    }
}