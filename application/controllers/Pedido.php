<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Pedido extends CI_Controller {

	public function index(){

		//sql busca
		$sql = 'SELECT c.id_comanda, c.status, DATE_FORMAT(c.data_comanda, "%d-%m") as data_comanda, DATE_FORMAT(c.data_comanda, "%h:%i") as hora_comanda, c.ref_comanda, c.tipo_comanda, c.mesa as nome_mesa, cl.nome_cliente 
        FROM comanda c 
        LEFT JOIN cliente cl ON (cl.id_cliente = c.id_cliente_viagem)
        where c.status = 1 and 1=1 and 1=1 order by c.data_comanda desc';

		$data['comandas'] = $this->Crud_model->Query($sql);
        
        //$sql = "SELECT count(*) as qtd FROM contato where $visualizado";
		//$data['qtd'] = $this->Crud_model->Query($sql);

		$menu['id_page'] = 2;
		$header['title'] = 'Dash | Pedido';
		$header['page'] = '1';

        $this->load->view('dashboard/template/commons/header',$header);
        $this->load->view('dashboard/pedido/home',$data);
		$this->load->view('dashboard/template/commons/footer');
	}

	public function PedidosComanda(){

		$comanda = $this->uri->segment(4);

		if ($comanda > 0):
			
			$sql = "SELECT cp.id_comanda_produto, cat.nome_categoria, p.nome_produto, cp.quantidade, tp.nome_tabela, cp.status_pedido, DATE_FORMAT(cp.data_pedido, '%h:%i') as horas, cp.observacao
			FROM comanda_produto cp
			INNER JOIN comanda c ON (c.id_comanda = cp.id_comanda)
			INNER JOIN produto p ON (p.id_produto = cp.id_produto)
			INNER JOIN categoria_produto cat ON (cat.id_categoria = p.id_categoria)
			INNER JOIN tabela_produto t ON (t.id_tabela_produto = cp.id_tabela_produto)
			INNER JOIN tabela_preco tp ON (tp.id_tabela = t.id_tabela)
			WHERE cp.fg_ativo = 1 AND cp.id_comanda = $comanda AND cp.gerar_pedido = 1";

			$res = $this->Crud_model->Query($sql);
			
			if ($res):

				$json = [];
				$observacao = [];
				foreach ($res as $obs) {
					$obs = (array) $obs;
					if ($obs["observacao"] == "" || $obs["observacao"] == null) {
						$json[] = array_merge($obs,array('observacoes' => []));
					}else{
						$observacao = explode("||", $obs["observacao"]);
						$json[] = array_merge($obs,array('observacoes' => $observacao));
					}
				}
				
				$json = json_encode($json,JSON_UNESCAPED_UNICODE);
				echo $json;
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}

	public function PedidosAbertos(){
		
		$sql = "SELECT cp.id_comanda_produto, c.mesa, cat.nome_categoria, p.nome_produto, cp.quantidade, tp.nome_tabela, cp.status_pedido, DATE_FORMAT(cp.data_pedido, '%h:%i') as horas, cp.observacao
			FROM comanda_produto cp
			INNER JOIN comanda c ON (c.id_comanda = cp.id_comanda)
			INNER JOIN produto p ON (p.id_produto = cp.id_produto)
			INNER JOIN categoria_produto cat ON (cat.id_categoria = p.id_categoria)
			INNER JOIN tabela_produto t ON (t.id_tabela_produto = cp.id_tabela_produto)
			INNER JOIN tabela_preco tp ON (tp.id_tabela = t.id_tabela)
			WHERE cp.fg_ativo = 1 AND cp.status_pedido = 1 AND cp.gerar_pedido = 1 ORDER BY cp.data_pedido asc";

		$res = $this->Crud_model->Query($sql);
		
		if ($res):

			$json = [];
			$observacao = [];
			foreach ($res as $obs) {
				$obs = (array) $obs;
				if ($obs["observacao"] == "" || $obs["observacao"] == null) {
					$json[] = array_merge($obs,array('observacoes' => []));
				}else{
					$observacao = explode("||", $obs["observacao"]);
					$json[] = array_merge($obs,array('observacoes' => $observacao));
				}
			}
			
			$json = json_encode($json,JSON_UNESCAPED_UNICODE);
			echo $json;
			return;
		endif;
	}

	public function finalizarPedido(){

		$pedido = $this->uri->segment(4);

		if ($pedido > 0):
			
			$res = $this->Crud_model->Update('comanda_produto',array('status_pedido' => 2),array('id_comanda_produto' => $pedido));
			
			if ($res):
				$this->output->set_status_header('200');	
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;

	}

}