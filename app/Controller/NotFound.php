<?php
namespace App\Controller;
class NotFound extends BaseController{
    public function notFound($req,$res){
      return $res->renderView("404");
    }
    public function methodNotFound($req,$res){
       return $res->renderView("405");
    }
}