<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

class Produtor extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library(['form_validation','upload','image_lib']);
	} 
	
	//Tela Produtor
	public function index(){

		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)) {

			$data["t_pessoas"] = $this->Crud_model->ReadAll("tipo_pessoa");
			$header['title'] = "Dash | Produtores";
			$menu['id_page'] = 3;

			$this->load->view('dashboard/template/commons/header',$header);
			$this->load->view('dashboard/template/commons/menu',$menu);
			$this->load->view('dashboard/produtor/home',$data);
			$this->load->view('dashboard/template/commons/footer');
			
		}else{
			redirect(base_url('login'));
		}
	}

	//Tela Cadastro Produtor
	public function Cadastro() {

		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)){	
			
			$data["produtores"] = false;
			
			//Estados
			$data["estados"] = $this->Crud_model->ReadAll("estado");
			$data["t_pessoas"] = $this->Crud_model->ReadAll("tipo_pessoa");

			$header['title'] = "Dash | Cadastro Produtor";
			$data['title'] = "Cadastro de Produtor";
			$data['idFormulario'] = "inserirProdutor";
			$data['btnFoto'] = "Selecionar";
			$menu['id_page'] = 3;

			$this->load->view('dashboard/template/commons/header',$header);
			$this->load->view('dashboard/template/commons/menu',$menu);
			$this->load->view('dashboard/produtor/cadastro',$data);
			$this->load->view('dashboard/produtor/modal-propriedade',$data);
			$this->load->view('dashboard/template/commons/footer');
			
		}else{
			redirect(base_url('login'));
		}

	}

	//Tela Edição Produtor
	public function Editar() {

		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)){	
			
			$data["produtores"] = false;
			
			//Estados
			$data["estados"] = $this->Crud_model->ReadAll("estado");
			$data["t_pessoas"] = $this->Crud_model->ReadAll("tipo_pessoa");

			$header['title'] = "Dash | Produtor";
			$data['title'] = "Editar Produtor";
			$data['idFormulario'] = "editarProdutor";
			$data['btnFoto'] = "Alterar";
			$menu['id_page'] = 3;

			$dataModel = $this->Crud_model->Read('produtor',array('id_produtor' => $this->uri->segment(3)));

			if ($dataModel) {
				
				$data['produtor'] = $dataModel->id_produtor;
				$this->load->view('dashboard/template/commons/header',$header);
				$this->load->view('dashboard/template/commons/menu',$menu);
				$this->load->view('dashboard/produtor/cadastro',$data);
				$this->load->view('dashboard/produtor/modal-propriedade',$data);
				$this->load->view('dashboard/template/commons/footer');

			}else{
				redirect(base_url('admin/produtor'));	
			}
			
			
		}else{
			redirect(base_url('login'));
		}

	}

	//Select individual
	public function GetId(){
		
		$ref = $this->uri->segment(5);
		
		if ($ref > 0):
			
			$sql = "SELECT * FROM produtor p
			INNER JOIN cidade c ON (c.id_cidade = p.id_cidade)
			INNER JOIN estado e ON (e.id_estado = c.id_estado)
			WHERE p.fg_ativo = 1 AND p.id_produtor = $ref";

			$res = $this->Crud_model->Query($sql);
			
			if ($res):
				$json = json_encode($res,JSON_UNESCAPED_UNICODE);
				echo $json;
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}

	//Select com Paginacao
	public function Get(){
		$ref = $this->uri->segment(4);
		$page = $ref * 20 - 20;

		//Pesquisa
		$nome = "1=1";
		$tipo = "1=1";
		$cidade = "1=1";
		
		if ($this->input->get("nome") != null) {
			$nome = "p.nome_produtor LIKE '%".$this->input->get("nome")."%'";
		}

		if ($this->input->get("tipo") != null) {
			$tipo = "p.id_tipo_pessoa = ".$this->input->get("tipo");
		}

		if ($this->input->get("cidade") != null) {
			$cidade = "c.nome_cidade LIKE '%".$this->input->get("cidade")."%'";
		}
		
		$res1 = $this->Crud_model->Count('produtor');
		$qtd = 0;

		if ($res1->total > 20):
			$qtd = round($res1->total/20) + 1;
		elseif($res1->total > 0):
			$qtd = 1;
		endif;

		if ($ref > 0):
			$sql = "SELECT * FROM produtor p 
			INNER JOIN cidade c ON (p.id_cidade = c.id_cidade)
			INNER JOIN tipo_pessoa t ON (p.id_tipo_pessoa = t.id_tipo_pessoa)
			WHERE $nome and $tipo and $cidade
			LIMIT 20 OFFSET $page";
			$res = $this->Crud_model->Query($sql);
			if ($res):
				$json = json_encode($res,JSON_UNESCAPED_UNICODE);
				echo '{"pages":'.$qtd.',"result":'.$json.'}';
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}

	//Inserindo registros
	public function Register() {

		$nivel_user = 1;
		$foto_name = null;
		$comprovante_name = null;
		
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):

			$dataRegister = $this->input->post();
			if ($dataRegister AND $dataRegister['nome_produtor'] != NULL):


				$dataModel = array(
					'nome_produtor' => trim($dataRegister['nome_produtor']),
					'id_tipo_pessoa' => trim($dataRegister['id_tipo_pessoa']),
					'cpf_cnpj' => trim($dataRegister['cpf_cnpj']),
					'rg_inscricao_estadual' => trim($dataRegister['rg_inscricao_estadual']),
					'data_nascimento' => trim($dataRegister['data_nascimento']),
					'escolaridade' => trim($dataRegister['escolaridade']),
					'membros_familia' => trim($dataRegister['membros_familia']),
					'email' => trim($dataRegister['email']),
					'telefone' => trim($dataRegister['telefone']),
					'foto_produtor' => $foto_name,
					'endereco' => trim($dataRegister['endereco']),
					'numero' => trim($dataRegister['numero']),
					'complemento' => trim($dataRegister['complemento']),
					'cep' => trim($dataRegister['cep']),
					'bairro' => trim($dataRegister['bairro']),
					'id_cidade' => trim($dataRegister['id_cidade']),
					'comprovante_bancario' => $comprovante_name,
					'certificados' => trim($dataRegister['certificados']));
				$res = $this->Crud_model->InsertId('produtor',$dataModel);


			//Config ambiente de upload
				$path = './uploads/docs/'.$res;
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size'] = '5000';
				$config['encrypt_name']  = TRUE;
				$this->upload->initialize($config);

			//verifica se o path é válido, se não for cria o diretório
				if (!is_dir($path)) {
					mkdir($path, 0777, $recursive = true);
				}

				if (!$this->upload->do_upload('foto_file')) {
					$foto_name = "";
				} else {
					$dadosImagem = $this->upload->data();
					$foto_name = $dadosImagem['file_name'];
				}

				if (!$this->upload->do_upload('comprovante_file')) {
					$comprovante_name = "";
				} else {
					$dadosImagem = $this->upload->data();
					$comprovante_name = $dadosImagem['file_name'];
				}

				$dataModel = array('comprovante_bancario' => $foto_name,'foto_produtor' => $comprovante_name);
				$upload = $this->Crud_model->Update('produtor',$dataModel,array('id_produtor' => $res));

				if($res and $upload):
					echo $res;
					$this->output->set_status_header('200');
					return;
				endif;
			endif;
		else:
			$this->output->set_status_header('400');
		endif;
	}

	//Inserindo registros
	public function Edit() {

		$nivel_user = 1;
		$foto_name = null;
		$comprovante_name = null;
		
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):

			$dataRegister = $this->input->post();

			$dataId = (int)$this->uri->segment(5);

			if ($dataRegister AND $dataId > 0):

			//Config ambiente de upload
				$path = './uploads/docs/'.$dataId.'/';
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size'] = '5000';
				$config['encrypt_name']  = TRUE;
				$this->upload->initialize($config);

			//verifica se o path é válido, se não for cria o diretório
				if (!is_dir($path)) {
					mkdir($path, 0777, $recursive = true);
				}

				if (!$this->upload->do_upload('foto_file')) {
					$foto_name = false;
				} else {
					$dadosImagem = $this->upload->data();
					$foto_name = $dadosImagem['file_name'];
				}

				if (!$this->upload->do_upload('comprovante_file')) {
					$comprovante_name = false;
				} else {
					$dadosImagem = $this->upload->data();
					$comprovante_name = $dadosImagem['file_name'];
				}

				$dataModel = array(
					'nome_produtor' => trim($dataRegister['nome_produtor']),
					'id_tipo_pessoa' => trim($dataRegister['id_tipo_pessoa']),
					'cpf_cnpj' => trim($dataRegister['cpf_cnpj']),
					'rg_inscricao_estadual' => trim($dataRegister['rg_inscricao_estadual']),
					'data_nascimento' => trim($dataRegister['data_nascimento']),
					'escolaridade' => trim($dataRegister['escolaridade']),
					'membros_familia' => trim($dataRegister['membros_familia']),
					'email' => trim($dataRegister['email']),
					'telefone' => trim($dataRegister['telefone']),
					'endereco' => trim($dataRegister['endereco']),
					'numero' => trim($dataRegister['numero']),
					'complemento' => trim($dataRegister['complemento']),
					'cep' => trim($dataRegister['cep']),
					'bairro' => trim($dataRegister['bairro']),
					'id_cidade' => trim($dataRegister['id_cidade']),
					'certificados' => trim($dataRegister['certificados']));

			//caso mudou a img exclui a anterior
				$sql = "SELECT foto_produtor, comprovante_bancario FROM produtor WHERE id_produtor = $dataId";
				$upload = $this->Crud_model->Query($sql);

				if ($foto_name) {
					$dataModel = array_merge($dataModel,array('foto_produtor' => $foto_name));
					if ($upload) {
						unlink($path.$upload[0]->foto_produtor);
					}
				}
				if ($comprovante_name) {
					$dataModel = array_merge($dataModel,array('comprovante_bancario' => $comprovante_name));
					if ($upload) {
						unlink($path.$upload[0]->comprovante_bancario);
					}
				}


				$res = $this->Crud_model->Update('produtor',$dataModel,array('id_produtor' => $dataId));

				if($res):
					echo $res;
					$this->output->set_status_header('200');
					return;
				endif;

			endif;
		else:
			$this->output->set_status_header('400');
		endif;

	}

	//Delete registro
	public function Remove(){
		$nivel_user = 2;
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)):
			$dataId = (int)$this->uri->segment(5);
			if ($dataId > 0):

				$sql = "SELECT id_propriedade FROM propriedade WHERE id_produtor = $dataId";
				$query = $this->Crud_model->Query($sql);
				if ($query) {
					foreach ($query as $q) {
						$res = $this->Crud_model->Delete('safra_previsao',array('id_propriedade' => $q->id_propriedade));
						$res = $this->Crud_model->Delete('safra_fechamento',array('id_propriedade' => $q->id_propriedade));
						$res = $this->Crud_model->Delete('safra_cafe',array('id_propriedade' => $q->id_propriedade));
					}
				}
				//remove todas as propriedades
				$propriedade = $this->Crud_model->Delete('propriedade',array('id_produtor' => $dataId));
				$res = $this->Crud_model->Delete('produtor',array('id_produtor' => $dataId));

				if($res):
					//remove a pasta
					$path = './uploads/docs/'.$dataId.'/';
					$this->unlinkRecursive($path);
					$this->output->set_status_header('200');
					return;
				endif;
			endif;
		else:
			$this->output->set_status_header('400');
		endif;
	}

	//funcao deletar pasta e conteudo
	function unlinkRecursive($path) {

		if($dh = @opendir($path)) { 

			while (false !== ($obj = readdir($dh))) 
			{ 
				if($obj == '.' || $obj == '..') 
				{ 
					continue; 
				} 
				if (!@unlink($path . '/' . $obj)) 
				{ 
					$this->unlinkRecursive($path.'/'.$obj, true);
				} 
			} 
			closedir($dh); 
			@rmdir($path);
		}
		return;
	} 

}