jQuery(document).ready(function(){

	//Buscando Produtores
	getProdutores(1);

});

var PAGEID = 1;
var PAGEQTD = 1;

// Select produtores
function getProdutores(page){
	var selector = $("#produtores");
	var url = base_urla + 'admin/api/produtor/' + page;
	
	selector.empty();
	var nome = "nome="+$("#nomesearch").val();
	var tipo = "tipo="+$("#tiposearch").val();;
	var cidade = "cidade="+$("#cidadesearch").val();

	//request("Buscando Dados");
	$.get(url +'?'+nome+'&'+tipo+'&'+cidade, function(res) {
		if (res) {
			data = JSON.parse(res);
			PAGEQTD = data.pages; 
			data.result.forEach(function(obj){
				var col = "";
				col += "<td>"+"<i class='fa fa-user'></i></td>"
				col += "<td>"+obj.nome_produtor+"</td>"
				col += "<td>"+obj.nome_tipo_pessoa+"</td>"
				col += "<td>"+obj.email+"</td>"
				col += "<td>"+obj.nome_cidade+"</td>"
				selector.append("<tr onclick=viewProdutor("+obj.id_produtor+")>"+col+"</tr>");
			});
			$("#naoencontrado").css("display","none");
		}else{
			data = null;
			$("#naoencontrado").css("display","block");
		}
	})
	.done(function(){
    	if (PAGEID == PAGEQTD) {
    		$("#btnproximo").prop("disabled","true");;
    	}
    	if(PAGEID == 1){
    		$("#btnanterior").prop("disabled","true");;
    	}
    });
}

function limparSearch() {
	$("#nomesearch").val("");
	$("#tiposearch").val("");
	$("#cidadesearch").val("");
	getProdutores(1);
}

function pagination(tipo){
	if (tipo == 0) {
		//anterior
		PAGEID = PAGEID - 1;
		getProdutores(PAGEID);
		$("#btnproximo").removeAttr("disabled");
	}else if(tipo == 1) {
		//proximo
		PAGEID = PAGEID + 1;
		getProdutores(PAGEID);
		$("#btnanterior").removeAttr("disabled");
	}
}


function viewProdutor(id){
	window.location.href = base_urla +"admin/produtor/" + id;
}