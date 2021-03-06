jQuery(document).ready(function(){

	//Buscando produtos
	getProdutos(1);

});

var PAGEID = 1;
var PAGEQTD = 1;

// Select produtos
function getProdutos(page){
	var selector = $("#produtos");
	var url = base_urla + 'api/produtos/' + page;

	var nome = "nome="+$("#nomesearch").val();
	var tipo = "categoria="+$("#tiposearch").val();;
	var referencia = "referencia="+$("#referenciasearch").val();
	var ingrediente = "ingrediente="+$("#ingredientesearch").val();

	request("Buscando Dados");
	$.get(url +'?'+nome+'&'+tipo+'&'+referencia+'&'+ingrediente, function(res) {
        selector.empty();
        if (res) {
			data = JSON.parse(res);
			PAGEQTD = data.pages;
			data.result.forEach(function(obj){
				var col = "";
				console.log(obj.ingrediente);
				col += "<td>"+"<i class='fa fa-tag'></i></td>"
				col += "<td>"+obj.ref_produto+"</td>"
				col += "<td>"+obj.nome_produto+"</td>"
				col += "<td>"+(obj.ingrediente === '0' ? "Não" : "Sim")+"</td>"
				col += "<td>"+obj.nome_categoria+"</td>"
				selector.append("<tr onclick=viewProduto("+obj.id_produto+")>"+col+"</tr>");
			});
			$("#naoencontrado").css("display","none");
			$("#loadSpinner").css("display","none");
            requestSuccess();
		}else{
			data = null;
			$("#naoencontrado").css("display","none");
			$("#loadSpinner").css("display","block");
            requestSuccess();
		}
	})
	.done(function(){
    	if (PAGEID == PAGEQTD) {
    		$("#btnproximo").prop("disabled","true");
    	}
    	if(PAGEID == 1){
    		$("#btnanterior").prop("disabled","true");
    	}
    });
}

function limparSearch() {
	$("#nomesearch").val("");
	$("#tiposearch").val("0");
	$("#referenciasearch").val("");
	$("#ingredientesearch").val("0");
	getProdutos(1);
}

function pagination(tipo){
	if (tipo == 0) {
		//anterior
		PAGEID = PAGEID - 1;
		getProdutos(PAGEID);
		$("#btnproximo").removeAttr("disabled");
	}else if(tipo == 1) {
		//proximo
		PAGEID = PAGEID + 1;
		getProdutos(PAGEID);
		$("#btnanterior").removeAttr("disabled");
	}
}

function viewProduto(id){
	window.location.href = base_urla +"admin/produto/" + id;
}