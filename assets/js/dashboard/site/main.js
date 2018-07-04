function editarConteudoSite(id) {
	$('#modalEditarConteudo').css('display','block');
	selectConteudo(id);
}

function selectConteudo(id){

	if (id == 3) { // se for 3 abre o modal de edicao dos imoveis em destaque
 		$('#modalEditarConteudo').css('display','none');
 		$('#modalEditarDestaque').css('display','block');
		return
	}

	$('#textoConteudoOutput').empty();
	request('carregando conteudo');
	var data = [];
	
	//fazendo a requisicao
	$.get(base_urla + 'api/conteudo-site?id='+id, function(res) { 
       
       	data = JSON.parse(res);
     
       	data.forEach(function(obj){
       
	       $('.textoConteudoOutput').append('<label><b>'+ obj.ds_texto_conteudo +'<b></label>' +
	       									'<textarea class="w3-input w3-border">'+ obj.texto +'</textarea><br>'
	       								);
    	});
    })
    .done(function(){
    	requestSuccess()
    });
}
