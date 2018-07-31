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
                'produtos':1
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

var VALORESPRODUTO1 = [];
var VALORESPRODUTO2 = [];
var VALORESPRODUTOS = []

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
        if (data !== null) {
            data.forEach(function (obj) {
                var eloption = $("<option>");
                eloption.val(obj.id_produto).html(obj.nome_produto);
                option.append(eloption);
            });
            buscarProduto('1');
        }
    });
}

function mudarTipoPizza() {
    if ($("#tipo_pizza").val() === "1"){
        $("#baseProduto1").removeClass("m4").addClass("m2");
        $("#baseProduto2").removeClass("w3-hide");
    }else {
        $("#baseProduto1").removeClass("m2").addClass("m4");
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

function buscarProduto(id) {

    var produto = $("#id_produto" + id).val();
    var url = base_urla + 'admin/api/produto/id/' + produto;
    var data = null;

    //Remocoes
    var option = $("#remocoesProduto");
    var optTabelas = $("#tabelasProduto");

    $.get(url, function (res) {
        if (res) {
            data = JSON.parse(res);
        }
    }).done(function () {
        option.empty();
        if (data.itens !== null) {

            //add Remocoes
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


            //add Tabela de preços
            var tabelas = [];
            data.valores.forEach(function (obj) {        
                tabelas.push(obj);
            });            

            if (id === '1'){
                VALORESPRODUTO1 = tabelas;
            } else {
                VALORESPRODUTO2 = tabelas;
            }

            VALORESPRODUTOS = VALORESPRODUTO1.concat(VALORESPRODUTO2);

            console.log(VALORESPRODUTOS);

            // var elOptTabelas = $("<option>");
            // elOptTabelas.val(obj.id_tabela_preco).html(obj.nome_tabela +' - R$'+ obj.valor);
            // optTabelas.append(elOptTabelas);

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

function regraValorMaior(list) {
    var result = [];
    list.forEach(function (value) {
       if (!result.includes(value)){
           result.push(value);
       }
    });
    return result;
}
