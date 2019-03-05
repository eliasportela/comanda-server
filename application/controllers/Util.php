<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Util extends CI_Controller {

	public function GetCategoriasProdutos(){

        $chave = $this->uri->segment(3);
        $nivel_acesso = 0;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {
            $categorias = $this->Crud_model->Query("SELECT * FROM categoria_produto WHERE fg_ativo = 1");

            if ($categorias) {
                $json = json_encode($categorias, JSON_UNESCAPED_UNICODE);
                echo $json;
                return;
            }
        }
		
		$this->output->set_status_header('401');
	}

    public function GetIngredientesProduto(){

        $chave = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $nivel_acesso = 0;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {
            $ingredientes = $this->Crud_model->Query("
                SELECT id_produto, nome_produto, ref_produto 
                FROM produto p
                INNER JOIN categoria_produto c ON p.id_categoria = c.id_categoria
                WHERE p.fg_ativo = 1 AND p.ingrediente = true 
                AND c.id_ingrediente = (
                    SELECT c_aux.id_ingrediente 
                        FROM produto p_aux
                        INNER JOIN categoria_produto c_aux ON p_aux.id_categoria = c_aux.id_categoria
                        WHERE p_aux.id_produto = $id
                )
                ORDER BY p.nome_produto ASC");

            if ($ingredientes) {
                $json = json_encode($ingredientes, JSON_UNESCAPED_UNICODE);
                echo $json;
                return;
            }
        }

        $this->output->set_status_header('401');
    }

    public function GetTabelas(){

        $chave = $this->uri->segment(3);
        $nivel_acesso = 0;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave,$nivel_acesso);

        if ($acesso_aprovado) {
            $tabelas = $this->Crud_model->ReadAll('tabela_produto');

            if ($tabelas) {
                $json = json_encode($tabelas, JSON_UNESCAPED_UNICODE);
                echo $json;
                return;
            }
        }

        $this->output->set_status_header('401');
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