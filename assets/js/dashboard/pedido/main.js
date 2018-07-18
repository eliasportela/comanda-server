jQuery(document).ready(function(){

	getPedidos();

	setInterval(function(){ 
		getPedidos();
	}, 15000);

});

var IDPEDIDO = 1;

function getPedidos() {

	pageurl = base_urla + 'admin/api/pedidos';
	
	$.ajax({url: pageurl, type: 'GET',
		success: function(data, textStatus, jqXHR)
		{
			if (data != "") {	
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
	var p = pedido('palha',obj);
	select.append(p);
	
}

function pedido(cor,pedido) {

	var obs = [];
	for (var i = 0; i < pedido.observacoes.length; i++) {
      	obs.push(pedido.observacoes[i]);
    }

	var pedido = '<div id="pedido'+pedido.id_comanda_produto+'" class="w3-col l3 m6 w3-left-align w3-margin-top">'+
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
          '<h5><b>Mesa '+pedido.mesa+'</b></h5>' +
          pedido.quantidade +' '+ pedido.nome_produto + ' </br>' +
          '<div class="ads">' +
          '- Produto: '+pedido.nome_categoria+' </br>' +
          (obs[0] != undefined ? '- ' + obs[0] + '<br>' : "") +
          (obs[1] != undefined ? '- ' + obs[1] + '<br>' : "") +
          (obs[2] != undefined ? '- ' + obs[2] + '<br>' : "") +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>';

    IDPEDIDO++;

    return pedido;
}
