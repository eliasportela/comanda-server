<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Pedido extends CI_Controller {

	public function index(){

		//sql busca
		$menu['id_page'] = 2;
		$header['title'] = 'Dash | Pedido';
		$header['page'] = '1';

        $this->load->view('dashboard/template/commons/header',$header);
        $this->load->view('dashboard/pedido/home');
		$this->load->view('dashboard/template/commons/footer');
	}

    public function GetPedidos(){

        $pagina = $this->uri->segment(3);
        $chave = $this->uri->segment(4);
        $nivel_acesso = 1;

        $pagination = "LIMIT 10 OFFSET ".($pagina - 1) * 100;
        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {

            $where_clause = "";
            $order_clause = "";

            //Status do Pedido
            if ($this->input->get('status') != null) {
                $status = (int) $this->input->get('status');
                $where_clause .= "AND cp.status_pedido =".$status;
            }

            //Id da comanda
            if ($this->input->get('comanda') != null) {
                $id_comanda = (int) $this->input->get('comanda');
                $where_clause .= " AND cp.id_comanda =" . $id_comanda;
            }

            //Ordenacao por data
            if ($this->input->get('ordenar') != null) {
                $order = ($this->input->get('ordenar') === 'true');
                if ($order) {
                    $order_clause = "ORDER BY cp.data_pedido desc";
                } else {
                    $order_clause = "ORDER BY cp.data_pedido asc";
                }
            }

            $sql = "SELECT cp.id_comanda_produto, c.mesa, cat.id_categoria, cat.nome_categoria, GROUP_CONCAT(p.nome_produto SEPARATOR '||') as nome_produto, cp.quantidade, t.id_tabela, t.nome_tabela, cp.observacao, DATE_FORMAT(cp.data_pedido, '%h:%i') as horas, cp.status_pedido
			FROM comanda_produto cp 
			INNER JOIN comanda c ON (c.id_comanda = cp.id_comanda)
			INNER JOIN cp_produtos cpp ON (cp.id_comanda_produto = cpp.id_cp)
            INNER JOIN produto p ON (p.id_produto = cpp.id_produto)
			INNER JOIN categoria_produto cat ON (cat.id_categoria = p.id_categoria)
            INNER JOIN tabela_preco tp ON (tp.id_tabela_preco = cp.id_tabela_preco)
			INNER JOIN tabela_produto t ON (t.id_tabela = tp.id_tabela)
			WHERE cp.fg_ativo = 1 AND p.ingrediente != 1 AND p.gerar_pedido = 1 $where_clause
            GROUP BY cp.id_comanda_produto $order_clause";

            //die(var_dump($sql));

            $res = $this->Crud_model->Query($sql);

            if ($res) {
                $json = [];
                $observacao = [];
                foreach ($res as $obs) {
                    $obs = (array)$obs;
                    if ($obs["observacao"] == "" || $obs["observacao"] == null) {
                        $json[] = array_merge($obs, array('observacoes' => []));
                    } else {
                        $observacao = explode("||", $obs["observacao"]);
                        $json[] = array_merge($obs, array('observacoes' => $observacao));
                    }
                }

                $json = json_encode($json, JSON_UNESCAPED_UNICODE);
                echo $json;
                return;

            } else {
                $this->output->set_status_header('204');
                return;
            }

        }

        $this->output->set_status_header('401');

    }

	public function EditPedido(){

	    $chave = $this->uri->segment(4);
        $nivel_acesso = 2;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {

            $data_model = array();

            // Id da comanda
            if($this->input->post("id_pedido") != null) {
                $id_pedido = (int) $this->input->post("id_pedido");
            } else {
                $this->output->set_status_header('400');
                return;
            }

            // Status do pedido
            if($this->input->post("status") != null) {
                $status = (int) $this->input->post("status");
                $data_model = array_merge($data_model,array('status_pedido' => $status));
            }

            // Cancelar/Ativar Pedido
            if($this->input->post("cancelado") != null) {
                $cancelado = (int) $this->input->post("cancelado");
                $data_model = array_merge($data_model,array('fg_ativo' => $cancelado));
            }

            if ($data_model) {

                $res = $this->Crud_model->Update('comanda_produto', $data_model, array('id_comanda_produto' => $id_pedido));
                if ($res) {
                    $this->output->set_status_header('200');
                    return;
                }

            } else {
                $this->output->set_status_header('400');
                return;
            }


        }

        $this->output->set_status_header('401');

	}

}