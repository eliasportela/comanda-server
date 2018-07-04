<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

class Safra extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library(['upload','image_lib']);
	}

	public function GetPrevisao(){
		
		$propriedade = $this->uri->segment(4);

		if ($propriedade > 0):
			
			$res = $this->Crud_model->ReadPar('safra_previsao',array('id_propriedade' => $propriedade));
			
			if ($res):
				$json = json_encode($res,JSON_UNESCAPED_UNICODE);
				echo $json;
				$this->output->set_status_header('200');
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}

	public function GetFechamento(){
		
		$propriedade = $this->uri->segment(4);

		if ($propriedade > 0):
			
			$res = $this->Crud_model->ReadPar('safra_fechamento',array('id_propriedade' => $propriedade));
			
			if ($res):
				$json = json_encode($res,JSON_UNESCAPED_UNICODE);
				echo $json;
				$this->output->set_status_header('200');
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}


	public function GetCafe(){
		
		$propriedade = $this->uri->segment(4);

		if ($propriedade > 0):
			
			$res = $this->Crud_model->ReadPar('safra_cafe',array('id_propriedade' => $propriedade));
			
			if ($res):
				$json = json_encode($res,JSON_UNESCAPED_UNICODE);
				echo $json;
				$this->output->set_status_header('200');
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}


	//Delete previsao
	public function DeletePrevisao(){
		$nivel_user = 2;
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):
			$dataId = (int)$this->uri->segment(5);
			if ($dataId > 0):
				$res = $this->Crud_model->Delete('safra_previsao',array('id_safra_previsao' => $dataId));
				if($res):
					$this->output->set_status_header('200');
					return;
				endif;
			endif;
		else:
			$this->output->set_status_header('400');
		endif;
	}

	//Delete fechamento
	public function DeleteFechamento(){
		$nivel_user = 2;
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):
			$dataId = (int)$this->uri->segment(5);
			if ($dataId > 0):
				$res = $this->Crud_model->Delete('safra_fechamento',array('id_safra_fechamento' => $dataId));
				if($res):
					$this->output->set_status_header('200');
					return;
				endif;
			endif;
		else:
			$this->output->set_status_header('400');
		endif;
	}

	//Delete safra cafe
	public function DeleteCafe(){
		$nivel_user = 2;
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):
			$dataId = (int)$this->uri->segment(5);
			if ($dataId > 0):
				$res = $this->Crud_model->Delete('safra_cafe',array('id_safra_cafe' => $dataId));
				if($res):
					$this->output->set_status_header('200');
					return;
				endif;
			endif;
		else:
			$this->output->set_status_header('400');
		endif;
	}

}