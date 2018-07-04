jQuery(document).ready(function(){
	jQuery('#solicitarContato').submit(function(){
		requestBtn('Enviando os dados!');
		var dadosContato = {
								"nome_contato": $("#nome_contato").val(),
								"email": $("#email").val(),
								"telefone": $("#telefone").val()
							};
		pageurl = base_urla + 'api/solicitar-contato';
		console.log(pageurl);
		$.ajax({url: pageurl,type: 'POST',data:  dadosContato,
		    success: function(data, textStatus, jqXHR)
		        {
		        	setTimeout(function(){ 
		        		requestBtnSuccess("Quero falar com um corretor");
		           		$('#solicitarContato').each(function(){this.reset();});
		           		swal("Solicitação enviada!","Obrigado por nos contatar, assim que possível entraremos em contato.","success");
		        	}, 2000);
				},
		    error: function(jqXHR, textStatus, errorThrown) 
		        {
		            swal("Erro!","Não foi possível enviar sua solicitação. Por favor envie um e-mail para contato@souzacafes.com.br","error");
		        }          
		    });	
		return false;
	});
});


