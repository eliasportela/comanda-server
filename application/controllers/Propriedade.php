<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

class Propriedade extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library(['upload','image_lib']);
	}

	//Select com Paginacao
	public function Get(){
		
		$produtor = $this->uri->segment(4);

		if ($produtor > 0):
			$sql = "SELECT * FROM propriedade p
			INNER JOIN cidade c ON (c.id_cidade = p.id_cidade) 
			INNER JOIN estado e ON (e.id_estado = c.id_estado)
			WHERE p.id_produtor = $produtor and p.fg_ativo = 1";

			$res = $this->Crud_model->Query($sql);
			
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

	public function GetId(){
		
		$propriedade = $this->uri->segment(5);

		if ($propriedade > 0):
			$sql = "SELECT * FROM propriedade p
			INNER JOIN cidade c ON (c.id_cidade = p.id_cidade) 
			INNER JOIN estado e ON (e.id_estado = c.id_estado)
			WHERE p.id_propriedade = $propriedade and p.fg_ativo = 1";

			$res = $this->Crud_model->Query($sql);
			
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

	//Inserindo registros
	public function Register() {

		$nivel_user = 1;
		$foto_propriedade = null;
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):

			$dataRegister = $this->input->post();

			if ($dataRegister['nome_propriedade'] != NULL):

				$produtor = trim($dataRegister['id_produtor']);

			//Config ambiente de upload
				$path = './uploads/docs/'.$produtor.'/propriedades/';
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size'] = '5000';
				$config['encrypt_name']  = TRUE;
				$this->upload->initialize($config);

			//verifica se o path é válido, se não for cria o diretório
				if (!is_dir($path)) {
					mkdir($path, 0777, $recursive = true);
				}

				$logimg = null;

			//upload da imagem
				if (!$this->upload->do_upload('propriedade_file')) {
					$logimg = $this->upload->display_errors(null,null);
				} else {
					$dadosImagem = $this->upload->data();
					$foto_propriedade = $dadosImagem['file_name'];
				}

				$dataModel = array(
					'id_produtor' => $produtor,
					'nome_propriedade' => trim($dataRegister['nome_propriedade']),
					'tipo_propriedade' => trim($dataRegister['tipo_propriedade']),
					'cnpj' => trim($dataRegister['cnpj']),
					'contato' => trim($dataRegister['contato']),
					'telefone' => trim($dataRegister['telefone']),
					'foto_propriedade' => $foto_propriedade,
					'latitude' => trim($dataRegister['latitude']),
					'longitude' => trim($dataRegister['longitude']),
					'altitude' => trim($dataRegister['altitude']),
					'area_total' => trim($dataRegister['area_total']),
					'area_plantada' => trim($dataRegister['area_plantada']),
					'area_irrigada' => trim($dataRegister['area_irrigada']),
					'arrendada' => trim($dataRegister['arrendada']),
					'prod_media_cafe' => trim($dataRegister['prod_media_cafe']),
					'p_eletricidade' => trim($dataRegister['p_eletricidade']),
					'p_familiar' => trim($dataRegister['p_familiar']),
					'p_analise_solo_folha' => trim($dataRegister['p_analise_solo_folha']),
					'p_adubacao_organica' => trim($dataRegister['p_adubacao_organica']),
					'p_fertilizacao' => trim($dataRegister['p_fertilizacao']),
					'p_analise_camada_expessura' => trim($dataRegister['p_analise_camada_expessura']),
					'p_sistema_tulhas' => trim($dataRegister['p_sistema_tulhas']),
					'p_protecao_chuva' => trim($dataRegister['p_protecao_chuva']),
					'tipo_terreiro' => trim($dataRegister['tipo_terreiro']),
					'tipo_processamento' => trim($dataRegister['tipo_processamento']),
					'processamento_via_umido' => trim($dataRegister['processamento_via_umido']),
					'logradouro' => trim($dataRegister['logradouro']),
					'numero_km' => trim($dataRegister['numero_km']),
					'id_cidade' => trim($dataRegister['selectPropCidades']),
					'obs' => trim($dataRegister['obs']));

				$propriedade = $this->Crud_model->InsertID('propriedade',$dataModel);

				if($propriedade):

				//Safras Previsao
					if(isset($dataRegister['safraPreQtd'])):

						for ($i=0; $i < count($dataRegister['safraPreQtd']); $i++) {

							$safraModel = array('safra_ano_inicio' => $dataRegister['safraPreAnoInicio'][$i],
								'safra_ano_fim' => $dataRegister['safraPreAnoFim'][$i],
								'qtd_safra' => $dataRegister['safraPreQtd'][$i],
								'id_propriedade' => $propriedade);

							$res = $this->Crud_model->Insert('safra_previsao',$safraModel);
						}

					endif;

				//Safras Fechamento
					if(isset($dataRegister['safraFeQtd'])):

						for ($i=0; $i < count($dataRegister['safraFeQtd']); $i++) {

							$safraModel = array('safra_ano_inicio' => $dataRegister['safraFeAnoInicio'][$i],
								'safra_ano_fim' => $dataRegister['safraFeAnoFim'][$i],
								'qtd_safra' => $dataRegister['safraFeQtd'][$i],
								'id_propriedade' => $propriedade);

							$res = $this->Crud_model->Insert('safra_fechamento',$safraModel);
						}

					endif;

				//Safras Cafés
					if(isset($dataRegister['safraCafeQtd'])):

						for ($i=0; $i < count($dataRegister['safraCafeQtd']); $i++) {

							$safraModel = array(
								'safra_ano_inicio' => $dataRegister['safraCafeAnoInicio'][$i],
								'safra_ano_fim' => $dataRegister['safraCafeAnoFim'][$i],
								'variedade' => $dataRegister['safraCafeVariedade'][$i],
								'area_plantada' => $dataRegister['safraCafeArea'][$i],
								'qtd_safra' => $dataRegister['safraCafeQtd'][$i],
								'id_propriedade' => $propriedade);

							$res = $this->Crud_model->Insert('safra_cafe',$safraModel);
						}

					endif;

					$this->output->set_status_header('200');

				endif;

			endif;

		else:
			$this->output->set_status_header('404');
		endif;
	}

	//Inserindo registros
	public function Edit() {

		$nivel_user = 1;
		$foto_propriedade = null;
		
		$dataRegister = $this->input->post();
		$propriedade = (int)$this->uri->segment(4);
		$produtor = trim($dataRegister['id_produtor']);

		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):

			$path = './uploads/docs/'.$produtor.'/propriedades/';
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size'] = '5000';
			$config['encrypt_name']  = TRUE;
			$this->upload->initialize($config);

			if (!is_dir($path)) {
				mkdir($path, 0777, $recursive = true);
			}


			if ($dataRegister['nome_propriedade'] != NULL):

				if ($this->upload->do_upload('propriedade_file')) {
					$dadosImagem = $this->upload->data();
					$foto_propriedade = $dadosImagem['file_name'];
				}else{
					$data = array('error' => $this->upload->display_errors());
				}

				$dataModel = array(
					'nome_propriedade' => trim($dataRegister['nome_propriedade']),
					'tipo_propriedade' => trim($dataRegister['tipo_propriedade']),
					'cnpj' => trim($dataRegister['cnpj']),
					'contato' => trim($dataRegister['contato']),
					'telefone' => trim($dataRegister['telefone']),
					'latitude' => trim($dataRegister['latitude']),
					'longitude' => trim($dataRegister['longitude']),
					'altitude' => trim($dataRegister['altitude']),
					'area_total' => trim($dataRegister['area_total']),
					'area_plantada' => trim($dataRegister['area_plantada']),
					'area_irrigada' => trim($dataRegister['area_irrigada']),
					'arrendada' => trim($dataRegister['arrendada']),
					'prod_media_cafe' => trim($dataRegister['prod_media_cafe']),
					'p_eletricidade' => trim($dataRegister['p_eletricidade']),
					'p_familiar' => trim($dataRegister['p_familiar']),
					'p_analise_solo_folha' => trim($dataRegister['p_analise_solo_folha']),
					'p_adubacao_organica' => trim($dataRegister['p_adubacao_organica']),
					'p_fertilizacao' => trim($dataRegister['p_fertilizacao']),
					'p_analise_camada_expessura' => trim($dataRegister['p_analise_camada_expessura']),
					'p_sistema_tulhas' => trim($dataRegister['p_sistema_tulhas']),
					'p_protecao_chuva' => trim($dataRegister['p_protecao_chuva']),
					'tipo_terreiro' => trim($dataRegister['tipo_terreiro']),
					'tipo_processamento' => trim($dataRegister['tipo_processamento']),
					'logradouro' => trim($dataRegister['logradouro']),
					'numero_km' => trim($dataRegister['numero_km']),
					'id_cidade' => trim($dataRegister['selectPropCidades']),
					'obs' => trim($dataRegister['obs']));


				if (isset($dataRegister['processamento_via_umido'])) {
					$dataModel = array_merge($dataModel,array('processamento_via_umido' => trim($dataRegister['processamento_via_umido'])));
				}

				if ($foto_propriedade) {

					$sql = "SELECT foto_propriedade FROM propriedade WHERE id_propriedade = $propriedade";
					$upload = $this->Crud_model->Query($sql);
					$dataModel = array_merge($dataModel,array('foto_propriedade' => $foto_propriedade));
					if ($upload) {
						unlink($path.$upload[0]->foto_propriedade);
					}
				}

				$res = $this->Crud_model->Update('propriedade',$dataModel,array('id_propriedade' => $propriedade));

				if($res):

				//Safras Previsao
					if(isset($dataRegister['safraPreQtd'])):

						for ($i=0; $i < count($dataRegister['safraPreQtd']); $i++) {

							$safraModel = array('safra_ano_inicio' => $dataRegister['safraPreAnoInicio'][$i],
								'safra_ano_fim' => $dataRegister['safraPreAnoFim'][$i],
								'qtd_safra' => $dataRegister['safraPreQtd'][$i],
								'id_propriedade' => $propriedade);

							$res = $this->Crud_model->Insert('safra_previsao',$safraModel);
						}

					endif;

				//Safras Fechamento
					if(isset($dataRegister['safraFeQtd'])):

						for ($i=0; $i < count($dataRegister['safraFeQtd']); $i++) {

							$safraModel = array('safra_ano_inicio' => $dataRegister['safraFeAnoInicio'][$i],
								'safra_ano_fim' => $dataRegister['safraFeAnoFim'][$i],
								'qtd_safra' => $dataRegister['safraFeQtd'][$i],
								'id_propriedade' => $propriedade);

							$res = $this->Crud_model->Insert('safra_fechamento',$safraModel);
						}

					endif;

				//Safras Cafés
					if(isset($dataRegister['safraCafeQtd'])):

						for ($i=0; $i < count($dataRegister['safraCafeQtd']); $i++) {

							$safraModel = array(
								'safra_ano_inicio' => $dataRegister['safraCafeAnoInicio'][$i],
								'safra_ano_fim' => $dataRegister['safraCafeAnoFim'][$i],
								'variedade' => $dataRegister['safraCafeVariedade'][$i],
								'area_plantada' => $dataRegister['safraCafeArea'][$i],
								'qtd_safra' => $dataRegister['safraCafeQtd'][$i],
								'id_propriedade' => $propriedade);

							$res = $this->Crud_model->Insert('safra_cafe',$safraModel);
						}

					endif;

					$this->output->set_status_header('200');

				endif;

			endif;

		else:
			$this->output->set_status_header('404');
		endif;
		
	}

	//Delete registro
	public function Remove(){
		$nivel_user = 2;
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):
			$dataId = (int)$this->uri->segment(5);
			if ($dataId > 0):
				//remover img
				$sql = "SELECT id_produtor, foto_propriedade FROM propriedade WHERE id_propriedade = $dataId";
				$query = $this->Crud_model->Query($sql);
				if ($query) {
					$path = './uploads/docs/'.$query[0]->id_produtor.'/propriedades/';
					unlink($path.$query[0]->foto_propriedade);
				}
				
				$res = $this->Crud_model->Delete('safra_previsao',array('id_propriedade' => $dataId));
				$res = $this->Crud_model->Delete('safra_fechamento',array('id_propriedade' => $dataId));
				$res = $this->Crud_model->Delete('safra_cafe',array('id_propriedade' => $dataId));
				$res = $this->Crud_model->Delete('propriedade',array('id_propriedade' => $dataId));
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