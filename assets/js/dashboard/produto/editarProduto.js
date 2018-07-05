jQuery(document).ready(function(){

	//Buscando Produtor
	if ($("#id_produto").val() != undefined) {
		IDPRODUTO = $("#id_produto").val();
		getProdutorID(IDPRODUTO);
	}
	
	jQuery('#editarProdutor').submit(function(){
		
		if ($("#selectCidades").val() == 0) {
			swal("","Selecione a cidade do produtor","warning");
		
		}else{

			var dadosajax = new FormData(this);
			pageurl = base_urla + 'admin/api/produtor/editar/' + IDPRODUTO;

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

var IDPRODUTO = "";

function getProdutorID(id){
	
	var url = base_urla + 'admin/api/produto/id/'+id;
	var data = null;
	var selector = $("#itens");
	
	selector.empty();
	
	$.get(url, function(res) {
		
		if (res) {
			data = JSON.parse(res);
			produto = data.produto;
			itens = data.itens;

			$("#nome_produto").val(produto.nome_produto);
			$("#categoria_produto").val(produto.id_categoria);
			$("#referencia").val(produto.ref_produto);
			
		}else{
			swal("","Erro interno, por favor recarregue a página","error");
		}
	})
	.done(function(){
		itens.forEach(function(obj){
			var col = "";
			col += "<td></td>";
			col += "<td>"+obj.ref_produto+"</td>";
			col += "<td>"+obj.nome_produto+"</td>";
			col += "<td>Remover</td>";
			console.log(col);
			selector.append("<tr"+col+"</tr>");
		});
    });
}


function alterTableItens(itens){

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