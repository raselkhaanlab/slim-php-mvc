<?php
namespace RKO;
class Response {
    public function json($data=NULL){
        header('Content-type: application/json');
        return \json_encode($data);
    }
    public function setHeader(string $key,string $value){
        header($key.": ".$value);
        return $this;
    }
    public function setHeaders(array $headers){
        foreach ($headers as $key => $value) {
            header($key.": ".$value);
        }
        return $this;
    }
    public function status(int $statusCode){
        \http_response_code($statusCode);
        return $this;
    }
    public function cookie($name, $value, $expire, $path, $domain, $secure, $httponly){
        \setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        return $this;
    }
    public function redirect($url){
        header("Location: $url");
        exit;
    }
    public function send($val=''){
        header('Content-type: text/plain');
        return $val;
    }
    public function view($view,$params=[]){
        $viewInfo = \pathinfo($view);
        $extension= !empty($viewInfo['extension'])?$viewInfo['extension']:"php";
        header('Content-type: text/html');
        \extract($params);
        require VIEWS_PATH."/".$viewInfo['filename'].".".$extension;
    }
}