jQuery(document).ready(function(){
		
	jQuery('#inserir').submit(function(){
			
		var dadosajax = {
					'nome' : $("#nome").val(),
					'user' : $("#user").val(),
					'senha' : $("#senha").val(),
					'tipo_user' : $("#permissao").val()
				};
		
		pageurl = base_urla + 'admin/cadastro-usuario';
			
			jQuery.ajax({
				type: "POST",
				url: pageurl,
				data: dadosajax,
				success: function(result)
				{
					console.log($.trim(result));
					//se foi inserido com sucesso
					if ($.trim(result) == '1') {
						swal({
							title: "Sucesso!",
						  	text: "Usuário inserido com sucesso!",
						  	type: "success",
						  	closeOnConfirm: false
						},
						function(){
						  location.reload();
						});

					}else{
						swal("Erro!","Erro desconhecido","error");
					}
				},
				error: function(result)
				{	
					var res = result.readyState;
					console.log(res);
					if (res == 4){
						swal("Erro!","Username já cadastrado! Verifique e tente Novamente","error");
					}else{
						swal("Erro!","Erro ao enviar requisão ao servidor. Tente Novamente!","error");
					}
				}

			});
			
			return false;
	});

	//Editar usuario
	jQuery('#editar').submit(function(){
			
		var dadosajax = {
			'id_usuario' : $("#idUser").val(), 
			'nome' : $("#editarNome").val(),
			'user' : $("#editarUser").val(),
			'id_tipo_usuario' : $("#editarPermissao").val()
		};
		
		pageurl = base_urla + 'admin/editar-usuario';
			
			jQuery.ajax({
				type: "POST",
				url: pageurl,
				data: dadosajax,
				success: function(result)
				{
					console.log($.trim(result));
					//se foi inserido com sucesso
					if ($.trim(result) == '1') {
						swal({
							title: "Sucesso!",
						  	text: "Usuário editado com sucesso!",
						  	type: "success",
						  	closeOnConfirm: false
						},
						function(){
						  location.reload();
						});

					}else{
						swal("Erro!","Erro desconhecido","error");
					}
				},
				error: function(result)
				{	
					var res = result.readyState;
					console.log(res);
					if (res == 4){
						swal("Erro!","Username já cadastrado! Verifique e tente Novamente","error");
					}else{
						swal("Erro!","Erro ao enviar requisão ao servidor. Tente Novamente!","error");
					}
				}

			});
			
			return false;
	});
});
// Funcao para deletar o usuario
function deletarItem() {
	swal({
	  title: "Tem certeza?",
	  text: "Ao excluir este usuário ele não poderá mais logar no sistema!",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: "Sim, Excluir!",
	  closeOnConfirm: false
	},
	function(){
	  	var dadosDeletar = {
			'id' : $("#idUser").val()
		};
		
		pageurl = base_urla + 'admin/remover-usuario';
			
			jQuery.ajax({
				type: "GET",
				url: pageurl,
				data: dadosDeletar,
				success: function(result)
				{
					console.log($.trim(result));
					//se foi inserido com sucesso
					if ($.trim(result) == '1') {
						swal({
							title: "Sucesso!",
						  	text: "Usuário deletado com sucesso!",
						  	type: "success",
						  	closeOnConfirm: false
						},
						function(){
						  location.reload();
						});

					}else{
						swal("Erro!","Erro desconhecido","error");
					}
				},
				error: function(result)
				{	
					swal("Erro!","Erro ao enviar requisão ao servidor. Tente Novamente!","error");
				}

			});

	  	swal("Excluido!", "Este usuário foi deletado com sucesso!", "success");
	  	$('#edicao').css("display","none");
	});
}


function editarUsuario(id,user,nome,tipo) {
	$('#idUser').val(id);
	$('#editarNome').val(nome);
	$('#editarUser').val(user);
	$("#editarPermissao").val(tipo).change();
	$('#edicao').css("display","block");

}

function erro() {
	swal({
  title: "Enviando Dados!",
  text: "Enviando Dados",
  imageUrl: "images/thumbs-up.jpg",
  closeOnConfirm: false,
  showConfirmButton: false,
  showLoaderOnConfirm: true,
  timer: 2000
},
function(){
  setTimeout(function(){
    swal("Sucesso!","Dados Inseridos com sucesso!!","success");
  }, 2000);
});
}
	 