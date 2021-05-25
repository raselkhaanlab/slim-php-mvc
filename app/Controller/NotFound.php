<?php
namespace App\Controller;
class NotFound extends BaseController{
    public function notFound($req,$res){
       $this->data["title"]="NOT FOUND";
      return $res->view("404",$this->data);
    }
    public function methodNotFound($req,$res){
      $this->data["title"]="METHOD NOT FOUND";
       return $res->view("405",$this->data);
    }
}