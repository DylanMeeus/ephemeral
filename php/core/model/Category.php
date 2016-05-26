<?php


if(!defined("SERVLET"))
    die("You may not view this page");


// ORM for categories

class Category
{

private $name;
private $id;

public function __construct()
{

}

// following the C++ style here, to make it a bit more terse.
public function getName(){ return $this->name; }
public function setName($name){ $this->name=$name; }

public function getId(){ return $this->id; }
public function setId($id){$this->id = $id;}

}
