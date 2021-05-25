<?php
namespace RKO;
class Request {
    public function method(){
        return $_SERVER['REQUEST_METHOD'];
    }
    public function requestUri(){
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri,0,strpos($uri,'?'));
        }
        return $uri;
    }
    public function session($key){
        if(array_key_exists($key,$_SESSION)){
            return $_SESSION[$key];
        }
        return;
    }

    public function flash($name = "", $message = ""){
        if (!empty($name) && !empty($message)){
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            $_SESSION[$name] = $message;
        }
    
    }
    public function flashArray($name="", array $message=[]){
        if (!empty($name) && count($message)){
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            $_SESSION[$name] = $message;
        }
    }
    public function sessionRegenerate(){
        \session_regenerate_id();
    }
    public function sessionDestroy(){
        \session_destroy();
    }
    public function sessionSet($params=[]){
        foreach($params as $key =>$value){
            $_SESSION[$key]= $value;
        }
    }
    public function sessionDelete($key){
        if(array_key_exists($key,$_SESSION)){
             unset($_SESSION[$key]);
        }
    }
    public function sessionUnset($key){
        if(array_key_exists($key,$_SESSION)){
             \session_unset();
        }
    }
    public function query($name=''){
        $val='';
        if(!isset($name) || empty($name)){
            $val= $_GET;
        }
        elseif(array_key_exists($name,$_GET)){
            $val = $_GET[$name];
        }
        else{
            $val= $val;
        }
        return $val;
    }
    
    public function body($name=''){
        $val='';
        $data=$_POST;
        if(strtolower($this->header('content-type'))==='application/json'){
            $data = json_decode(file_get_contents('php://input'), true);
        }
        if(!isset($name) || empty($name)){
            $val= $data;
        }
        elseif(array_key_exists($name,$data)){
            $val = $data[$name];
        }
        else{
           $val = $val;
        }
        return $val;
    }
    
    public function file($name=''){
        $val='';
        
        if(!isset($name) || empty($name)){
            $val= $_FILES;
        }
        elseif(array_key_exists($name,$_FILES)){
            $val = $_FILES[$name];
        }
        else{
            $val = $val;
        }
        return $val;
    }
    public  function header($name=''){
        $val = '';
        $headers = getallheaders();
        $headers= array_change_key_case($headers,CASE_LOWER);
        if(!isset($name) || empty($name)){
            $val= $headers;
        }
        elseif(array_key_exists($name,$headers)){
            $val = $headers[$name];
        }
        
        return $val;
    }
    public function xhr(){
        $xRequestWith = $this->header('x-requested-with');
        if (strtolower($xRequestWith) ==="xmlhttprequest"){
            return true;
        }
        return false;
    }
    public function wantJson(){
        $want = $this->header('accept');
        if (strtolower($want) ==="application/json"){
            return true;
        }
        return false;
    }
    public function protocol(){
        return $_SERVER["REQUEST_SCHEME"];
    }
    public function clientIp(){
        return $_SERVER["REMOTE_ADDR"];
    }
    public function clientPort(){
        return $_SERVER["REMOTE_PORT"];
    }
    public function serverIp(){
        return $_SERVER["SERVER_ADDR"];
    }
    public function serverPort(){
        return $_SERVER["SERVER_PORT"];
    }
}