<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Util extends CI_Controller {

	public function GetCidades()
	{
		if($this->input->get('id')){
			$idEstado = (int) $this->input->get('id');
			$par = array('id_estado' => $idEstado);
			
			$cidades = $this->Crud_model->ReadPar('cidade',$par);
			
			if ($cidades) {
				$json = json_encode($cidades,JSON_UNESCAPED_UNICODE);
				echo $json;
				return;
			}
		}
		$this->output->set_status_header('500');
	}

}