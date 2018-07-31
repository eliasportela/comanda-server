$(document).ready(function () {
    buscarProdutosCategoria();

    $('#inserirProdutos').submit(function () {

        if ($("#id_produto1").val() > 0 || $("#id_produto2").val() > 0) {
            swal("", "Selecione a cidade do produtor", "warning");

        } else {

            var dadosajax = {
                'id_comanda':1,
                'id_produto':1,
                'gerar_pedido':1,
                'quantidade':1,
                'id_tabela_produto':1,
                'observacao':1,
                'produtos'
            };

            pageurl = base_urla + 'admin/api/produto';

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

var SELITENSREMOCAO1 = [];
var SELITENSREMOCAO2 = [];
var SELITENSREMOCAO = [];

var ITENSREMOCAO = [];
var ITENSADICIONAIS = [];

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
        option.append("<option value='0'>Selecione um Produto</option>");
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

function addRemocoes() {

    var id = $("#remocoesProduto").val();
    var nome = $("#remocoesProduto").find("option:selected").text();

    if (!ITENSREMOCAO.includes(id)) {

        ITENSREMOCAO.push(id);

        var tr = $("<tr>");
        var td1 = $("<td>");
        var td2 = $("<td>");

        td1.append(nome);
        td2.append("<input type='hidden' value='"+ id +"' /><button class='w3-button' type='button' onclick='removerRemocao(" + id + ")' ><i class='fa fa-trash-o w3-text-red'></i></button>")

        tr.append(td1).append(td2);
        $("#tabelaRemocoes").append(tr);

    }

}

function addAdicionais() {

    var id = $("#adicionaisProduto").val();
    var nome = $("#adicionaisProduto").find("option:selected").text();

    if (!ITENSADICIONAIS.includes(id) && ($("#id_produto1").val() > 0 || $("#id_produto2").val() > 0 )) {

        ITENSADICIONAIS.push(id);

        var tr = $("<tr>");
        var td1 = $("<td>");
        var td2 = $("<td>");

        td1.append(nome);
        td2.append("<input type='hidden' value='"+ id +"'/><button class='w3-button' type='button' onclick='removerAdicionais(" + id + ")' ><i class='fa fa-trash-o w3-text-red'></i></button>")

        tr.append(td1).append(td2);
        $("#tabelaAdicionais").append(tr);

    }

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
