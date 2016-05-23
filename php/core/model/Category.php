
<?php




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
public function setName($param){ $this->name=$param; }

public function getId(){ return $this->id; }
public function setId($param){$this->id = $id;}

}
