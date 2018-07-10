<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Produto extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library(['form_validation','upload','image_lib']);
	} 
	
	//Tela produto
	public function index(){

		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)) {

			$data["categoria_produto"] = $this->Crud_model->ReadAll("categoria_produto");
			$header['title'] = "Dash | Produto";
			$menu['id_page'] = 3;

			$this->load->view('dashboard/template/commons/header',$header);
			$this->load->view('dashboard/template/commons/menu',$menu);
			$this->load->view('dashboard/produto/home',$data);
			$this->load->view('dashboard/template/commons/footer');
			
		}else{
			redirect(base_url('login'));
		}
	}

	//Tela Cadastro produto
	public function Cadastro() {

		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)){	
			
			$data["categoria_produto"] = $this->Crud_model->ReadAll("categoria_produto");

			$header['title'] = "Dash | Cadastro produto";
			$data['title'] = "Cadastrar Produto";
			$data['idFormulario'] = "inserirProduto";
			$menu['id_page'] = 3;
			
			$data['produto'] = null;
			$data['editar'] = false;
			$data['coluna'] = "m3";
			$this->load->view('dashboard/template/commons/header',$header);
			$this->load->view('dashboard/template/commons/menu',$menu);
			$this->load->view('dashboard/produto/cadastro',$data);
			$this->load->view('dashboard/template/commons/footer');
			
		}else{
			redirect(base_url('login'));
		}

	}

	//Tela Edição produto
	public function Editar() {

		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)){	
			
			//categoria produto
			$data["categoria_produto"] = $this->Crud_model->ReadAll("categoria_produto");

			$header['title'] = "Dash | Editar Produto";
			$data['title'] = "Editar Produto";
			$data['idFormulario'] = "editarProduto";
			$menu['id_page'] = 3;

			$dataModel = $this->Crud_model->Read('produto',array('id_produto' => $this->uri->segment(3)));

			if ($dataModel) {
				
				$data['produto'] = $dataModel->id_produto;
				$data['editar'] = true;
				$data['coluna'] = "m4";
				$data['tabela'] = $this->Crud_model->ReadAll('tabela_preco');

				$sql = "SELECT id_produto, nome_produto FROM produto WHERE fg_ativo = 1 AND id_categoria = 1";
				$data['produtos'] = $this->Crud_model->Query($sql);

				$this->load->view('dashboard/template/commons/header',$header);
				$this->load->view('dashboard/template/commons/menu',$menu);
				$this->load->view('dashboard/produto/cadastro',$data);
				$this->load->view('dashboard/template/commons/footer');

			}else{
				redirect(base_url('admin/produto'));	
			}
			
			
		}else{
			redirect(base_url('login'));
		}

	}

	//Select individual
	public function GetId(){
		
		$ref = $this->uri->segment(5);
		
		if ($ref > 0):
			
			$sql = "SELECT id_produto, nome_produto, ref_produto, id_categoria
			FROM produto p
			WHERE fg_ativo = 1 AND id_produto = $ref";

			$res = $this->Crud_model->Query($sql);
			$res = $res[0];
			
			if ($res):
				$json = json_encode($res,JSON_UNESCAPED_UNICODE);
				
				$sql = "SELECT p.id_produto, p.nome_produto, p.ref_produto 
				FROM item_produto ip 
				INNER JOIN produto p ON (p.id_produto = ip.id_produto_item) 
				WHERE ip.id_produto = $ref";

				$res = $this->Crud_model->Query($sql);
				if ($res):
					$itens = json_encode($res,JSON_UNESCAPED_UNICODE);
					echo '{"produto":'.$json.',"itens":'.$itens.'}';
					return;
				else:
					echo '{"produto":'.$json.',"itens":""}';
				endif;

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
		$referencia = "1=1";
		$nome = "1=1";
		$categoria = "1=1";
		
		if ($this->input->get("referencia") != null) {
			$referencia = "p.ref_produto = '".$this->input->get("referencia")."'";
		}

		if ($this->input->get("categoria") != null && $this->input->get("categoria") != 0) {
			$categoria = "p.id_categoria = ".$this->input->get("categoria");
		}

		if ($this->input->get("nome") != null) {
			$nome = "p.nome_produto LIKE '%".$this->input->get("nome")."%'";
		}

		$sqlCount = "SELECT COUNT(*) as qtd FROM produto p 
			INNER JOIN categoria_produto c ON (p.id_categoria = c.id_categoria)
			WHERE $nome and $referencia and $categoria and p.fg_ativo = 1";

		$res1 = $this->Crud_model->Query($sqlCount);
		$qtd = 0;
		
		if ($res1[0]->qtd > 20):
			$qtd = round($res1[0]->qtd/20) + 1;
		elseif($res1[0]->qtd > 0):
			$qtd = 1;
		endif;

		if ($ref > 0):
			$sql = "SELECT * FROM produto p 
			INNER JOIN categoria_produto c ON (p.id_categoria = c.id_categoria)
			WHERE $nome and $referencia and $categoria and p.fg_ativo = 1
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
			if ($dataRegister AND $dataRegister['nome_produto'] != NULL):

				
				$nome_produto = trim($dataRegister['nome_produto']);
				$id_categoria = trim($dataRegister['id_categoria']);
				$gerar_referencia = trim($dataRegister['gerar_referencia']);
				$referencia = "";

				if(isset($dataRegister['referencia'])){
					$referencia = trim($dataRegister['referencia']);
				}
				
				if($gerar_referencia == 1 OR $referencia == ""){
					$referencia = "R".rand(1,1000000);
				}

				$dataModel = array(
					'nome_produto' => $nome_produto,
					'id_categoria' => $id_categoria,
					'ref_produto' => $referencia);

				$res = $this->Crud_model->InsertId('produto',$dataModel);

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
					'nome_produto' => trim($dataRegister['nome_produto']),
					'id_categoria' => trim($dataRegister['id_categoria']),
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
				$sql = "SELECT foto_produto, comprovante_bancario FROM produto WHERE id_produto = $dataId";
				$upload = $this->Crud_model->Query($sql);

				if ($foto_name) {
					$dataModel = array_merge($dataModel,array('foto_produto' => $foto_name));
					if ($upload) {
						unlink($path.$upload[0]->foto_produto);
					}
				}
				if ($comprovante_name) {
					$dataModel = array_merge($dataModel,array('comprovante_bancario' => $comprovante_name));
					if ($upload) {
						unlink($path.$upload[0]->comprovante_bancario);
					}
				}


				$res = $this->Crud_model->Update('produto',$dataModel,array('id_produto' => $dataId));

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

				$sql = "SELECT id_propriedade FROM propriedade WHERE id_produto = $dataId";
				$query = $this->Crud_model->Query($sql);
				if ($query) {
					foreach ($query as $q) {
						$res = $this->Crud_model->Delete('safra_previsao',array('id_propriedade' => $q->id_propriedade));
						$res = $this->Crud_model->Delete('safra_fechamento',array('id_propriedade' => $q->id_propriedade));
						$res = $this->Crud_model->Delete('safra_cafe',array('id_propriedade' => $q->id_propriedade));
					}
				}
				//remove todas as propriedades
				$propriedade = $this->Crud_model->Delete('propriedade',array('id_produto' => $dataId));
				$res = $this->Crud_model->Delete('produto',array('id_produto' => $dataId));

			endif;
		else:
			$this->output->set_status_header('400');
		endif;
	}

	public function getProdutosCategoriaTabela(){
		$id = $this->uri->segment(4);
		
		if ($id > 0):
			
			$sql = "SELECT p.id_produto, p.nome_produto, p.ref_produto, p.id_categoria, t.id_tabela ,t.nome_tabela
			FROM produto p
			INNER JOIN tabela_produto tp ON (tp.id_produto = p.id_produto)
			INNER JOIN tabela_preco t ON (t.id_tabela = tp.id_tabela)
			WHERE p.fg_ativo = 1 AND p.id_categoria = $id order by p.id_produto, t.id_tabela";

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

	public function getProdutosCategoria(){
		$id = $this->uri->segment(4);
		
		if ($id > 0):
			
			$sql = "SELECT id_produto, nome_produto
			FROM produto
			WHERE fg_ativo = 1 AND id_categoria = $id";

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

	
	

}