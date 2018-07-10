<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Util extends CI_Controller {

	public function GetCategoriasProdutos(){

		$categorias = $this->Crud_model->Query("SELECT * FROM categoria_produto WHERE pizza = 1");
		
		if ($categorias) {
			$json = json_encode($categorias,JSON_UNESCAPED_UNICODE);
			echo $json;
			return;
		}
		
		$this->output->set_status_header('500');
	}

	public function GetTabelaCategoria(){

		$id = $this->uri->segment(4);
		
		if ($id > 0):

			$sql = "SELECT t.nome_tabela
					FROM tabela_preco t
					INNER JOIN tabela_produto tp ON (tp.id_tabela = t.id_tabela)
					INNER JOIN produto p ON (tp.id_produto = p.id_produto)
					INNER JOIN categoria_produto c ON (c.id_categoria = p.id_categoria)
					WHERE p.fg_ativo = 1 AND p.id_categoria = $id 
					GROUP BY t.id_tabela ORDER BY t.id_tabela";

			$tabelas = $this->Crud_model->Query($sql);
			
			if ($tabelas) {
				$json = json_encode($tabelas,JSON_UNESCAPED_UNICODE);
				echo $json;
				return;
			}

			$this->output->set_status_header('200');
			return;

		endif;
		
		$this->output->set_status_header('500');
	}

}