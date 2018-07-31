<style>
    #modalProduto {
        padding-top: 0;
    }
    #modalProduto .modal {
        width: 95% !important;
    }
</style>
<div id="modalProduto" class="w3-modal">
    <div class="w3-modal-content modal w3-card-4 w3-animate-left" style="margin-top: 60px">
        <form method="POST" action="" id="inserirProdutos">
            <div class="w3-container w3-padding-16 w3-large w3-border-bottom">
                <i class="fa fa-tag"></i> <span id="titleProduto">Inserir Produto</span>
                <span class="w3-right" onclick="toogleModalProduto(0)" style="cursor: pointer;"><i class="fa fa-times"></i></span>
            </div>
            <div class="w3-section" >
                <div class="w3-row-padding">
                    <div class="w3-col m2">
                        <label for="categoria_produto">Categoria</label>
                        <select class="w3-select w3-border w3-white" id="categoria_produto" name="id_categoria" onchange="buscarProdutosCategoria()">
                            <?php foreach ($categorias as $categoria): ?>
                            <option value="<?=$categoria->id_categoria?>"><?=$categoria->nome_categoria?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="w3-col m2">
                        <label for="tipo_pizza">Pizza</label>
                        <select class="w3-select w3-border w3-white" id="tipo_pizza" name="tipo_pizza" onchange="mudarTipoPizza()">
                            <option value="0">Inteira</option>
                            <option value="1">1/2 a 1/2</option>
                        </select>
                    </div>
                    <div class="w3-col m4" id="baseProduto1">
                        <label for="id_produto1">Produto</label>
                        <select class="w3-select w3-border w3-white produtos" id="id_produto1" onchange="buscarProduto('1')">
                        </select>
                    </div>
                    <div class="w3-col m2 w3-hide" id="baseProduto2">
                        <label for="id_produto2">Produto</label>
                        <select class="w3-select w3-border w3-white produtos" id="id_produto2" onchange="buscarProduto('2')">
                        </select>
                    </div>
                    <div class="w3-col m2">
                        <label for="tipo_pizza">Tabela/Preço</label>
                        <select class="w3-select w3-border w3-white" id="tabelasProduto" name="tabelasProduto">
                            <option value="0">Selecione um Produto</option>
                        </select>
                    </div>
                    <div class="w3-col m2">
                        <label>Quantidade</label>
                        <input type="number" class="w3-input w3-border" value="1" placeholder="Quantidade" id="quantidade">
                    </div>
                </div>
                <div class="w3-row-padding">
                    <div class="w3-col m4 w3-margin-top">
                        <div class="w3-row">
                            <div class="w3-col m9">
                                <label class="w3-margin-top"><b>Remoções</b></label>
                                <select class="w3-select w3-border w3-white" id="remocoesProduto">
                                    <option>Selecione um Produto</option>
                                </select>
                            </div>
                            <div class="w3-col m3" style="padding-left: 2px">
                                <label class="w3-margin-top invisivel"><b>A</b></label>
                                <button class="w3-button w3-dark-gray w3-block" type='button' onclick="addRemocoes()" style="height: 40px"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="w3-margin-top" style="height: 200px">
                            <table class="w3-table w3-small">
                                <tr class="w3-red">
                                    <th style="width: 80%">Produto</th>
                                    <th style="width: 20%"></th>
                                </tr>
                                <tbody id="tabelaRemocoes">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="w3-col m4 w3-margin-top">
                        <div class="w3-row">
                            <div class="w3-col m9">
                                <label class="w3-margin-top"><b>Adicionais</b></label>
                                <select class="w3-select w3-border w3-white" id="adicionaisProduto">
                                    <option>Selecione um Produto</option>
                                </select>
                            </div>
                            <div class="w3-col m3" style="padding-left: 2px">
                                <label class="w3-margin-top invisivel"><b>A</b></label>
                                <button class="w3-button w3-dark-gray w3-block" type="button" onclick="addAdicionais()" style="height: 40px"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="w3-margin-top" style="height: 200px">
                            <table class="w3-table w3-small">
                                <tr class="w3-red">
                                    <th style="width: 80%">Produto</th>
                                    <th style="width: 20%"></th>
                                </tr>
                                <tbody id="tabelaAdicionais">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="w3-col m4 w3-margin-top">
                        <label class="w3-margin-top"><b>Observações</b></label>
                        <textarea class="w3-input w3-border" placeholder="Observação sobre o pedido" id="obsProduto" style="height: 250px"></textarea>
                    </div>
                </div>
            </div>
            <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                <button onclick="toogleModalProduto(0)" type="button" class="w3-button w3-gray" style="width: 150px">
                    <i class="fa fa-times"></i>
                    Cancelar
                </button>
                <button type="submit" class="w3-button w3-red w3-right" style="width: 150px">
                    <i class="fa fa-check"></i>
                    Inserir
                </button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/comanda/modal.js');?>"></script>