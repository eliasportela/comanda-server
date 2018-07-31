$(document).ready(function () {
    buscarProdutosCategoria();
});

var SELITENSREMOCAO1 = [];
var SELITENSREMOCAO2 = [];
var SELITENSREMOCAO = [];

function buscarProdutosCategoria() {
    var id = $("#categoria_produto").val();
    var url = base_urla + 'admin/api/produtos-categoria/' + id;
    var data = null;
    var option = $(".produtos");

    if (id !== "2"){
        $("#tipo_pizza").prop('disabled', true).addClass("w3-light-gray").val(0);
        mudarTipoPizza();
    } else {
        $("#tipo_pizza").prop('disabled', false).removeClass("w3-light-gray");
        mudarTipoPizza();
        buscarProdutosAdicionais();
    }

    $.get(url, function (res) {
        if (res) {
            data = JSON.parse(res);
        }
    }).done(function () {
        option.empty();
        option.append("<option>Selecione um Produto</option>");
        if (data !== null) {
            data.forEach(function (obj) {
                var eloption = $("<option>");
                eloption.val(obj.id_produto).html(obj.nome_produto);
                option.append(eloption);
            });
        }
    });
}

function mudarTipoPizza() {
    if ($("#tipo_pizza").val() === "1"){
        $("#baseProduto1").removeClass("m6").addClass("m3");
        $("#baseProduto2").removeClass("w3-hide");
    }else {
        $("#baseProduto1").removeClass("m3").addClass("m6");
        $("#baseProduto2").addClass("w3-hide");
    }
}

function buscarProdutosAdicionais() {

    var url = base_urla + 'admin/api/produtos-categoria/' + 1;
    var data = null;
    var option = $("#adicionaisProduto");

    $.get(url, function (res) {
        if (res) {
            data = JSON.parse(res);
        }
    }).done(function () {
        option.empty();
        if (data !== null) {
            data.forEach(function (obj) {
                var eloption = $("<option>");
                eloption.val(obj.id_produto).html(obj.nome_produto);
                option.append(eloption);
            });

        }
    });

}

function buscarProdutosRemocoes(id) {

    var produto = $("#id_produto" + id).val();
    var url = base_urla + 'admin/api/produto/id/' + produto;
    var data = null;
    var option = $("#remocoesProduto");

    $.get(url, function (res) {
        if (res) {
            data = JSON.parse(res);
        }
    }).done(function () {
        option.empty();
        if (data.itens !== null) {

            var itens = [];
            data.itens.forEach(function (obj) {
                var s = JSON.stringify(obj);
                itens.push(s);
            });

            if (id === '1'){
                SELITENSREMOCAO1 = itens;
            } else {
                SELITENSREMOCAO2 = itens;
            }

            SELITENSREMOCAO = SELITENSREMOCAO1.concat(SELITENSREMOCAO2);
            var map = JSON.parse("[" + unique(SELITENSREMOCAO) + "]");

            map.forEach(function (obj) {
                var eloption = $("<option>");
                eloption.val(obj.id_produto).html(obj.nome_produto);
                option.append(eloption);
            });

        }
    });

}

function unique(list) {
    var result = [];
    list.forEach(function (value) {
       if (!result.includes(value)){
           result.push(value);
       }
    });
    return result;
}
