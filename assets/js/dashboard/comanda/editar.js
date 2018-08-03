jQuery(document).ready(function () {

    //Buscando Produtos da comanda
    IDCOMANDA = $("#id_comanda").val();
    if (IDCOMANDA !== undefined) {
        getProdutos(IDCOMANDA);
	}

	jQuery('#editarComanda').submit(function () {

		if ($("#selectCidades").val() === 0) {
			swal("", "Selecione a cidade do produtor", "warning");

		} else {

			var dadosajax = new FormData(this);
			pageurl = base_urla + 'admin/api/produtor/editar/' + IDCOMANDA;

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

var IDCOMANDA;

function getProdutos(id) {

    var url = base_urla + 'admin/api/comanda-prudutos/' + id;
	var data = null;
	var selector = $("#tableProdutos");
	selector.empty();

	$.get(url, function (res) {
		if (res) {
			data = JSON.parse(res);
		}
	}).done(function () {
        var valor = 0;
        if (data !== null) {
			data.forEach(function (obj) {
                var col = "";
				var valorUni = (parseFloat(obj.quantidade) * parseFloat(obj.valor));
				col += "<td><button class='w3-button w3-text-red w3-round'><i class='fa fa-trash'></i></button></td>";
                col += "<td>"+ obj.ref_produto +"</td>";
                col += "<td>"+ obj.nome_produto +"</td>";
                col += "<td>"+ obj.nome_categoria +"</td>";
                col += "<td>"+ obj.quantidade +"</td>";
                col += "<td>"+ obj.nome_tabela +"</td>";
                col += "<td>"+ formataDinheiro(valorUni) +"</td>";
                selector.append("<tr onclick='getProdutoId("+ obj.id_comanda +","+ obj.id_produto +")'>" + col + "</tr>");
                valor +=  valorUni;
            });
		}
        totalComanda(valor);
    });
}

function totalComanda(valor){
    $("#totalComanda").html(formataDinheiro(valor));
}

function formataDinheiro(n) {
    return n.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
}

function getProdutoId(comanda,produto) {
	
	var url = base_urla + 'admin/api/comanda-pruduto/' + comanda + '/' + produto;
	var data = null;

	$.get(url, function (res) {
		if (res) {
			data = JSON.parse(res);
			$("#qtdProduto").val(data.quantidade);
			$("#tabelaProduto").val();
			$("#obsProduto").val(data.observacao);
			$("#titleProduto").html(data.nome_categoria+" | "+data.ref_produto+" | "+ data.nome_produto)
		} else {
			swal("", "Erro interno, por favor recarregue a página", "error");
		}
	})
	.done(function () {
		if (data != "") {
            toogleModalProduto(1);
		}
	});
}

function toogleModalProduto(arg) {
	if (arg === 1) {
		$("#modalProduto").css("display","block");
	}else{
		$("#modalProduto").css("display","none");
	}
}

