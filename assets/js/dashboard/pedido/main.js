jQuery(document).ready(function(){

	getPedidos();

	setInterval(function(){ 
		getPedidos();
	}, 5000);

});

var IDPEDIDO = 1;

function getPedidos() {

	pageurl = base_urla + 'api/pedidos/1/' + token;
	
	$.ajax({url: pageurl, type: 'GET',
		success: function(data, textStatus, jqXHR)
		{
			if (data !== "") {
				data = JSON.parse(data);
				$("#basePedido").html("");
				data.forEach(function (obj) {
					addNovoPedido(obj);
				});
			}
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			swal("Erro!","Erro na solictação!","error");
		}          
	});
}

function fecharPedido(id) {
	$.get(base_urla + 'admin/api/finalizar-pedidos/' + id)
     .success(function(result) { 
		$("#pedido" + id).hide();
     })
     .error(function(jqXHR, textStatus, errorThrown) { 
     	console.log(jqXHR);
     });
}

function addNovoPedido(obj){
	var select = $("#basePedido");
	var p = addPedido('palha',obj);
	select.append(p);
	
}

function addPedido(cor,pedido) {

	var obs = [];
	if (pedido.adicionais !== null) {
        for (var i = 0; i < pedido.adicionais.length; i++) {
            obs.push(pedido.adicionais[i].nome_produto);
        }
    }

	var pedido = '<div id="pedido'+pedido.id_comanda_produto+'" class="w3-col l2 m6 w3-left-align w3-margin-top">'+
      '<div class="card card-'+cor+'">'+
       	'<div class="card-header w3-row">'+
          '<div class="w3-col s6">'+
            '<button class="w3-button w3-block"><i class="fa fa-times"></i></button>'+
          '</div>'+
          '<div class="w3-col s6">'+
            '<button class="w3-button w3-block" onclick="fecharPedido(\''+ pedido.id_comanda_produto + '\')"><i class="fa fa-check"></i></button>'+
          '</div>'+
        '</div>'+
        '<div class="card-body">' +
          '<h5><b>Mesa '+pedido.mesa+'</b></h5>' + normalizarNome(pedido.nome_produto) + ' </br>' +
          '<div class="ads">' +
          '- QTD: '+ pedido.quantidade +' </br>' +
          '- Tamanho: '+ pedido.nome_tabela +' </br>' +
          '- Produto: '+ pedido.nome_categoria +' </br>' +
          '- '+ pedido.observacao +' </br>' +
          (obs[0] !== undefined ? '- C/ ' + obs[0] + '<br>' : "") +
          (obs[1] !== undefined ? '- C/' + obs[1] + '<br>' : "") +
          (obs[2] !== undefined ? '- C/' + obs[2] + '<br>' : "") +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>';

    IDPEDIDO++;

    return pedido;
}

function normalizarNome(n){
	n = n.split("||");
    var nome = "";
	n.forEach(function (value) {
		nome += (n.length > 1 ? '1/2 ' : '') + value + ', ';
	});

	return nome.substring(0, nome.length - 2);
}
