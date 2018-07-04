<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contato extends CI_Controller {

	public function index()
	{

		//Filtros dos pedidos novos
		if($this->input->get("visualizado") == 1){
			$data['optionVisualizado'] = 1;
			$visualizado = '1=1';
		}else if ($this->input->get("visualizado") == 2) {
			$data['optionVisualizado'] = 2;
			$visualizado = 'visualizado = 1';
		}else{
			$data['optionVisualizado'] = 0;
			$visualizado = 'visualizado = 0';
		}

		//Filtros da data do contatos
		if($this->input->get("data_de")){
			$data['optionDataDe'] = $this->input->get('data_de');
			$dataDe = "DATE(data_contato) >= '".$this->input->get('data_de')."'";
		}else{
			$data['optionDataDe'] = '';
			$dataDe = "1=1";
		}
		//Filtros da data do contatos
		if($this->input->get("data_ate")){
			$data['optionDataAte'] = $this->input->get('data_ate');
			$dataAte = "DATE(data_contato) <= '".$this->input->get('data_ate')."'";
		}else{
			$data['optionDataAte'] = '';
			$dataAte = "1=1";
		}

		//sql busca
		$sql = 'SELECT id_contato, DATE_FORMAT(data_contato, "%d-%m") as data_contato, DATE_FORMAT(data_contato, "%h:%i") as hora_contato, nome_contato, email, telefone, visualizado FROM contato where fg_ativo = 1 and '.$visualizado.' and '.$dataDe.' and '.$dataAte.' order by data_contato desc';

		$data['contatos'] = $this->Crud_model->Query($sql);

		$sql = "SELECT count(*) as qtd FROM contato where $visualizado";
		$data['qtd'] = $this->Crud_model->Query($sql);

		$menu['id_page'] = 5;
		$header['title'] = 'Dash | Pedidos';
		$header['page'] = '1';

		$this->load->view('dashboard/template/commons/header',$header);
		$this->load->view('dashboard/template/commons/menu',$menu);
		$this->load->view('dashboard/pedido/home',$data);
		$this->load->view('dashboard/template/commons/footer');
	}

	public function NotificacaoContato()
	{
		$sql = "SELECT COUNT(*) as qtd FROM contato WHERE visualizado = 0 and fg_ativo = 1";
		$data = $this->Crud_model->Query($sql);
		if ($data) {
			$data = $data[0];
			$json = json_encode($data,JSON_UNESCAPED_UNICODE);
			echo $json;
		}else{
			$this->output->set_status_header('500');
		}
	}

	public function VisualizacaoContato()
	{
		if($this->input->get('id')){
			$idContato = (int) $this->input->get('id');
			$par = array('id_contato' => $idContato);
			
			$contato = $this->Crud_model->Read('contato',$par);
			
			if ($contato) {
				
				if ($contato->visualizado == 1) {
					$datavisualizado = 0;
				}else{
					$datavisualizado = 1;
				}

				$dataModel = array('visualizado' => $datavisualizado);
				$data = $this->Crud_model->Update('contato',$dataModel,$par);

				if ($data) {
					$this->output->set_status_header('200');
					return;
				}
			}
		}
		$this->output->set_status_header('500');
	}

}