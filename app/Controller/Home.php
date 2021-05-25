<?php
namespace App\Controller;
 class Home extends BaseController{
     private $user;
     public function __construct(){
         parent::__construct();
     }
     public function index($req , $res){
         return $res->view('index',$this->data);
    }
 }