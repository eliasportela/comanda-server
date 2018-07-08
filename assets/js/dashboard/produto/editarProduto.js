jQuery(document).ready(function () {

	//Buscando Produtor
	if ($("#id_produto").val() != undefined) {
		IDPRODUTO = $("#id_produto").val();
		getProdutoID(IDPRODUTO);
	}

	jQuery('#editarProdutor').submit(function () {

		if ($("#selectCidades").val() == 0) {
			swal("", "Selecione a cidade do produtor", "warning");

		} else {

			var dadosajax = new FormData(this);
			pageurl = base_urla + 'admin/api/produtor/editar/' + IDPRODUTO;

			request("Salvando as alterações");
			$.ajax({
				url: pageurl,
				type: 'POST',
				data: dadosajax,
				mimeType: "multipart/form-data",
				contentType: false,
				cache: false,
				processData: false,
				success: function (data, textStatus, jqXHR) {
					requestSuccess();
					swal({
						title: '', text: 'Dados atualizados com sucesso!!', type: 'success'
					}, function () {
						location.reload();
					});
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log(jqXHR);
					requestSuccess();
				}
			});
		}

		return false;
	});

});

var IDPRODUTO = "";

function getProdutoID(id) {

	var url = base_urla + 'admin/api/produto/id/' + id;
	var data = null;
	var itens = null;
	var selector = $("#itens");
	selector.empty();

	$.get(url, function (res) {
		if (res) {
			data = JSON.parse(res);
			produto = data.produto;
			itens = data.itens;

			$("#nome_produto").val(produto.nome_produto);
			$("#categoria_produto").val(produto.id_categoria);
			$("#referencia").val(produto.ref_produto);

		} else {
			swal("", "Erro interno, por favor recarregue a página", "error");
		}
	})
		.done(function () {
			if (itens != "") {
				itens.forEach(function (obj) {
					var col = "";
					col += "<td>" + obj.nome_produto + "</td>";
					col += '<td><button class="w3-button w3-dark-gray w3-round">Remover</button></td>';
					selector.append("<tr>" + col + "</tr>");
				});
			}
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
		'<button class="w3-button w3-dark-gray w3-round" type="button" onclick="removeSafraPrevisao(' + ITEM + ')">' +
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
		'<input type="hidden" value="' + $("#precosValor").val() + '" class="w3-input w3-border" name="precos[]" required>' +
		$("#precosValor").val() +
		'</td>';
	col += '<td>' +
		'<button class="w3-button w3-dark-gray w3-round" type="button" onclick="removeSafraPrevisao(' + ITEM + ')">' +
		'Remover' +
		'</button>' +
		'</td>';
	selPreco.append("<tr id='rowTabelaItens" + PRECO + "'>" + col + "</tr>");

	PRECO = PRECO + 1;
}

function removeSafraPrevisao(id) {
	$("#rowSafraPre" + id).remove();
}

function addSafraFechamento() {

	var selector = $("#tabelaSafraFechamento");
	var col = "";
	col += '<td>' +
		'<input type="number" value="2018" class="w3-input w3-border" name="safraFeAnoInicio[]" min="1900" max="2099" style="width: 45%;display: inline-block;margin-right:3px" required>' +
		'<input type="number" value="2019" class="w3-input w3-border" name="safraFeAnoFim[]" min="1900" max="2099" style="width: 45%;display: inline-block" required>' +
		'</td>';
	col += '<td>' +
		'<input type="number" class="w3-input w3-border" placeholder="Quantidade de sacas" name="safraFeQtd[]" required>' +
		'</td>';
	col += '<td>' +
		'<button class="w3-button w3-border w3-round" type="button" onclick="removeSafraFechamento(' + SAFRAFE + ')">' +
		'<i class="fa fa-times"></i> Remover' +
		'</button>' +
		'</td>';
	selector.append("<tr id='rowSafraFe" + SAFRAFE + "'>" + col + "</tr>");

	SAFRAFE = SAFRAFE + 1;
}

function removeSafraFechamento(id) {
	$("#rowSafraFe" + id).remove();
}