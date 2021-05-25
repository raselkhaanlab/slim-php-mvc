<?php
namespace RKO;
class Controller{
    public $data=[];
    public function __construct(){
        $this->data['title']=APP_CONFIG['app_title'];
    }
}