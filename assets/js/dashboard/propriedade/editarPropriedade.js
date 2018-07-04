//Editar/Visualizar
function getPropriedadesId(id){

	var url = base_urla + 'admin/api/propriedade/id/'+id;
	var data = null;
	
	$.get(url, function(res) {
		
		if (res) {
			data = JSON.parse(res);
			data = data[0];
			$("#nome_propriedade").val(data.nome_propriedade);
			$("#tipo_propriedade").val(data.tipo_propriedade);
			$("#cnpj").val(data.cnpj);
			$("#contato").val(data.contato);
			$("#telefone").val(data.telefone);
			$("#foto_propriedade").val(data.foto_propriedade);
			$("#latitude").val(data.latitude);
			$("#longitude").val(data.longitude);
			$("#altitude").val(data.altitude);
			$("#area_total").val(data.area_total);
			$("#area_plantada").val(data.area_plantada);
			$("#area_irrigada").val(data.area_irrigada);
			$("#arrendada").val(data.arrendada);
			$("#prod_media_cafe").val(data.prod_media_cafe);
			$("#p_eletricidade").val(data.p_eletricidade);
			$("#p_familiar").val(data.p_familiar);
			$("#p_analise_solo_folha").val(data.p_analise_solo_folha);
			$("#p_adubacao_organica").val(data.p_adubacao_organica);
			$("#p_fertilizacao").val(data.p_fertilizacao);
			$("#p_analise_camada_expessura").val(data.p_analise_camada_expessura);
			$("#p_sistema_tulhas").val(data.p_sistema_tulhas);
			$("#p_protecao_chuva").val(data.p_protecao_chuva);
			$("#tipo_terreiro").val(data.tipo_terreiro);
			$("#tipo_processamento").val(data.tipo_processamento);
			$("#logradouro").val(data.logradouro);
			$("#numero_km").val(data.numero_km);
			$("#selectPropCidades").val(data.id_cidade);
			$("#obs").val(data.obs);
			$("#selectPropEstados").val(data.id_estado);
			
		}else{
			swal("","Erro interno, por favor recarregue a página","error");
		}
	})
	.done(function(){
    	getCidades('selectPropEstados','selectPropCidades',data.id_cidade);
    	getSafrasFechamento(id);
    	getSafrasPrevisao(id);
    	getSafrasCafes(id);
    	toogleTipoProcessamento();
    	$("#image-propriedade").empty();
    	$("#image-propriedade-bd").attr("src","");
    	if ((data.foto_propriedade != "") && (data.foto_propriedade != null)) {
    		$("#image-propriedade-bd").attr("src",base_urla + 'uploads/docs/'+IDPRODUTOR+'/propriedades/'+data.foto_propriedade);
    		$("#image-propriedade-bd").css("display","block");
    	}
    });
}

function getSafrasPrevisao(id){
	url = base_urla + 'admin/api/safra-previsao/' + id;
	previsao = $("#tabelaSafraPrevisao");
	previsao.empty();
	$.get(url, function(res) {
		if (res) {
			data = JSON.parse(res);
			
			data.forEach(function(obj){
				var col = "";
				col += '<td>'+obj.safra_ano_inicio+ '/' +obj.safra_ano_fim + '</td>';
				col += '<td>'+obj.qtd_safra+'</td>';
				col += '<td><button type="button" class="w3-button w3-red w3-round" onclick="removerSafraPrevisao('+obj.id_safra_previsao+')"><i class="fa fa-trash-o"></i></button></td>';
				previsao.append("<tr>"+col+"</tr>");
			});
		}
	})
	.done(function(){
    	
    });
}

function getSafrasFechamento(id){
	url = base_urla + 'admin/api/safra-fechamento/' + id;
	fechamento = $("#tabelaSafraFechamento");
	fechamento.empty()
	$.get(url, function(res) {
		if (res) {
			data = JSON.parse(res);
			data.forEach(function(obj){
				var col = "";
				col += '<td>'+obj.safra_ano_inicio+ '/' +obj.safra_ano_fim + '</td>';
				col += '<td>'+obj.qtd_safra+'</td>';
				col += '<td><button type="button" class="w3-button w3-red w3-round" onclick="removerSafraFechamento('+obj.id_safra_fechamento+')"><i class="fa fa-trash-o"></i></button></td>';
				fechamento.append("<tr>"+col+"</tr>");
			});
		}
	})
	.done(function(){
    	
    });
}

function getSafrasCafes(id){
	url = base_urla + 'admin/api/safra-cafe/' + id;
	selector = $("#tabelaSafraCafe");
	selector.empty();
	$.get(url, function(res) {
		if (res) {
			data = JSON.parse(res);
			
			data.forEach(function(obj){
				var col = "";
				col += '<td>'+obj.safra_ano_inicio+ '/' +obj.safra_ano_fim + '</td>';
				col += '<td>'+obj.variedade+'</td>';
				col += '<td>'+obj.area_plantada+'</td>';
				col += '<td>'+obj.qtd_safra+'</td>';
				col += '<td>'+
							'<button type="button" class="w3-button w3-red w3-round" onclick="removerSafraCafe('+obj.id_safra_cafe+')"><i class="fa fa-trash-o"></i></button></td>';
				selector.append("<tr>"+col+"</tr>");
			});
		}
	})
	.done(function(){
    	
    });
}