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

	public function GetComandas(){

        $pagina = $this->uri->segment(3);
        $chave = $this->uri->segment(4);
        $nivel_acesso = 1;

        $pagination = "LIMIT 30 OFFSET ".($pagina - 1) * 30;
        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {

            $where_clause = "WHERE 1=1 ";
            $order_clause = "";

            //ID da comanda
            if ($this->input->get('id_comanda') != null) {
                $par = (int) $this->input->get('id_comanda');
                $where_clause .= " AND c.id_comanda=".$par;
            }

            //Referencia da comanda
            if ($this->input->get('ref_comanda') != null) {
                $par = $this->input->get('ref_comanda');
                $where_clause .= " AND c.ref_comanda='".$par."'";
            }

            //Status da comanda
            if ($this->input->get('status_comanda') != null) {
                $par = $this->input->get('status_comanda');
                $where_clause .= " AND c.status=".$par;
            }

            //Data "DE" da comanda
            if ($this->input->get('data_de') != null) {
                $par = $this->input->get('data_de');
                $where_clause .= " DATE(data_comanda) >= '".$par."'";
            }

            //Data "Ate" da comanda
            if ($this->input->get('data_ate') != null) {
                $par = $this->input->get('data_ate');
                $where_clause .= " DATE(data_comanda) <= '".$par."'";
            }

            //Tipo da comanda
            if ($this->input->get('tipo_comanda') != null) {
                $par = (int) $this->input->get('tipo_comanda');
                if ($par == 1) {
                    $where_clause .= " AND c.mesa is not null";
                } else if ($par == 2) {
                    $where_clause .= " AND c.id_cliente_viagem is not null";
                }
            }

            //Ordenacao por data
            if ($this->input->get('ordenar') != null) {
                $order = ($this->input->get('ordenar') === 'true');
                if ($order) {
                    $order_clause = "ORDER BY c.data_comanda desc";
                } else {
                    $order_clause = "ORDER BY c.data_comanda asc";
                }
            }

            $sql = "SELECT c.id_comanda, c.status, DATE_FORMAT(c.data_comanda, '%d-%m') as data_comanda, 
            DATE_FORMAT(c.data_comanda, '%h:%i') as hora_comanda, c.ref_comanda, c.observacao, c.mesa, cl.nome_cliente 
            FROM comanda c 
            LEFT JOIN cliente cl ON (cl.id_cliente = c.id_cliente_viagem)
            $where_clause $order_clause $pagination";

            $res = $this->Crud_model->Query($sql);
            if ($res){
                $json = json_encode($res,JSON_UNESCAPED_UNICODE);
                echo $json;
                return;
            } else {
                $this->output->set_status_header('204');
                return;
            }

        }

        $this->output->set_status_header('401');
	}

	public function RegisterComanda(){

        $chave = $this->uri->segment(4);
        $nivel_acesso = 2;
        $json = [];

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {

            $dataRegister = $this->input->post();

            if ($dataRegister == null) {
                echo json_encode(array('result' => 'Erro nos parametros enviados','id_comanda' => null), JSON_UNESCAPED_UNICODE);
                $this->output->set_status_header('500');
                return;
            }

            $mesa = $dataRegister['mesa'];
            $observacao = $dataRegister['observacao'];

            $ref_comanda = rand(1000, 9000);

            $dataModel = array('mesa' => $mesa, 'observacao' => $observacao, 'ref_comanda' => $ref_comanda);
            $res = $this->Crud_model->InsertID('comanda', $dataModel);
            if ($res) {
                $dataModel = array('ref_comanda' => $ref_comanda + $res);
                $res2 = $this->Crud_model->Update('comanda', $dataModel, array('id_comanda' => $res));
                if ($res2) {
                    echo json_encode(array('result' => 'Sucesso','id_comanda' => $res), JSON_UNESCAPED_UNICODE);
                    return;

                } else {
                    echo json_encode(array('result' => 'Erro no Servidor','id_comanda' => null), JSON_UNESCAPED_UNICODE);
                    $this->output->set_status_header('500');
                    return;
                }
            }

        }

        echo json_encode(array('result' => 'Você não tem acesso a esta função!','id_comanda' => null), JSON_UNESCAPED_UNICODE);
		$this->output->set_status_header('401');
	}

    public function EditComanda()
    {

        $chave = $this->uri->segment(4);
        $nivel_acesso = 2;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado) {

            $dataRegister = $this->input->post();

            if ($dataRegister == null) {
                echo json_encode(array('result' => 'Erro nos parametros enviados'), JSON_UNESCAPED_UNICODE);
                $this->output->set_status_header('500');
                return;
            }

            $id_comanda = $dataRegister['id_comanda'];
            $mesa = $dataRegister['mesa'];
            $observacao = $dataRegister['observacao'];

            $dataModel = array('mesa' => $mesa, 'observacao' => $observacao);
            $res = $this->Crud_model->Update('comanda', $dataModel, array("id_comanda" => $id_comanda));

            if ($res) {
                echo json_encode(array('result' => 'Sucesso'), JSON_UNESCAPED_UNICODE);
                return;
            }

        }

        echo json_encode(array('result' => 'Você não tem acesso a esta função!'), JSON_UNESCAPED_UNICODE);
        $this->output->set_status_header('401');
    }

	public function GetProdutosComanda(){

		$chave = $this->uri->segment(4);
        $nivel_acesso = 1;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {

            $id = $this->uri->segment(5);

            $where_clause = "AND cp.id_comanda = $id ";
            if ($this->input->get("id_pedido") != null) {
                $par = (int) $this->input->get("id_pedido");
                $where_clause .= " AND cp.id_comanda_produto = $par";
            }

            $sql = "SELECT cp.id_comanda_produto, c.id_comanda, cat.id_categoria, cat.pizza, cat.nome_categoria, GROUP_CONCAT(p.id_produto SEPARATOR '||') as id_produto, GROUP_CONCAT(p.nome_produto SEPARATOR '||') as nome_produto, cp.quantidade, tp.valor, t.id_tabela, t.nome_tabela, cp.observacao
			FROM comanda_produto cp 
			INNER JOIN comanda c ON (c.id_comanda = cp.id_comanda)
			INNER JOIN cp_produtos cpp ON (cp.id_comanda_produto = cpp.id_cp)
            INNER JOIN produto p ON (p.id_produto = cpp.id_produto)
			INNER JOIN categoria_produto cat ON (cat.id_categoria = p.id_categoria)
            INNER JOIN tabela_preco tp ON (tp.id_tabela_preco = cp.id_tabela_preco)
			INNER JOIN tabela_produto t ON (t.id_tabela = tp.id_tabela)
			WHERE cp.fg_ativo = 1 AND p.ingrediente != 1 $where_clause
            GROUP BY cp.id_comanda_produto ORDER BY cp.id_comanda_produto";

            $pedidos = $this->Crud_model->Query($sql);

            if ($pedidos) {
                $json = [];

                foreach ($pedidos as $pedido) {

                    $pedido = (array)$pedido;
                    $id = $pedido["id_comanda_produto"];

                    $sql = "SELECT p.id_produto, p.nome_produto, cp.id_tabela_preco
                        FROM cp_adicionais cpa
                        INNER JOIN comanda_produto cp ON (cpa.id_cpa = cp.id_comanda_produto)
                        INNER JOIN cp_produtos cpp ON (cp.id_comanda_produto = cpp.id_cp)
                        INNER JOIN produto p ON (cpp.id_produto  = p.id_produto) 
                        WHERE cpa.id_cp = $id";

                    $res = $this->Crud_model->Query($sql);
                    if ($res) {
                        $json[] = array_merge($pedido, array('adicionais' => $res));
                    } else {
                        $json[] = array_merge($pedido, array('adicionais' => null));
                    }
                }

                $json = json_encode($json, JSON_UNESCAPED_UNICODE);
                echo $json;
                return;

            } else {
                $this->output->set_status_header('204');
                return;

            }

            $this->output->set_status_header('401');

        }
	}

	public function InserirProdutoComanda()
    {
        $chave = $this->uri->segment(5);
        $nivel_acesso = 1;
        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {

            $dataRegister = $this->input->post();

            $id_comanda = $dataRegister['id_comanda'];
            $id_tabela = $dataRegister['id_tabela'];
            $gerar_pedido = $dataRegister['gerar_pedido'];
            $quantidade = $dataRegister['quantidade'];
            $observacao = $dataRegister['observacao'];
            $status_pedido = 0;
            $produtos = (isset($dataRegister['produtos'])) ? $dataRegister['produtos'] : null;

            $dataObservacao = "";

            //Remocoes
            $remocoes = (isset($dataRegister['remocoes'])) ? $dataRegister['remocoes'] : null;
            if ($remocoes != null) {
                $obsTemp = "";
                foreach ($remocoes as $obs) {
                    $obsTemp .= $obs . ", ";
                }
                $dataObservacao .= "Remoções: " . substr($obsTemp, 0, -2) . "||";
            }

            //Buscar valor do produto (Sera buscado a tabela do maior valor)
            $in = "(";
            foreach ($produtos as $p) {
                $in .= $p . ",";
            }
            if (strlen($in) > 2) {
                $in = substr($in, 0, -1) . ")";
            }
            $sql = "SELECT id_tabela_preco FROM tabela_preco 
            WHERE id_produto in $in AND id_tabela = $id_tabela
            ORDER BY valor desc LIMIT 1";
            $data = $this->Crud_model->Query($sql)[0];

            //Observacoes
            if ($observacao != "") {
                $observacao = $dataObservacao . $observacao . "||";
            } else {
                $observacao = $dataObservacao;
            }
            if (strlen($observacao) > 2) {
                $observacao = substr($observacao, 0, -2);
            } else {
                $observacao = null;
            }

            //Inserindo dados na comanda
            $dataModel = array(
                'id_comanda' => $id_comanda,
                'quantidade' => $quantidade,
                'id_tabela_preco' => $data->id_tabela_preco,
                'status_pedido' => $gerar_pedido ? 0 : 1,
                'observacao' => $observacao);

            $id_comanda_produto = $this->Crud_model->InsertId('comanda_produto', $dataModel);

            $res = false;
            if ($id_comanda_produto) {
                $res = true;
            }

            //inserindo produtos
            if ($produtos != null) {
                foreach ($produtos as $p) {
                    $res = $this->Crud_model->Insert('cp_produtos', array("id_cp" => $id_comanda_produto, "id_produto" => $p));
                }
            }

            $adicionais = (isset($dataRegister['adicionais'])) ? $dataRegister['adicionais'] : null;
            if ($adicionais != null && $res) {
                foreach ($adicionais as $ads) {
                    $adsProduto = explode('||', $ads);
                    $adsId = $adsProduto[0];
                    $adsTabela = $adsProduto[1];
                    $dataModel = array(
                        'id_comanda' => $id_comanda,
                        'quantidade' => 1,
                        'id_tabela_preco' => $adsTabela);

                    $id = $this->Crud_model->InsertId('comanda_produto', $dataModel);

                    if ($id) {
                        $res = $this->Crud_model->Insert('cp_produtos', array("id_cp" => $id, "id_produto" => $adsId));
                        $res = $this->Crud_model->Insert('cp_adicionais', array("id_cp" => $id_comanda_produto, "id_cpa" => $id));
                    }
                }
            }

            if ($res) {
                $this->output->set_status_header('200');
            } else {
                $this->output->set_status_header('500');
            }

        }
    }

    public function EditarProdutoComanda()
    {
        $id_comanda_produto = $this->uri->segment(5);
        $chave = $this->uri->segment(6);
        $nivel_acesso = 2;
        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado && $id_comanda_produto != null) {

            $dataRegister = $this->input->post();
            $id_cp = $id_comanda_produto;
            $id_comanda_produto = array("id_comanda_produto" => $id_comanda_produto);

            $id_comanda = $dataRegister['id_comanda'];
            $id_tabela = $dataRegister['id_tabela'];
            $gerar_pedido = $dataRegister['gerar_pedido'];
            $quantidade = $dataRegister['quantidade'];
            $observacao = $dataRegister['observacao'];
            $produtos = (isset($dataRegister['produtos'])) ? $dataRegister['produtos'] : null;

            $dataObservacao = "";

            //Remocoes
            $remocoes = (isset($dataRegister['remocoes'])) ? $dataRegister['remocoes'] : null;
            if ($remocoes != null) {
                $obsTemp = "";
                foreach ($remocoes as $obs) {
                    $obsTemp .= $obs . ", ";
                }
                $dataObservacao .= "Remoções: " . substr($obsTemp, 0, -2) . "||";
            }

            //Buscar valor do produto (Sera buscado a tabela do maior valor)
            $in = "(";
            foreach ($produtos as $p) {
                $in .= $p . ",";
            }
            if (strlen($in) > 2) {
                $in = substr($in, 0, -1) . ")";
            }
            $sql = "SELECT id_tabela_preco FROM tabela_preco 
            WHERE id_produto in $in AND id_tabela = $id_tabela
            ORDER BY valor desc LIMIT 1";
            $data = $this->Crud_model->Query($sql)[0];

            //Observacoes
            if ($observacao != "") {
                $observacao = $dataObservacao . $observacao . "||";
            } else {
                $observacao = $dataObservacao;
            }
            if (strlen($observacao) > 2) {
                $observacao = substr($observacao, 0, -2);
            } else {
                $observacao = null;
            }

            //Inserindo dados na comanda
            $dataModel = array(
                'id_comanda' => $id_comanda,
                'quantidade' => $quantidade,
                'id_tabela_preco' => $data->id_tabela_preco,
                'status_pedido' => $gerar_pedido ? 0 : 1,
                'observacao' => $observacao);

            $this->Crud_model->Delete('cp_produtos', array("id_cp" => $id_cp));
            $res = $this->Crud_model->Update('comanda_produto', $dataModel, $id_comanda_produto);

            //inserindo produtos
            if ($produtos != null) {
                foreach ($produtos as $p) {
                    $res = $this->Crud_model->Insert('cp_produtos', array("id_cp" => $id_cp, "id_produto" => $p));
                }
            }

            $adicionais = (isset($dataRegister['adicionais'])) ? $dataRegister['adicionais'] : null;

            $sql = "SELECT id_cpa FROM cp_adicionais WHERE id_cp = $id_cp";
            $id_cpas = $this->Crud_model->Query($sql);

            $this->Crud_model->Delete('cp_adicionais', array("id_cp" => $id_cp));
            foreach ($id_cpas as $id_cpa) {
                $id_cpa = $id_cpa->id_cpa;
                $this->Crud_model->Delete('cp_produtos', array("id_cp" => $id_cpa));
                $this->Crud_model->Delete('comanda_produto', array("id_comanda_produto" => $id_cpa));
            }

            if ($adicionais != null && $res) {

                foreach ($adicionais as $ads) {
                    $adsProduto = explode('||', $ads);
                    $adsId = $adsProduto[0];
                    $adsTabela = $adsProduto[1];
                    $dataModel = array(
                        'id_comanda' => $id_comanda,
                        'quantidade' => 1,
                        'id_tabela_preco' => $adsTabela);

                    $id = $this->Crud_model->InsertId('comanda_produto', $dataModel);

                    if ($id) {
                        $this->Crud_model->Insert('cp_produtos', array("id_cp" => $id, "id_produto" => $adsId));
                        $res = $this->Crud_model->Insert('cp_adicionais', array("id_cp" => $id_cp, "id_cpa" => $id));
                    }
                }
            }

            if ($res) {
                $this->output->set_status_header('200');
            } else {
                $this->output->set_status_header('500');
            }

        }
    }

    public function RemoveProdutoComanda()
    {

        $chave = $this->uri->segment(5);
        $nivel_acesso = 3;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado) {

            $dataRegister = $this->input->post();

            if ($dataRegister["id_comanda_produto"] == null) {
                echo json_encode(array('result' => 'Erro nos parametros enviados'), JSON_UNESCAPED_UNICODE);
                $this->output->set_status_header('500');
                return;
            }

            $id_cp = (int)$dataRegister['id_comanda_produto'];

            //Deletando os adicionais
            $sql = "SELECT id_cpa FROM cp_adicionais WHERE id_cp = $id_cp";
            $id_cpas = $this->Crud_model->Query($sql);

            $this->Crud_model->Delete('cp_adicionais', array("id_cp" => $id_cp));
            foreach ($id_cpas as $id_cpa) {
                $id_cpa = $id_cpa->id_cpa;
                $this->Crud_model->Delete('cp_produtos', array("id_cp" => $id_cpa));
                $this->Crud_model->Delete('comanda_produto', array("id_comanda_produto" => $id_cpa));
            }

            //Deletando os produtos
            $this->Crud_model->Delete('cp_produtos', array("id_cp" => $id_cp));

            //Deletando a comanda_produto
            $dataPar = array('id_comanda_produto' => $id_cp);

            $res = $this->Crud_model->Delete('comanda_produto', $dataPar);

            if ($res) {
                echo json_encode(array('result' => 'Sucesso'), JSON_UNESCAPED_UNICODE);
                return;
            } else {
                echo json_encode(array('result' => 'Erro'), JSON_UNESCAPED_UNICODE);
                return;
            }

        }

        echo json_encode(array('result' => 'Você não tem acesso a esta função!'), JSON_UNESCAPED_UNICODE);
        $this->output->set_status_header('401');
    }

}