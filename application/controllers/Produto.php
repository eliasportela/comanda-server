<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Produto extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation', 'upload', 'image_lib']);
    }

    //Tela produto
    public function index()
    {

        $nivel_user = 1; //Nivel requirido para visualizar a pagina

        if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)) {

            $data["categoria_produto"] = $this->Crud_model->ReadAll("categoria_produto");
            $header['title'] = "Dash | Produto";
            $menu['id_page'] = 3;

            $this->load->view('dashboard/template/commons/header', $header);
            $this->load->view('dashboard/template/commons/menu', $menu);
            $this->load->view('dashboard/produto/home', $data);
            $this->load->view('dashboard/template/commons/footer');

        } else {
            redirect(base_url('login'));
        }
    }

    //Tela Cadastro produto
    public function Cadastro()
    {

        $nivel_user = 1; //Nivel requirido para visualizar a pagina

        if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)) {

            $data["categoria_produto"] = $this->Crud_model->ReadAll("categoria_produto");
            $data["tabela_ingrediente"] = $this->Crud_model->ReadAll("tabela_ingrediente");

            $header['title'] = "Dash | Cadastro produto";
            $data['title'] = "Cadastrar Produto";
            $data['idFormulario'] = "inserirProduto";
            $menu['id_page'] = 3;

            $data['produto'] = null;
            $data['editar'] = false;
            $data['coluna'] = "m3";
            $this->load->view('dashboard/template/commons/header', $header);
            $this->load->view('dashboard/template/commons/menu', $menu);
            $this->load->view('dashboard/produto/cadastro', $data);
            $this->load->view('dashboard/template/commons/footer');

        } else {
            redirect(base_url('login'));
        }

    }

    //Tela Edição produto
    public function Editar()
    {

        $nivel_user = 1; //Nivel requirido para visualizar a pagina

        if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)) {

            //categoria produto
            $data["categoria_produto"] = $this->Crud_model->ReadAll("categoria_produto");
            $data["tabela_ingrediente"] = $this->Crud_model->ReadAll("tabela_ingrediente");

            $header['title'] = "Dash | Editar Produto";
            $data['title'] = "Editar Produto";
            $data['idFormulario'] = "editarProduto";
            $menu['id_page'] = 3;

            $id_produto = $this->uri->segment(3);

            $sql = "SELECT p.id_produto, c.id_ingrediente 
                  FROM produto p
                  INNER JOIN categoria_produto c ON p.id_categoria = c.id_categoria
                  WHERE p.id_produto = $id_produto";

            $dataModel = $this->Crud_model->Query($sql);
            $dataModel = $dataModel[0];

            if ($dataModel) {

                $data['produto'] = $dataModel->id_produto;
                $data['editar'] = true;
                $data['coluna'] = "m4";
                $data['tabela'] = $this->Crud_model->ReadAll('tabela_produto');

                $sql = "SELECT id_produto, nome_produto 
                        FROM produto p
                        INNER JOIN categoria_produto c ON p.id_categoria = c.id_categoria
                        WHERE p.fg_ativo = 1 AND p.ingrediente = true AND c.id_ingrediente = $dataModel->id_ingrediente ORDER BY p.nome_produto ASC";
                $data['produtos'] = $this->Crud_model->Query($sql);

                $this->load->view('dashboard/template/commons/header', $header);
                $this->load->view('dashboard/template/commons/menu', $menu);
                $this->load->view('dashboard/produto/cadastro', $data);
                $this->load->view('dashboard/template/commons/footer');

            } else {
                redirect(base_url('admin/produto'));
            }


        } else {
            redirect(base_url('login'));
        }

    }

    //Desantivando produto
    public function Remove()
    {
        $dataId = (int)$this->uri->segment(4);
        if ($dataId > 0) {
            $this->Crud_model->Update('produto', array('fg_ativo' => 0), array('id_produto' => $dataId));
        }
    }

    public function getID()
    {

        $chave = $this->uri->segment(4);
        $nivel_acesso = 1;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado) {

            if ($this->input->get("produtos") != null) {
                $par = $this->input->get("produtos");
                $whereClause = "AND p.id_produto = " . $par;
            }

            $sql = "SELECT id_produto, nome_produto, ref_produto, id_categoria, gerar_pedido, p.ingrediente
			FROM produto p
			WHERE fg_ativo = 1 $whereClause";

            $res = $this->Crud_model->Query($sql);
            $res = $res[0];

            //die(var_dump($this->input->get()));

            if ($res) {
                $json = json_encode($res, JSON_UNESCAPED_UNICODE);

                $sql = "SELECT p.id_produto, p.nome_produto, p.ref_produto
                    FROM item_produto ip 
                    INNER JOIN produto p ON (p.id_produto = ip.id_produto_item) 
                    WHERE ip.id_produto = $par";

                $resItens = $this->Crud_model->Query($sql);

                $sql = "SELECT tp.id_tabela_preco, tp.id_tabela, t.nome_tabela, tp.valor
                    FROM tabela_preco tp
                    INNER JOIN produto p ON (p.id_produto = tp.id_produto)
                    INNER JOIN tabela_produto t ON (t.id_tabela = tp.id_tabela)
                    WHERE p.fg_ativo = 1 AND p.id_produto = $par";

                $resValores = $this->Crud_model->Query($sql);

                $itens = "null";
                $valores = "null";
                if ($resItens):
                    $itens = json_encode($resItens, JSON_UNESCAPED_UNICODE);
                endif;

                if ($resValores):
                    $valores = json_encode($resValores, JSON_UNESCAPED_UNICODE);
                endif;

                echo '{"produto":' . $json . ',"itens":' . $itens . ',"valores":' . $valores . '}';
                return;
            }
        }
    }

    public function Get()
    {
        $pagination = $this->uri->segment(3);
        $page = $pagination * 20 - 20;

        //Pesquisa
        $referencia = "1=1";
        $nome = "1=1";
        $categoria = "1=1";
        $ingrediente = "1=1";

        if ($this->input->get("referencia") != null) {
            $referencia = "p.ref_produto = '" . $this->input->get("referencia") . "'";
        }

        if ($this->input->get("categoria") != null && $this->input->get("categoria") != 0) {
            $categoria = "p.id_categoria = " . $this->input->get("categoria");
        }

        if ($this->input->get("nome") != null) {
            $nome = "p.nome_produto LIKE '%" . $this->input->get("nome") . "%'";
        }

        if ($this->input->get("ingrediente") != null) {
            $ingrediente = "p.ingrediente = " . $this->input->get("ingrediente");
        }

        $sqlCount = "SELECT COUNT(*) as qtd FROM produto p 
			INNER JOIN categoria_produto c ON (p.id_categoria = c.id_categoria)
			WHERE $nome and $referencia and $categoria and $ingrediente and p.fg_ativo = 1";

        $res1 = $this->Crud_model->Query($sqlCount);
        $qtd = 0;

        if ($res1[0]->qtd > 20):
            $qtd = round($res1[0]->qtd / 20) + 1;
        elseif ($res1[0]->qtd > 0):
            $qtd = 1;
        endif;

        if ($pagination > 0):
            $sql = "SELECT * FROM produto p 
			INNER JOIN categoria_produto c ON (p.id_categoria = c.id_categoria)
			WHERE $nome and $referencia and $categoria and $ingrediente and p.fg_ativo = 1
			LIMIT 20 OFFSET $page";
            $res = $this->Crud_model->Query($sql);
            if ($res):
                $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                echo '{"page":' . $pagination . ',"pages":' . $qtd . ',"result":' . $json . '}';
                return;
            endif;
        else:
            $this->output->set_status_header('500');
        endif;
    }

    //Inserindo registros
    public function Register()
    {
        $dataRegister = $this->input->post();
        if ($dataRegister AND $dataRegister['nome_produto'] != NULL):

            $nome_produto = trim($dataRegister['nome_produto']);
            $id_categoria = trim($dataRegister['id_categoria']);
            $ingrediente = trim($dataRegister['ingrediente']);
            $gerar_pedido = trim($dataRegister['gerar_pedido']);

            $dataModel = array(
                'nome_produto' => $nome_produto,
                'id_categoria' => $id_categoria,
                'ingrediente' => $ingrediente == 0 ? false : true,
                'gerar_pedido' => $gerar_pedido,
                'ref_produto' => rand(1, 1000000));

            $res = $this->Crud_model->InsertId('produto', $dataModel);

            if ($res):

                //Editando a referencia do produto
                $this->Crud_model->Update('produto', array('ref_produto' => 'R0' . $res), array('id_produto' => $res));

                echo $res;
                $this->output->set_status_header('200');
                return;
            endif;
        else:
            $this->output->set_status_header('400');
        endif;
    }

    public function Edit() {

        $dataRegister = $this->input->post();

        if ($dataRegister AND $dataRegister['nome_produto'] != NULL) {

            $id_produto = trim($dataRegister['id_produto']);
            $nome_produto = trim($dataRegister['nome_produto']);
            $id_categoria = trim($dataRegister['id_categoria']);
            $ingrediente = trim($dataRegister['ingrediente']);
            $gerar_pedido = trim($dataRegister['gerar_pedido']);
            $referencia = trim($dataRegister['ref_produto']);

            $dataModel = array(
                'nome_produto' => $nome_produto,
                'id_categoria' => $id_categoria,
                'gerar_pedido' => $gerar_pedido,
                'ingrediente' => $ingrediente == 0 ? false : true,
                'ref_produto' => $referencia);

            $res = $this->Crud_model->Update('produto', $dataModel, array('id_produto' => $id_produto));

            if ($res) {

                if (isset($dataRegister['produtos'])) {
                    $this->Crud_model->Delete('item_produto', array('id_produto' => $id_produto));
                    for ($i = 0; $i < count($dataRegister['produtos']); $i++) {
                        $produtoModel = array('id_produto' => $id_produto, 'id_produto_item' => $dataRegister['produtos'][$i]);
                        $this->Crud_model->Insert('item_produto', $produtoModel);
                    }
                }

                if (isset($dataRegister['precos'])) {
                    $this->Crud_model->Delete('tabela_preco', array('id_produto' => $id_produto));
                    for ($i = 0; $i < count($dataRegister['precos']); $i++) {
                        $tabelaModel = array('id_produto' => $id_produto,
                            'id_tabela' => $dataRegister['precos'][$i],
                            'valor' => $dataRegister['valores'][$i]);
                        $this->Crud_model->Insert('tabela_preco', $tabelaModel);
                    }
                }

                $this->output->set_status_header('200');
                return;

            } else {
                $this->output->set_status_header('400');
            }
        }

    }

    public function getProdutosTabelaPreco()
    {

        $pagina = $this->uri->segment(4);
        $chave = $this->uri->segment(5);
        $nivel_acesso = 1;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado) {

            $pagination = "LIMIT 100 OFFSET " . ($pagina - 1) * 100;

            $whereClause = "";
            if ($this->input->get("id_categoria") != null) {
                $par = (int)$this->input->get("id_categoria");
                $whereClause = "AND p.id_categoria = " . $par;
            }

            if ($this->input->get("ingrediente") != null) {
                $par = (int)$this->input->get("ingrediente");
                $whereClause .= " AND p.ingrediente = " . $par;
            }

            $sql = "SELECT p.id_produto, p.nome_produto, p.ref_produto, p.id_categoria, tp.id_tabela_preco, t.id_tabela, t.nome_tabela, p.gerar_pedido
			FROM produto p
			INNER JOIN tabela_preco tp ON (tp.id_produto = p.id_produto)
			INNER JOIN tabela_produto t ON (t.id_tabela = tp.id_tabela)
			INNER JOIN categoria_produto c ON (c.id_categoria = p.id_categoria)
			WHERE p.fg_ativo = 1 $whereClause order by p.id_produto, t.id_tabela $pagination";

            $res = $this->Crud_model->Query($sql);

            if ($res):
                $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                echo $json;
                return;
            endif;
        }

        $this->output->set_status_header('401');
    }

    public function GetProdutosItens()
    {

        $chave = $this->uri->segment(4);
        $nivel_acesso = 1;

        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado) {

            $whereClause = "";
            if ($this->input->get("produtos") != null) {
                $par = $this->input->get("produtos");

                $in = "(";
                foreach ($par as $p) {
                    $in .= $p . ",";
                }

                if (strlen($in) > 2) {
                    $in = substr($in, 0, -1) . ")";
                    $whereClause = "ip.id_produto in $in";
                }
            }
            $sql = "SELECT p.id_produto, p.nome_produto, p.ref_produto 
            FROM item_produto ip 
            INNER JOIN produto p ON (p.id_produto = ip.id_produto_item) 
            WHERE $whereClause
            group by p.id_produto";

            $resItens = $this->Crud_model->Query($sql);
            if ($resItens) {
                $itens = json_encode($resItens, JSON_UNESCAPED_UNICODE);
                echo $itens;
                return;
            } else {
                $this->output->set_status_header('204');
                return;
            }
        }

        $this->output->set_status_header('401');

    }

}