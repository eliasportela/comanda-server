jQuery(document).ready(function(){

	//Inserir Propriedade
	jQuery('#inserirProduto').submit(function(){

		var dadosajax = new FormData(this);
		var pageurl = base_urla + 'admin/api/produto';
		
		request("Salvando as informações");

		$.ajax({
			url: pageurl,
			type: 'POST',
			data:  dadosajax,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data)
			{
				requestSuccess();
				swal({
					title: '',text: 'Dados inseridos com sucesso!!',type: 'success'
				},function(){
					window.location.href = base_urla +"admin/produto/" + data;
				});
				
			},
			error: function(jqXHR)
			{
				console.log(jqXHR);
				requestSuccess();
			}          
		});
		

		return false;
	});

});