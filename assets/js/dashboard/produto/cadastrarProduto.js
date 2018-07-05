jQuery(document).ready(function(){

	//Inserir Propriedade
	jQuery('#inserirProduto').submit(function(){

		var dadosajax = new FormData(this);
		pageurl = base_urla + 'admin/api/produto';
		
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
		

		return false;
	});

});


//Toogle Disable input gerarReferencia 
function toogleGerarReferencia(){
	var tipo = $("#gerar-referencia").val();
	if (tipo == 0) {
		$("#referencia").val("");
		$("#referencia").prop("disabled","true");
	}
	else{
		$("#referencia").removeAttr("disabled");
	}
}