jQuery(document).ready(function () {

	//Buscando Produtor
    IDPRODUTO = $("#id_produto").val();
    if (IDPRODUTO !== undefined) {
		getProdutoID(IDPRODUTO);
	}

	jQuery('#editarProduto').submit(function () {

		var dadosajax = new FormData(this);
		var pageurl = base_urla + 'admin/api/produto/editar';

		request("Salvando as alterações");
		$.ajax({
			url: pageurl,
			type: 'POST',
			data: dadosajax,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function (data, textStatus, jqXHR) {
				requestSuccess();
				swal({
					title: '', text: 'Dados atualizados com sucesso!!', type: 'success'
				}, function () {
					//console.log(jqXHR);
					location.reload();
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				requestSuccess();
			}
		});
		

		return false;
	});

});

var IDPRODUTO = "";

function getProdutoID(id) {

	var url = base_urla + 'api/produto/id/' + token + '?produtos=' + id;
	var data = null;
	var itens = null;
	var tabelas = null;
	var produto = null;

	var selector = $("#itens");
	selector.empty();

	var tabelaPrecos = $("#valores");	
	tabelaPrecos.empty();

    request("Buscando Dados do produto");
	$.get(url, function (res) {
		if (res) {
			data = JSON.parse(res);
			produto = data.produto;
			itens = data.itens;
			tabelas = data.valores;

		    $("#nome_produto").val(produto.nome_produto);
			$("#id_categoria").val(produto.id_categoria);
			$("#referencia").val(produto.ref_produto);
			$("#gerar_pedido").val(produto.gerar_pedido);

		} else {
			swal("", "Erro interno, por favor recarregue a página", "error");
		}
	}).done(function () {
		if (itens != null) {
			itens.forEach(function (obj) {
				var col = "";
				col += "<td>" + obj.nome_produto + "</td>";
				col += '<td><button class="w3-button w3-dark-gray w3-round">Remover</button></td>';
				selector.append("<tr>" + col + "</tr>");
			});
		}

		if (tabelas != null) {
			tabelas.forEach(function (obj) {
				var col = "";
				col += "<td>" + obj.nome_tabela + "</td>";
				col += "<td>" + obj.valor + "</td>";
				col += '<td><button class="w3-button w3-dark-gray w3-round">Remover</button></td>';
				tabelaPrecos.append("<tr>" + col + "</tr>");
			});
		}

		requestSuccess();
	});
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
	}, function () {
		url = base_urla + 'admin/api/produtor/remover/' + id;
		$.get(url).done(function () {
			window.location.href = base_urla + "admin/produtor";
		});
	});
}

var ITEM = 0;
var PRECO = 0;

function addItemTabela() {

	var selector = $("#tableItens");
	var col = "";
	col += '<td>' +
		'<input type="hidden" value="' + $("#produtos").val() + '" class="w3-input w3-border" name="produtos[]" required>' +
		$("#produtos :selected").text() +
		'</td>';
	col += '<td>' +
		'<button class="w3-button w3-border w3-round" type="button" onclick="removeItemTabela(' + ITEM + ')">' +
		'Remover' +
		'</button>' +
		'</td>';
	selector.append("<tr id='rowTabelaItens" + ITEM + "'>" + col + "</tr>");

	ITEM = ITEM + 1;
}

function addPrecoTabela() {

	var selPreco = $("#tablePreco");
	var col = "";
	col += '<td>' +
		'<input type="hidden" value="' + $("#precos").val() + '" class="w3-input w3-border" name="precos[]" required>' +
		$("#precos :selected").text() +
		'</td>';
	col += '<td>' +
		'<input type="hidden" value="' + $("#precosValor").val() + '" class="w3-input w3-border" name="valores[]" required>' +
		$("#precosValor").val() +
		'</td>';
	col += '<td>' +
		'<button class="w3-button w3-border w3-round" type="button" onclick="removePrecoTabela(' + PRECO + ')">' +
		'Remover' +
		'</button>' +
		'</td>';
	selPreco.append("<tr id='rowTabelaItens" + PRECO + "'>" + col + "</tr>");

	$("#precos").val(1);
	$("#precosValor").val("");
	PRECO = PRECO + 1;
}

function removeItemTabela(id) {
	$("#rowTabelaItens" + id).remove();
}

function removePrecoTabela(id) {
	$("#rowTabelaItens" + id).remove();
}