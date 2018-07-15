<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Pedido extends CI_Controller {

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

}