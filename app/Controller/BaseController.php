<?php
namespace App\Controller;
use RKO\Controller;
abstract class BaseController extends Controller{
    public function __construct(){
        parent::__construct();
        $this->data['title']="YOUR GLOBAL APP TITLE";
    }
}