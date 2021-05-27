<?php
namespace RKO;
class Response {
    private $view;
    public final function sendJson($data=NULL){
        header('Content-type: application/json');
        return \json_encode($data);
    }
    public final function setHeader(string $key,string $value){
        header($key.": ".$value);
        return $this;
    }
    public final function setHeaders(array $headers){
        foreach ($headers as $key => $value) {
            header($key.": ".$value);
        }
        return $this;
    }
    public final function setStatus(int $statusCode){
        \http_response_code($statusCode);
        return $this;
    }
    public final function setCookie($name, $value, $expire, $path, $domain, $secure, $httponly){
        \setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        return $this;
    }
    public final function redirect($url){
        header("Location: $url");
        exit;
    }
    public final function sendText($val=''){
        header('Content-type: text/plain');
        return $val;
    }
    public final function renderView($view,$params=[]){
        $viewInfo = \pathinfo($view);
        $extension= !empty($viewInfo['extension'])?$viewInfo['extension']:"php";
        header('Content-type: text/html');
        $this->view = new View($params);
        $View = $this->view;
        require VIEWS_PATH."/".$viewInfo['filename'].".".$extension;
    }
}