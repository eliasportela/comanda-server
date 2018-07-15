<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Cardapio extends CI_Controller {

	public function ListarCardapio(){

		$categoria = $this->uri->segment(4);

		if ($categoria > 0):
			
			$sql = "SELECT p.id_produto, p.nome_produto, p.ref_produto
			FROM produto p
			INNER JOIN categoria_produto c ON (c.id_categoria = p.id_categoria)
			WHERE p.fg_ativo = 1 AND c.id_categoria = $categoria";

			$produtos = $this->Crud_model->Query($sql);
			
			if ($produtos):

				$json = [];
				$itens = [];
				
				foreach ($produtos as $item) {

					$item = (array) $item;
					$id = $item['id_produto'];
					$sql = "SELECT p.nome_produto FROM item_produto ip 
					INNER JOIN produto p ON (p.id_produto = ip.id_produto_item) 
					WHERE ip.id_produto = $id";

					$res = $this->Crud_model->Query($sql);

					if($res){
						$res = (array) $res;
						$is = "";
						foreach ($res as $i) {
							$is .= $i->nome_produto.", ";
						}
						$is = substr($is, 0, -2);
						$json[] = array_merge($item,array('ingredientes' => $is));
					}
				}

				$json2 = [];
				foreach ($json as $produto) {

					$id = $produto["id_produto"];

					$sql = "SELECT t.nome_tabela, tp.valor
					FROM tabela_produto tp
					INNER JOIN produto p ON (p.id_produto = tp.id_produto)
					INNER JOIN tabela_preco t ON (t.id_tabela = tp.id_tabela)
					WHERE p.fg_ativo = 1 AND p.id_categoria != 1 AND p.id_produto = $id";
					
					$res = $this->Crud_model->Query($sql);
					if ($res) {
						$json2[] = array_merge($produto,array('valores' => $res));
					}else {
						$json2[] = array_merge($produto,array('valores' => ""));
					}
				}

				$json = json_encode($json2,JSON_UNESCAPED_UNICODE);
				echo $json;
				return;
			endif;
		else:
			$this->output->set_status_header('500');
		endif;
	}

}