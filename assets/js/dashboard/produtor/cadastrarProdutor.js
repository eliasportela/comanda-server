jQuery(document).ready(function(){

	//Inserir Propriedade
	jQuery('#inserirProdutor').submit(function(){
		
		if ($("#selectCidades").val() == 0) {
			swal("","Selecione a cidade do produtor","warning");
		
		}else{

			var dadosajax = new FormData(this);
			pageurl = base_urla + 'admin/api/produtor';

			request("Salvando as informações");
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
						title: '',text: 'Dados inseridos com sucesso!!',type: 'success'
					},function(){
						window.location.href = base_urla +"admin/produtor/" + data;
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


//Mudar doc do tiipo de pessoa
function selectTipoPessoa(){
	var tipo = $("#tipo_pessoa").val();
	//pessoa fisica
	if (tipo == 1) {
		$("#label-cpf_cnpj").html("CPF");
		$("#label-ie_rg").html("RG");
	}
	//pessoa juridica
	else{
		$("#label-cpf_cnpj").html("CNPJ");
		$("#label-ie_rg").html("Inscrição Estadual");
	}
}