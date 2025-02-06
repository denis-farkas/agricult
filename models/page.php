<?php

class Page {

private $db;

public function __construct (){
  $this->db=new Database;
}

public function listUser(){
  $this->db->query('SELECT * from user');
  $listuser=$this->db->resultSet();
  return $listuser;
}

}