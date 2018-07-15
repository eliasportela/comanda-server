<style type="text/css">
  .w3-table td{
    vertical-align: middle;
    padding: 14px;
    cursor: pointer;
  }
</style>

<div class="w3-main" style="margin-left:300px;margin-top:45px;">
  <!-- Header -->
  <header class="w3-container w3-cell-row" style="padding-top:25px">
    <span class="w3-large"><i class="fa fa fa-shopping-basket fa-fw"></i><b> Comandas</b></span>
    <br class="w3-hide-large">
    <a class="w3-button w3-red w3-right" href="<?=base_url('admin/contatos')?>">Limpar Filtros</a>
    <a class="w3-button w3-red w3-right" style="margin-right: 12px" href="#" onclick="location.reload();">Atualizar página</a>
  </header>
  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
        <div class="w3-container w3-card w3-white w3-padding w3-padding-32">
          <form method="GET" action="<?=base_url("admin/comanda")?>">
            <div class="w3-row-padding w3-center">
              <div class="w3-col l2">
                <label class="w3-margin-top"><b>Status</b></label>
                <select class="w3-select w3-border w3-white" name="status">
                  <option value="1">Abertas</option>
                  <option value="0">Fechadas</option>
                </select>
              </div>
              <div class="w3-col l2">
                <label class="w3-margin-top"><b>Tipo</b></label>
                <select class="w3-select w3-border w3-white" name="tipo">
                  <option value="1">Mesa</option>   
                  <option value="0">Viagem</option>   
                </select>
              </div>
              <div class="w3-col l2">
                <label class="w3-margin-top"><b>De</b></label>
                <input type="date" name="data_de" class="w3-input w3-border" value="<?=$optionDataDe?>">
              </div>
              <div class="w3-col l2">
                <label class="w3-margin-top"><b>Até</b></label>
                <input type="date" name="data_ate" class="w3-input w3-border" value="<?=$optionDataAte?>">
              </div>
              <div class="w3-col l2">
                <label class="w3-margin-top"><b>Referência</b></label>
                <input type="text" name="referencia" class="w3-input w3-border" value="" placeholder="Ex: R123">
              </div>
              <div class="w3-col l2">
                <label class="w3-margin-top"><b>Pesquisar</b></label>
                <button class="w3-button w3-block w3-red" id="inserir_cidade"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>
        
        <br>
        <div class="w3-responsive w3-card">
          <div class="w3-white" style="height: 45vh;overflow: auto;">
            <table class="w3-table w3-bordered w3-hoverable w3-centered">
              <tr class="w3-red">
                <th>Status</th>
                <th>Data</th>
                <th>Referência</th>
                <th>Tipo</th>
                <th>Mesa / Viagem</th>
              </tr>
            <?php 
            if($comandas == FALSE): echo '';?>
            <?php else:
            foreach ($comandas as $comanda): ?>
            <tr onclick="visualizarComanda(<?=$comanda->id_comanda?>)">
              <td><?=($comanda->status == 1 ? "Aberta" : "Fechada")?></td>
              <td class="w3-opacity"><?=$comanda->data_comanda.' às '.$comanda->hora_comanda?></td>
              <td><?=$comanda->ref_comanda?></td>
              <td><?=($comanda->tipo_comanda == 1 ? "Mesa" : "Viagem")?></td>
              <td><?=($comanda->nome_mesa != null ? 'Mesa ' . $comanda->nome_mesa : $comanda->nome_cliente)?></td>
            </tr>
            <?php endforeach; endif; ?>
            </table>
          </div>
          <p class="w3-right" style="padding-right: 24px">Quantidade: <?=0?></p>
        </div>
    </div>
  </div>
</div>


<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/comanda/main.js');?>"></script>