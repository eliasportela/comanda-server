<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library(['form_validation','upload','image_lib']);
	} 
	
public function Login()
  
  {
  	
  	$this->form_validation->set_rules('email','E-mail','required|min_length[4]|alpha_dash|trim');
    $this->form_validation->set_rules('senha','Senha','required|min_length[6]|trim');
    if($this->form_validation->run() == FALSE)
   	{
      $data['error'] = validation_errors();
    }
    else
    {
      $dataLogin = $this->input->post();
      $res = $this->User_model->Login($dataLogin);

      if($res)
      {

        foreach($res as $result)
        {
          if (password_verify($dataLogin['senha'], $result->senha))
    		
          {

            $data['error'] = null;
            $this->session->set_userdata('logged',true);
            $this->session->set_userdata('id_usuario',$result->id_usuario);
            $this->session->set_userdata('nome_usuario',$result->nome);
            $this->session->set_userdata('email',$result->email);
            $this->session->set_userdata('administrativo',$result->administrativo);
            redirect(base_url('admin'));
          }
          else
          {
          	$data['error'] = 2; // Senha incorreta
          }
        }

      }
      else
      {
        $data['error'] = 1; //Usuario Incorreto
      }
    }

    if ($this->session->userdata('logged')) {
    	redirect(base_url('admin'));
    }else{
	    $header['title'] = "Dashboard | Login";
	    $header['tela_login'] = true;
		$this->load->view('dashboard/template/commons/header',$header);
	    $this->load->view('dashboard/login',$data);
	    $this->load->view('dashboard/template/commons/footer');
	}
    
  }

	public function Logout() {
		$this->session->unset_userdata('logged');
		$this->session->unset_userdata('id_usuario');
		$this->session->unset_userdata('administrativo');
		redirect(base_url('login'));
	}

	public function Register() {

		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('administrativo') >= $nivel_user)){

			$dataRegister = $_POST;

			if((isset($dataRegister['nome'])) and (isset($dataRegister['email'])) and (isset($dataRegister['senha'])) and (isset($dataRegister['administrativo']))){
				$dataRegister = $this->input->post();
				$dataModel = array(
					'nome' => $dataRegister['nome'], 
					'email' => $dataRegister['email'],
					'senha' => $dataRegister['senha'],
					'administrativo' => $dataRegister['administrativo']);
					$res = $this->User_model->Save($dataModel);
				if($res){
					// retorna uma confirmação
					echo "1";
				}
			}
		
		}else{
			//erro de permissao
			echo "4";
		}

	}
	
	public function UpdatePassw() {
		$data['success'] = null;
		$data['error'] = null;
		$this->form_validation->set_rules('senha','Senha','required|min_length[6]|trim');
		$this->form_validation->set_rules('novaSenha','Nova Senha','required|min_length[6]|trim');
		$this->form_validation->set_rules('confSenha','Confirmar Senha','required|min_length[6]|trim');

		if($this->form_validation->run() == FALSE){
			$data['error'] = validation_errors();
		}else{
			$dataRegister = $this->input->post();
			$dataUser = array('id_usuario' => $this->session->userdata('id_usuario'));
			$res = $this->User_model->Validar($dataUser); 
			//die(var_dump($res));
			foreach($res as $result)
	        {
	          	if (password_verify($dataRegister['senha'], $result->senha))
	    
	   	       {
		          	if ($dataRegister['novaSenha'] == $dataRegister['confSenha']) {
		          		$dataModel = array(
							'senha' => $dataRegister['novaSenha']);
							
						#die(var_dump($dataModel));
						$this->User_model->UpdateSenha($dataModel,$dataUser);
						$data['success'] = "Senha alterada com sucesso!";
						$data['error'] = null;
					}else{
					$data['error'] = "As senhas não correspondem";
					}
				}
	        else {
	          	$data['error'] = "Senha atual incorreta.";
	        }
	    }		
	}

		$data['user'] = $this->User_model->GetUser($this->session->userdata('id_usuario'));
		$header['title'] = "Lista CCB | Alterar Senha";
		$this->load->view('adm/commons/header',$header);
	    $this->load->view('adm/user/alterar-senha',$data);
	    $this->load->view('adm/commons/footer');
	}

	public function ListarUser(){
		
		
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
		$this->load->view('dashboard/template/commons/header',$header);
		$this->load->view('dashboard/template/commons/menu',$menu);
		$this->load->view('dashboard/user/listar-user',$data);
		$this->load->view('dashboard/template/commons/footer');
		
			}else{
			redirect(base_url('login'));
		}
	}

	public function EditarUser(){
		
		
		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('id_tipo_usuario') <= $nivel_user)) {

				$dataRegister = $this->input->post();
				$par = array('id_usuario' => $dataRegister['id_usuario']);
				$dataModel = array(
					'nome' => $dataRegister['nome'],
					'user' => $dataRegister['user'], 
					'id_tipo_usuario' => $dataRegister['id_tipo_usuario']);
				$res = $this->Crud_model->Update('usuario',$dataModel,$par);
				//die(var_dump($res));
				if ($res) {
					echo "1";
				}else{
					echo "2";
				}
		}else{// Se não estiver logado redireciona para tela de login..
			echo "3";
		}

		//Fim da função
	}

	public function RemoverUser(){
		
		
		$nivel_user = 1; //Nivel requirido para visualizar a pagina

		if (($this->session->userdata('logged')) and ($this->session->userdata('id_tu') <= $nivel_user)) {
			
			//Se a url nao tiver o parametro de consulta
			if ($this->input->get('id')) {
				// Id recebe o paramentro da url
				$id = (int) $this->input->get('id');
				$dataModel = array('fg_ativo' => 0);
				$par = array('id_usuario' => $id);
				$result = $this->Crud_model->Update('usuario',$dataModel,$par);

				//Se ocorrer a remocao
				if ($result) {
					echo "1";
				}else{
					echo "2";
				}
			}

		}else{
			echo "4";
		}
	}

	public function EditarMyUser()
	{
		$nivel_user = 2;
		if (($this->session->userdata('logged')) and ($this->session->userdata('id_tipo_usuario') <= $nivel_user)) {
			
			$data['success'] = null;
			$data['error'] = null;
			
			$this->form_validation->set_rules('nome','Nome','required|min_length[4]|trim');
			$this->form_validation->set_rules('user','User','required|min_length[4]|alpha_dash|trim');
    		$this->form_validation->set_rules('cidade','Cidade','required|is_natural_no_zero|trim',array('is_natural_no_zero' => 'Selecione uma cidade'));
    		
    			
    		if($this->form_validation->run() == FALSE){
				$data['error'] = validation_errors();
				if ($data['error'] == NULL) {
					/* Se a validação do dados ainda nao ocorreu, entao o que retorna 
					no formulario é que conteudo do banco de dados*/
					$user = array('id_usuario' => $this->session->userdata('id_usuario'));
					$user = $this->Crud_model->Read('usuario',$user);
					$data['dataRegister'] = array('nome' => $user->nome, 'user' => $user->user, 'cidade' => $user->id_cidade);
    		
				}
				else{

					$data['dataRegister'] = $this->input->post();
					//die(var_dump($data['dataRegister']));
					/* Se ocorreu, os dados retorna para os campos, para o usuario nao precisar digitar 
					tudo novamente no formulario*/
				}
			}else{
				$dataRegister = $this->input->post();

				$dataModel = array(
					'nome' => $dataRegister['nome'], 
					'user' => $dataRegister['user'],
					'id_cidade' => $dataRegister['cidade']);

					$par = array('id_usuario' => $this->session->userdata('id_usuario'));
					$res = $this->Crud_model->Update('usuario',$dataModel,$par);

					$data['success'] = "Editado com sucesso";
					$user = array('id_usuario' => $this->session->userdata('id_usuario'));
					$user = $this->Crud_model->Read('usuario',$user);
					$data['dataRegister'] = array('nome' => $user->nome, 'user' => $user->user, 'cidade' => $user->id_cidade);
    		
					
			}

				$id = array('id_usuario' => $this->session->userdata('id_usuario'));
				$data['perfil'] = $this->Crud_model->Read('usuario',$id);
				$data['cidades'] = $this->Crud_model->ReadAll('cidade');
				$header['title'] = "Editar Profile";
				$this->load->view('adm/commons/header', $header);
				$this->load->view('adm/user/editar-my-user',$data);
				$this->load->view('adm/commons/footer');

		}else{
			redirect(base_url('login'));
		}	
	}

	public function Recortar()
	{
		//die(var_dump($_POST));
		// Configurações para o upload da imagem
        // Diretório para gravar a imagem
        $configUpload['upload_path']   = './uploads/';
        // Tipos de imagem permitidos
        $configUpload['allowed_types'] = 'jpg|png';
        // Usar nome de arquivo aleatório, ignorando o nome original do arquivo
        $configUpload['encrypt_name']  = TRUE;
 
        // Aplica as configurações para a library upload
        $this->upload->initialize($configUpload);
 
        // Verifica se o upload foi efetuado ou não
        // Em caso de erro carrega a home exibindo as mensagens
        // Em caso de sucesso faz o processo de recorte
        if ( ! $this->upload->do_upload('imagem'))
        {
            // Recupera as mensagens de erro e envia o usuário para a home
            $data= array('error' => $this->upload->display_errors());
			
			die(var_dump($data));	

        }
        else
        {
            // Recupera os dados da imagem
            $dadosImagem = $this->upload->data();
 
            // Calcula os tamanhos de ponto de corte e posição
            // de forma proporcional em relação ao tamanho da
            // imagem original
            if ($this->input->post()) {
            	# code...
            }
            $tamanhos = $this->CalculaPercetual($this->input->post());
 
            // Define as configurações para o recorte da imagem
            // Biblioteca a ser utilizada
            $configCrop['image_library'] = 'gd2';
            //Path da imagem a ser recortada
            $configCrop['source_image']  = $dadosImagem['full_path'];
            // Diretório onde a imagem recortada será gravada
            $configCrop['new_image']     = './assets/img/users/';
            // Proporção
            $configCrop['maintain_ratio']= FALSE;
            // Qualidade da imagem
            $configCrop['quality']             = 100;
            // Tamanho do recorte
            $configCrop['width']         = $tamanhos['wcrop'];
            $configCrop['height']        = $tamanhos['hcrop'];
            // Ponto de corte (eixos x e y)
            $configCrop['x_axis']        = $tamanhos['x'];
            $configCrop['y_axis']        = $tamanhos['y'];
 
            // Aplica as configurações para a library image_lib
            $this->image_lib->initialize($configCrop);
 
            // Verifica se o recorte foi efetuado ou não
            // Em caso de erro carrega a home exibindo as mensagens
            // Em caso de sucesso envia o usuário para a tela
            // de visualização do recorte
            if ( ! $this->image_lib->crop())
            {
                // Recupera as mensagens de erro e envia o usuário para a home
                $data = array('error' => $this->image_lib->display_errors());
                	die($data);	

            }
            else
            {
                // Define a URL da imagem gerada após o recorte
                $imagem = $dadosImagem['file_name'];
                $par = array('id_usuario' => $this->session->userdata('id_usuario'));
                $dataModel = array('img_perfil' => $imagem);
                $result = $this->Crud_model->Update('usuario',$dataModel,$par);
                if ($result) {
                	redirect(base_url('profile-editar'));
                }
            }
        }
	}

	 private function CalculaPercetual($dimensoes){
        // Verifica se a largura da imagem original é
        // maior que a da área de recorte, se for calcula o tamanho proporcional
        if($dimensoes['woriginal'] > $dimensoes['wvisualizacao']){
            $percentual = $dimensoes['woriginal'] / $dimensoes['wvisualizacao'];
 
            $dimensoes['x'] = round($dimensoes['x'] * $percentual);
            $dimensoes['y'] = round($dimensoes['y'] * $percentual);
            $dimensoes['wcrop'] = round($dimensoes['wcrop'] * $percentual);
            $dimensoes['hcrop'] = round($dimensoes['hcrop'] * $percentual);
        }
 
        // Retorna os valores a serem utilizados no processo de recorte da imagem
        return $dimensoes;
    }

}
