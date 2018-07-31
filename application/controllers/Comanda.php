<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Comanda extends CI_Controller {

	public function index(){

		//Status da comanda
		if ($this->input->get("status") == 1) {
			$data['status'] = 1;
			$status = 'status = 1';
		}else{
			$data['status'] = 0;
			$status = 'status = 0';
		}

		//Filtros da data da comanda
		if($this->input->get("data_de")){
			$data['optionDataDe'] = $this->input->get('data_de');
			$dataDe = "DATE(data_comanda) >= '".$this->input->get('data_de')."'";
		}else{
			$data['optionDataDe'] = '';
			$dataDe = "1=1";
		}
		//Filtros da data da comanda
		if($this->input->get("data_ate")){
			$data['optionDataAte'] = $this->input->get('data_ate');
			$dataAte = "DATE(data_comanda) <= '".$this->input->get('data_ate')."'";
		}else{
			$data['optionDataAte'] = '';
			$dataAte = "1=1";
		}

		//sql busca
		$sql = 'SELECT c.id_comanda, c.status, DATE_FORMAT(c.data_comanda, "%d-%m") as data_comanda, DATE_FORMAT(c.data_comanda, "%h:%i") as hora_comanda, c.ref_comanda, c.tipo_comanda, c.mesa as nome_mesa, cl.nome_cliente 
        FROM comanda c 
        LEFT JOIN cliente cl ON (cl.id_cliente = c.id_cliente_viagem)
        where c.status = 1 and 1=1 and 1=1 order by c.data_comanda desc';

		$data['comandas'] = $this->Crud_model->Query($sql);
        
        //$sql = "SELECT count(*) as qtd FROM contato where $visualizado";
		//$data['qtd'] = $this->Crud_model->Query($sql);

		$menu['id_page'] = 2;
		$header['title'] = 'Dash | Comanda';
		$header['page'] = '1';

        $this->load->view('dashboard/template/commons/header',$header);
        $this->load->view('dashboard/template/commons/menu',$menu);
		$this->load->view('dashboard/comanda/home',$data);
		$this->load->view('dashboard/template/commons/footer');
	}

	public function Editar(){
        
        $nivel_user = 1; //Nivel requirido para visualizar a pagina
		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)){	
			
			$dataModel = $this->Crud_model->Read('comanda',array('id_comanda' => $this->uri->segment(3)));

			if ($dataModel) {
				
				$data['comanda'] = $dataModel;
				$modal['categorias'] = $this->Crud_model->Query("SELECT id_categoria, nome_categoria from categoria_produto where id_categoria != 1");

				$header['title'] = 'Dash | Comanda';
                $header['page'] = '1';

                $this->load->view('dashboard/template/commons/header',$header);
                $this->load->view('dashboard/comanda/editar-comanda',$data);
                $this->load->view('dashboard/comanda/modal-comanda',$modal);
                $this->load->view('dashboard/template/commons/footer');

			}else{
				redirect(base_url('admin/produto'));	
			}
			
		}else{
			redirect(base_url('login'));
		}
	}
	
	public function Comandas(){

		$sql = 'SELECT c.id_comanda, c.status, DATE_FORMAT(c.data_comanda, "%d-%m") as data_comanda, DATE_FORMAT(c.data_comanda, "%h:%i") as hora_comanda, c.ref_comanda, c.tipo_comanda, c.observacao, c.mesa, cl.nome_cliente 
        FROM comanda c 
        LEFT JOIN cliente cl ON (cl.id_cliente = c.id_cliente_viagem)
        where c.status = 1 order by c.mesa asc';

		$res = $this->Crud_model->Query($sql);
		
		if ($res):
			$json = json_encode($res,JSON_UNESCAPED_UNICODE);
			echo $json;
			return;
		else: 
			$this->output->set_status_header('204');
		endif;		
	}

	public function InserirComanda(){

		$dataRegister = $this->input->post();
		
		if ($dataRegister == null) {
			$this->output->set_status_header('500');
			return;
		}

		$mesa = $dataRegister['mesa'];
		$observacao = $dataRegister['observacao'];

		$ref_comanda = rand(1,00000000000000);

		$dataModel = array('mesa' => $mesa, 'observacao' => $observacao, 'ref_comanda' => $ref_comanda);
		$res = $this->Crud_model->InsertID('comanda',$dataModel);
		if ($res) {
			$id = $res;
			$dataModel = array('ref_comanda' => "CMD0".$id);
			$res = $this->Crud_model->Update('comanda',$dataModel,array('id_comanda' => $id));
			if($res) {
				echo $id;
				$this->output->set_status_header('200');
				return;
			}else {
				$this->output->set_status_header('500');
			}
		}

		$this->output->set_status_header('500');	
	}

	public function ComandaId(){

		$id = $this->uri->segment(5);
		if ($id > 0):
			
			$res = $this->Crud_model->Read('comanda',array('id_comanda' => $id));

			if ($res):
				$json = json_encode($res,JSON_UNESCAPED_UNICODE);
				echo $json;
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}

	public function ComandaRef(){

		$ref = $this->uri->segment(5);
		
		if ($ref != ""):
			
			$res = $this->Crud_model->Read('comanda',array('ref_comanda' => $ref));
			if ($res):
				echo $res->id_comanda;
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}

	public function ProdutosComanda(){

		$id = $this->uri->segment(4);
		if ($id > 0):
			
			$sql = "SELECT cp.id_comanda_produto, p.id_produto, c.id_comanda, p.ref_produto, cat.id_categoria, cat.nome_categoria, p.nome_produto, cp.quantidade, t.valor, tp.nome_tabela
			FROM comanda_produto cp 
			INNER JOIN comanda c ON (c.id_comanda = cp.id_comanda)
			INNER JOIN produto p ON (p.id_produto = cp.id_produto)
			INNER JOIN categoria_produto cat ON (cat.id_categoria = p.id_categoria)
			INNER JOIN tabela_produto t ON (t.id_tabela_produto = cp.id_tabela_produto)
			INNER JOIN tabela_preco tp ON (tp.id_tabela = t.id_tabela)
			WHERE cp.fg_ativo = 1 AND cp.id_comanda = $id AND cat.id_categoria != 1 ORDER BY cp.id_comanda_produto";

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

	public function ProdutoComandaId(){

		$produto = $this->uri->segment(4);

		if ($produto > 0):
			
			$sql = "SELECT cp.id_comanda_produto, cat.id_categoria, cat.nome_categoria, p.nome_produto, cp.quantidade, tp.nome_tabela, cp.observacao
			FROM comanda_produto cp 
			INNER JOIN comanda c ON (c.id_comanda = cp.id_comanda)
			INNER JOIN produto p ON (p.id_produto = cp.id_produto)
			INNER JOIN categoria_produto cat ON (cat.id_categoria = p.id_categoria)
			INNER JOIN tabela_produto t ON (t.id_tabela_produto = cp.id_tabela_produto)
			INNER JOIN tabela_preco tp ON (tp.id_tabela = t.id_tabela)
			WHERE cp.fg_ativo = 1 AND cp.id_comanda_produto = $produto";

			$res = $this->Crud_model->Query($sql);
			$res = $res[0];
			
			if ($res):
				$json = json_encode($res,JSON_UNESCAPED_UNICODE);
				echo $json;
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}

	public function InserirProdutoComanda(){
		
		$dataRegister = $this->input->post();
		
		if ($dataRegister == null) {
			$this->output->set_status_header('500');
			return;
		}

		//die(var_dump($dataRegister));
		$id_comanda = $dataRegister['id_comanda'];
        $gerar_pedido = $dataRegister['gerar_pedido'];
        $quantidade = $dataRegister['quantidade'];
        $observacao = $dataRegister['observacao'];
        $status_pedido = 0;

        if ($gerar_pedido == 1) {
            $status_pedido = 1;
        }

        $id_tabela_produto = $dataRegister['id_tabela_produto'];
        $id_produto = [];

        $dataObservacao = "";
        $produtos = (isset($dataRegister['produtos'])) ? $dataRegister['produtos'] : null;
        if ($produtos != null && count($produtos) > 1) {
            $produtoGenerico = 15;
            foreach ($produtos as $p) {

                //TODO Verificar o produto com a tabela de preço maior
                
                $dataModel = array('id_comanda' => $id_comanda, 'id_produto' => $produtoGenerico, 'quantidade' => $quantidade, 'id_tabela_produto' => $id_tabela_produto,'status_pedido' => $status_pedido);
                $res = $this->Crud_model->Insert('comanda_produto',$dataModel);

                if ($res) {
                    $data = $this->Crud_model->Read('produto',array('id_produto' => $p));
                    $dataObservacao .= "1/2 " . $data->nome_produto . "||";
                }
            }
        } else if (count($produtos) == 1){
        }

        $adicionais = (isset($dataRegister['adicionais'])) ? $dataRegister['adicionais'] : null;
		if ($adicionais != null) {
			$obsTemp = "";
			foreach ($adicionais as $ads) {
				$adsProduto = explode('||', $ads);
				$adsId = $adsProduto[0];
				$adsTabela = $adsProduto[1];
				$dataModel = array('id_comanda' => $id_comanda, 'id_produto' => $adsId, 'quantidade' => 1, 'id_tabela_produto' => $adsTabela,'status_pedido' => $status_pedido);
				$res = $this->Crud_model->Insert('comanda_produto',$dataModel);
				
				if ($res) {
					$dataAds = $this->Crud_model->Read('produto',array('id_produto' => $adsId));
					$obsTemp .= $dataAds->nome_produto . ", ";
				}
			}
			$dataObservacao .= "Adicionais: " . substr($obsTemp, 0, -2) ."||";
		}

		$remocoes = (isset($dataRegister['remocoes'])) ? $dataRegister['remocoes'] : null;
		if ($remocoes != null) {
			$obsTemp = "";
			foreach ($remocoes as $obs) {
				$obsTemp .= $obs . ", ";
			}
			$dataObservacao .= "Remoções: " . substr($obsTemp, 0, -2) ."||";
		}


		if ($observacao != "") {
			$observacao = $observacao."||".$dataObservacao;
		}else {
			$observacao = $dataObservacao;
		}

		$observacao = substr($observacao, 0, -2);

		$dataModel = array('id_comanda' => $id_comanda, 'id_produto' => $id_produto, 'gerar_pedido' => $gerar_pedido, 'quantidade' => $quantidade, 'id_tabela_produto' => $id_tabela_produto, 'observacao' => $observacao);
		$res = $this->Crud_model->Insert('comanda_produto',$dataModel);
		//$res = true;
		if($res)  {	
			$this->output->set_status_header('200');
		}else {
			$this->output->set_status_header('500');
		}
	}

}