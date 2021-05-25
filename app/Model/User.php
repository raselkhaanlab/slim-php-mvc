<?php 
namespace App\Model;
class User extends BaseModel{
    protected $table='users';
    protected $primaryKey='id';
    public function getUsers(){
        return $this->getAll();
    }
}