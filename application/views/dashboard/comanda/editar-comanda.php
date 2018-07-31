<style type="text/css">
  .w3-table td{
    vertical-align: middle;
    cursor: pointer;
  }
</style>

<input type="hidden" name="id_comanda" id="id_comanda" value="<?=$comanda->id_comanda?>">

<div class="w3-main" style="margin-top:45px;">
  <!-- Header -->
  <header class="w3-container w3-cell-row" style="padding-top:10px"></header>
  <div class="w3-panel">
    <div class="w3-container w3-card w3-white w3-padding w3-padding-16">
        <form method="POST" action="" id="inserirPropriedade">
            <div class="w3-section" >
                <div class="w3-row-padding">
                    <div class="w3-col m8">
                        <span class="w3-large"> <i class="fa fa fa-shopping-basket fa-fw"></i><b> Comanda | REF: <?=$comanda->ref_comanda?> | Abertura: <?=date("d-m", strtotime($comanda->data_comanda)). ' ás '. date("H:m:s", strtotime($comanda->data_comanda))?></b></span>
                    </div>
                    <div class="w3-col m4 w3-right-align">
                        <span class="w3-padding <?=($comanda->status == 1 ? "w3-green": "w3-red")?>">
                            <b>Status:</b> <?=($comanda->status == 1 ? "Aberta": "Fechada")?>
                        </span>
                    </div>
                </div>
                <hr>
                <div class="w3-row-padding">
                    <div class="w3-col l1">
                        <label class="w3-margin-top"><b>Tipo</b></label>
                        <select class="w3-select w3-border w3-white" name="tipo_comanda">
                            <option value="1" <?=($comanda->tipo_comanda == 1 ? "selected" : "")?>>Mesa</option>   
                            <option value="0" <?=($comanda->tipo_comanda == 0 ? "selected" : "")?>>Viagem</option>   
                        </select>
                    </div>
                    <div class="w3-col l2">
                        <label class="w3-margin-top"><b>Mesa</b></label>
                        <input type="text" class="w3-input w3-border" name="id_mesa" value="<?=$comanda->mesa?>">
                    </div>
                    <div class="w3-col l7">
                        <label class="w3-margin-top"><b>Observações</b></label>
                        <input type="texto" class="w3-input w3-border" name="observacao" placeholder="Informações da Comanda" value="<?=$comanda->observacao?>">
                    </div>
                    <div class="w3-col l2 w3-hide">
                        <label class="w3-margin-top"><b>Cliente</b></label>
                        <button class="w3-button w3-dark-gray w3-block">Cliente</button>
                    </div>
                    <div class="w3-col l2">
                        <label class="w3-margin-top"><b>Editar</b></label>    
                        <button type="submite" class="w3-button w3-dark-gray w3-block">Atualizar Dados</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br>
    <div class="w3-row">
        <div class="w3-col m9">
            <div class="w3-card w3-white">
                <div class="w3-padding w3-padding-16">
                    <button class="w3-button w3-dark-gray w3-right" type="button" onclick="toogleModalProduto(1)"><i class="fa fa-plus"></i> Adicionar Produto</button>
                    <h6>Produtos da comanda</h6>
                </div>
                <div class="w3-responsive w3-border-bottom" style="min-height: 300px;margin:0 12px">
                    <table class="w3-table w3-bordered w3-centered">
                        <thead>
                            <tr class="w3-red">
                            <th style="width: 5%">#</th>
                            <th style="width: 10%">Referência</th>
                            <th style="width: 30%">Nome</th>
                            <th style="width: 15%">Categoria</th>
                            <th style="width: 10%">Quantidade</th>
                            <th style="width: 15%">Tabela</th>
                            <th style="width: 15%">Valor</th>
                            </tr>
                        </thead>
                        <tbody id="tableProdutos">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="w3-col m3">
            <div class="w3-margin-left w3-card w3-white w3-padding w3-padding-16 w3-display-container" style="min-height: 375px;">
                <div class="w3-green w3-padding w3-paddin-16">
                    <h5><b>Total da Comanda</b></h5>
                    <h2 class="w3-right-align">R$ <span id="totalComanda"></span></h2>
                </div>
                <hr>
                <div class="w3-padding-8">
                    <h5>Desconto</h5>
                    <input type="number" class="w3-input w3-border" placeholder="Desconto no total da comanda">
                </div>
                <div class="w3-display-bottommiddle w3-margin-top w3-margin-bottom" style="width:95%">
                    <button class="w3-button w3-dark-gray w3-block">Fechar Comanda</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/comanda/editar.js');?>"></script>