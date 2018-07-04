function visualizarContato(id) {

	pageurl = base_urla + 'admin/visualizacao-contato?id='+id;
	
	$.ajax({url: pageurl, type: 'GET',
		success: function(data, textStatus, jqXHR)
		{
			// setTimeout(function(){ 
			// 	requestBtnSuccess("Quero falar com um corretor");
			// 	$('#solicitarContato').each(function(){this.reset();});
			// 	swal("Solicitação enviada!","Obrigado por nos contatar, assim que possível entraremos em contato.","success");
			// }, 2000);
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			swal("Erro!","Erro na solictação!","error");
		}          
	});

}
