<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Query_model extends CI_Model
{

  function _construct()
  {
    parent::_construct();
  }
  
  // funcao with sql
  public function Query($sql){
    $query = $this->db->query($sql);
    $result = $query->result();
    if($result)
      return $result;
    else {
      return false;
    }
  }
  // end function

}