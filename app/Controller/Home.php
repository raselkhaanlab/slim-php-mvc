<?php
namespace App\Controller;
use App\Model\User;
 class Home extends BaseController{
     private $user;
     public function __construct(){
         parent::__construct();
         $this->user= new User;
     }
     public function index($req , $res){
         return $res->renderView('index',$this->data);
    }
 }