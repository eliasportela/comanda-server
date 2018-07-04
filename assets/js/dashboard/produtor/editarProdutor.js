jQuery(document).ready(function(){

	//Buscando Produtor
	if ($("#id_produtor").val() != undefined) {
		IDPRODUTOR = $("#id_produtor").val();
		getProdutorID(IDPRODUTOR);
	}
	
	jQuery('#editarProdutor').submit(function(){
		
		if ($("#selectCidades").val() == 0) {
			swal("","Selecione a cidade do produtor","warning");
		
		}else{

			var dadosajax = new FormData(this);
			pageurl = base_urla + 'admin/api/produtor/editar/' + IDPRODUTOR;

			request("Salvando as alterações");
			$.ajax({
				url: pageurl,
				type: 'POST',
				data:  dadosajax,
				mimeType:"multipart/form-data",
				contentType: false,
				cache: false,
				processData:false,
				success: function(data, textStatus, jqXHR)
				{	
					requestSuccess();
					swal({
						title: '',text: 'Dados atualizados com sucesso!!',type: 'success'
					},function(){
						location.reload();
					});
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					console.log(jqXHR);
					requestSuccess();
				}          
			});
		}

		return false;
	});

});

var IDPRODUTOR = "";

function getProdutorID(id){
	
	var url = base_urla + 'admin/api/produtor/id/'+id;
	var data = null;
	
	$.get(url, function(res) {
		
		if (res) {
			data = JSON.parse(res);
			data = data[0];
			$("#nome_produtor").val(data.nome_produtor);
			$("#tipo_pessoa").val(data.id_tipo_pessoa);
			$("#cpf_cnpj").val(data.cpf_cnpj);
			$("#rg_inscricao_estadual").val(data.rg_inscricao_estadual);
			$("#data_nascimento").val(data.data_nascimento);
			$("#escolaridade").val(data.escolaridade);
			$("#membros_familia").val(data.membros_familia);
			$("#email").val(data.email);
			$("#telefone").val(data.telefone);
			$("#endereco").val(data.endereco);
			$("#numero").val(data.numero);
			$("#complemento").val(data.complemento);
			$("#cep").val(data.cep);
			$("#bairro").val(data.bairro);
			$("#selectEstados").val(data.id_estado);
			$("#certificados").val(data.certificados);
		}else{
			swal("","Erro interno, por favor recarregue a página","error");
		}
	})
	.done(function(){
    	getCidades('selectEstados','selectCidades',data.id_cidade);
    	$("#id").val(data.id_estado);
    	selectTipoPessoa();
    	//imgs
    	if (data.foto_produtor != "") {
    		$("#image-foto-bd").attr("src",base_urla + 'uploads/docs/'+IDPRODUTOR+'/'+data.foto_produtor);
    		$("#image-foto-bd").css("display","block");
    	}
    	if (data.comprovante_bancario != "") {
    		$("#view-comprovante").attr("href",base_urla + 'uploads/docs/'+IDPRODUTOR+'/'+data.comprovante_bancario);
    		$("#image-comprovante-bd").css("display","block");
    	}
    });
}

function deletarProdutorId(id) {
	swal({
		title: "Você tem certeza?",
		text: "Todos os dados associados como (propriedade, safras, imagens e documentos) serão excluídos e não será possível recuperar posteriomente",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: "Sim, quero remover",
		closeOnConfirm: true,
		html: false
	}, function(){
		url = base_urla + 'admin/api/produtor/remover/' + id;
		$.get(url).done(function(){
			window.location.href = base_urla +"admin/produtor";
		});
	});
}