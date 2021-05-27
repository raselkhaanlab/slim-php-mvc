<?php
namespace RKO;
class Request {
    public final function getMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }
    public final function getRequestUri(){
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri,0,strpos($uri,'?'));
        }
        return $uri;
    }
    public final function getSession($key){
        if(array_key_exists($key,$_SESSION)){
            return $_SESSION[$key];
        }
        return;
    }

    public final function setFlash($name = "", $message = ""){
        if (!empty($name) && !empty($message)){
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            $_SESSION[$name] = $message;
        }
    
    }
    public final function sessionRegenerate(){
        \session_regenerate_id();
    }
    public final function sessionDestroy(){
        \session_destroy();
    }
    public final function setSession($params=[]){
        foreach($params as $key =>$value){
            $_SESSION[$key]= $value;
        }
    }
    public final function deleteSession($key){
        if(array_key_exists($key,$_SESSION)){
             unset($_SESSION[$key]);
        }
    }
    public final function unsetSession($key){
        if(array_key_exists($key,$_SESSION)){
             \session_unset();
        }
    }
    public final function get($name=''){
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
    
    public final function body($name=''){
        $val='';
        $data=$_POST;
        if(strtolower($this->getHeader('content-type'))==='application/json'){
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
    
    public final function file($name=''){
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
    public  function getHeader($name=''){
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
    public final function isXhr(){
        $xRequestWith = $this->getHeader('x-requested-with');
        if (strtolower($xRequestWith) ==="xmlhttprequest"){
            return true;
        }
        return false;
    }
    public final function wantJson(){
        $want = $this->getHeader('accept');
        if (strtolower($want) ==="application/json"){
            return true;
        }
        return false;
    }
    public final function getProtocol(){
        return $_SERVER["REQUEST_SCHEME"];
    }
    public final function getClientIp(){
        return $_SERVER["REMOTE_ADDR"];
    }
    public final function getClientPort(){
        return $_SERVER["REMOTE_PORT"];
    }
    public final function getServerIp(){
        return $_SERVER["SERVER_ADDR"];
    }
    public final function getServerPort(){
        return $_SERVER["SERVER_PORT"];
    }
}