<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Mycrud{

    protected $CI;
  
    public function __construct(){
      $this->CI =& get_instance();
    }
  
    public function Insert($table,$dados)
    {
      if ($dados) {
        
        $res = $this->CI->Crud_model->Insert($table,$dados);
        if ($res) {
          return true;
        }else{
          return false;
        }

      }

    }

    public function Update($table,$id,$dados)
    {
      if ($dados) {
        
        $par = array($id => $dados[$id]);
        $res = $this->CI->Crud_model->Update($table,$dados,$par);
        if ($res) {
          return true;
        }else{
          return false;
        }

      }

    }
    
    public function Remove($table,$id,$dados)
    {
      if ($dados) {
        
        $par = array($id => $dados[$id]);
        $fg_ativo = array('fg_ativo' => 0);
        $res = $this->CI->Crud_model->Update($table,$fg_ativo,$par);
        
        if ($res) {
          return true;
        }else{
          return false;
        }

      }

    }


    public function ReadAll($table)
    {
      //die(var_dump($table));
      $data = $this->CI->Crud_model->ReadAll($table);
      
      if ($data) {
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $json;
      }else{
        return false;
      }
    
    }

    public function ReadPar($table,$id,$dados)
    {
      
      $par = array($id => $dados[$id]);
      $data = $this->CI->Crud_model->ReadPar($table,$par);
      
      if ($data) {
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $json;
      }else{
        return false;
      }
    
    }

    //query
    public function Query($sql)
    {
      
      $data = $this->CI->Crud_model->Query($sql);
      
      if ($data) {
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $json;
      }else{
        return false;
      }
    
    }

}