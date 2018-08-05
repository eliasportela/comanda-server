<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['form_validation', 'upload', 'image_lib']);
    }

    public function Login() {

        $this->form_validation->set_rules('email', 'E-mail', 'required|min_length[4]|alpha_dash|trim');
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
        } else {
            $dataLogin = $this->input->post();
            $res = $this->User_model->Login($dataLogin);

            if ($res) {

                foreach ($res as $result) {


                    if (password_verify($dataLogin['senha'], $result->senha)) {

                        $data['error'] = null;
                        $this->session->set_userdata('logged', true);
                        $this->session->set_userdata('id_usuario', $result->id_usuario);
                        $this->session->set_userdata('nome_usuario', $result->nome);
                        $this->session->set_userdata('email', $result->email);
                        $this->session->set_userdata('administrativo', $result->administrativo);

                        redirect(base_url('admin'));
                    } else {
                        $data['error'] = 2; // Senha incorreta
                    }
                }

            } else {
                $data['error'] = 1; //Usuario Incorreto
            }
        }

        if ($this->session->userdata('logged')) {
            redirect(base_url('admin'));
        } else {
            $header['title'] = "Dashboard | Login";
            $header['tela_login'] = true;
            $this->load->view('dashboard/template/commons/header', $header);
            $this->load->view('dashboard/login', $data);
            $this->load->view('dashboard/template/commons/footer');
        }

    }

    public function Logout()
    {
        $this->session->unset_userdata('logged');
        $this->session->unset_userdata('id_usuario');
        $this->session->unset_userdata('administrativo');
        redirect(base_url('login'));
    }

    public function Register()
    {

        $nivel_user = 1; //Nivel requirido para visualizar a pagina

        if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)) {

            $dataRegister = $_POST;

            if ((isset($dataRegister['nome'])) and (isset($dataRegister['email'])) and (isset($dataRegister['senha'])) and (isset($dataRegister['administrativo']))) {
                $dataRegister = $this->input->post();
                $dataModel = array(
                    'nome' => $dataRegister['nome'],
                    'email' => $dataRegister['email'],
                    'senha' => $dataRegister['senha'],
                    'administrativo' => $dataRegister['administrativo']);
                $res = $this->User_model->Save($dataModel);
                if ($res) {
                    // retorna uma confirmação
                    echo "1";
                }
            }

        } else {
            //erro de permissao
            echo "4";
        }

    }

    public function UpdatePassw()
    {
        $data['success'] = null;
        $data['error'] = null;
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]|trim');
        $this->form_validation->set_rules('novaSenha', 'Nova Senha', 'required|min_length[6]|trim');
        $this->form_validation->set_rules('confSenha', 'Confirmar Senha', 'required|min_length[6]|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
        } else {
            $dataRegister = $this->input->post();
            $dataUser = array('id_usuario' => $this->session->userdata('id_usuario'));
            $res = $this->User_model->Validar($dataUser);
            //die(var_dump($res));
            foreach ($res as $result) {
                if (password_verify($dataRegister['senha'], $result->senha)) {
                    if ($dataRegister['novaSenha'] == $dataRegister['confSenha']) {
                        $dataModel = array(
                            'senha' => $dataRegister['novaSenha']);

                        #die(var_dump($dataModel));
                        $this->User_model->UpdateSenha($dataModel, $dataUser);
                        $data['success'] = "Senha alterada com sucesso!";
                        $data['error'] = null;
                    } else {
                        $data['error'] = "As senhas não correspondem";
                    }
                } else {
                    $data['error'] = "Senha atual incorreta.";
                }
            }
        }

        $data['user'] = $this->User_model->GetUser($this->session->userdata('id_usuario'));
        $header['title'] = "Lista CCB | Alterar Senha";
        $this->load->view('adm/commons/header', $header);
        $this->load->view('adm/user/alterar-senha', $data);
        $this->load->view('adm/commons/footer');
    }

    public function ListarUser() {


        $nivel_user = 1; //Nivel requirido para visualizar a pagina

        if (($this->session->userdata('logged')) and ($this->session->userdata('id_tipo_usuario') <= $nivel_user)) {

            #usuarios
            $sql = "SELECT u.id_usuario, u.nome, u.user, u.id_tipo_usuario, t.ds_tipo_usuario
		FROM usuario u 
		INNER JOIN tipo_usuario t ON (t.id_tipo_usuario = u.id_tipo_usuario)
		WHERE u.fg_ativo = 1;";
            //consultando
            $data['users'] = $this->Crud_model->Query($sql);

            //die(var_dump($data['users']));
            $header['title'] = "Dash | Usuarios";
            $menu['id_page'] = 2;
            $this->load->view('dashboard/template/commons/header', $header);
            $this->load->view('dashboard/template/commons/menu', $menu);
            $this->load->view('dashboard/user/listar-user', $data);
            $this->load->view('dashboard/template/commons/footer');

        } else {
            redirect(base_url('login'));
        }
    }

    public function EditarUser() {


        $nivel_user = 1; //Nivel requirido para visualizar a pagina

        if (($this->session->userdata('logged')) and ($this->session->userdata('id_tipo_usuario') <= $nivel_user)) {

            $dataRegister = $this->input->post();
            $par = array('id_usuario' => $dataRegister['id_usuario']);
            $dataModel = array(
                'nome' => $dataRegister['nome'],
                'user' => $dataRegister['user'],
                'id_tipo_usuario' => $dataRegister['id_tipo_usuario']);
            $res = $this->Crud_model->Update('usuario', $dataModel, $par);
            //die(var_dump($res));
            if ($res) {
                echo "1";
            } else {
                echo "2";
            }
        } else {// Se não estiver logado redireciona para tela de login..
            echo "3";
        }

        //Fim da função
    }

    public function RemoverUser() {


        $nivel_user = 1; //Nivel requirido para visualizar a pagina

        if (($this->session->userdata('logged')) and ($this->session->userdata('id_tu') <= $nivel_user)) {

            //Se a url nao tiver o parametro de consulta
            if ($this->input->get('id')) {
                // Id recebe o paramentro da url
                $id = (int)$this->input->get('id');
                $dataModel = array('fg_ativo' => 0);
                $par = array('id_usuario' => $id);
                $result = $this->Crud_model->Update('usuario', $dataModel, $par);

                //Se ocorrer a remocao
                if ($result) {
                    echo "1";
                } else {
                    echo "2";
                }
            }

        } else {
            echo "4";
        }
    }

    public function getAutenticacao() {

        $dataLogin = $this->input->post();
        $usuario = isset($dataLogin['usuario']) ? $dataLogin['usuario'] : false;
        $senha = isset($dataLogin['senha']) ? $dataLogin['senha'] : false;

        $res = $this->User_model->LoginToken($usuario);
        $json = [];

        if ($res) {

            foreach ($res as $result) {

                $id_usuario = $result->id_usuario;
                $datetime = new DateTime();
                $data_agora = $datetime->format('Y-m-d H:i:s');

                if (password_verify($senha, $result->senha)) {

                    $sql = "SELECT chave FROM token WHERE id_usuario = $id_usuario AND DATE(data_expiracao) > '".$data_agora."'";
                    $res = $this->Crud_model->Query($sql);

                    if ($res) {
                        $chave = $res[0]->chave;

                    } else {

                        $chave = md5(uniqid(rand(), true));
                        $datetime->modify('+1 day');
                        $data_expiracao = $datetime->format('Y-m-d H:i:s');

                        $data_model = array(
                            'data_acesso' => $data_agora,
                            'data_expiracao' => $data_expiracao,
                            'chave' => $chave,
                            'id_usuario' => $id_usuario
                        );

                        $res = $this->Crud_model->Insert('token', $data_model);
                    }

                    $json = array_merge($json,array('result' => 'Autorizado','chave' => $chave));


                } else {
                    //senha incorreta
                    $json = array_merge($json,array('result' => 'Senha Incorreta','chave' => null));
                }
            }

        }

        //Email invalido
        if (!$json) {
            $json = array_merge($json,array('result' => 'Usuário inválido','chave' => null));
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);

    }


}
