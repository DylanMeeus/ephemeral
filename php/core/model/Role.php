<?php

// ORM class for roles


class Role
{

    private $id, $role;
    
    public function setId($id){$this->id=$id;}
    public function getId(){return $this->id;}
    public function setRole($role){$this->role=$role;}
    public function getRole(){return $this->role;}

}




?>
