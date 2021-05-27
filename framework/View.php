<?php
 namespace RKO;

 class View{
     private $viewBag=[];
     public function __construct($viewBagParams=[]){
        $this->viewBag = $viewBagParams;
     }
     public function getFlash($key){
         $message = "";
         if(isset($_SESSION[$key])){
            $message = $_SESSION[$key];
            unset($_SESSION[$key]); 
         }
         return $message;
     }

     public function viewBag($key){
         if(!empty($this->viewBag[$key])){
             return $this->viewBag[$key];
         }
     }
 }