<style type="text/css">
  h6{
    margin: 3px 0;
  }
  .w3-centered tr td {
    padding: 13px;
  }
  .w3-table td{
    vertical-align: middle;
  }

</style>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <!-- Header -->
  <header class="w3-container w3-cell-row" style="padding-top:22px">
    <span class="w3-xlarge"><i class="fa fa fa-phone fa-fw"></i><b> Contatos</b></span>
    <br class="w3-hide-large">
    <a class="w3-button w3-black w3-right" href="<?=base_url('admin/contatos')?>">Limpar Filtros</a>
    <a class="w3-button w3-black w3-right" style="margin-right: 12px" href="#" onclick="location.reload();">Atualizar página</a>
  </header>
  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
        <div class="w3-container w3-card w3-white w3-padding w3-padding-32">
          <form method="GET" action="<?=base_url("admin/contatos")?>">
            <div class="w3-row-padding w3-center">
              <div class="w3-col l2">
                <label class="w3-margin-top"><b>Visualização</b></label>
                <select class="w3-select w3-border" name="visualizado">
                  <option value="0">Novos Contatos</option>
                  <option value="2" <?php if($optionVisualizado == 2): echo "selected"; endif; ?>>Visualizados</option>
                  <option value="1" <?php if($optionVisualizado == 1): echo "selected"; endif; ?>>Todos os Contatos</option>
                </select>
              </div>
              <div class="w3-col l4">
                <label class="w3-margin-top"><b>De</b></label>
                <input type="date" name="data_de" class="w3-input w3-border" value="<?=$optionDataDe?>">
              </div>
              <div class="w3-col l4">
                <label class="w3-margin-top"><b>Até</b></label>
                <input type="date" name="data_ate" class="w3-input w3-border" value="<?=$optionDataAte?>">
              </div>
              <div class="w3-col l2">
                <label class="w3-margin-top"><b>Pesquisar</b></label>
                <button class="w3-button w3-block w3-black" id="inserir_cidade"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>
        
        <br>
        <div class="w3-responsive w3-card">
          <div class="w3-white" style="height: 45vh;overflow: auto;">
            <table class="w3-table w3-bordered w3-centered">
              <tr class="w3-black">
                <th style="width:8%"><i class="fa fa-eye"></i></th>
                <th>Data</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
              </tr>
            <?php 
            if($contatos == FALSE): echo '';?>
            <?php else:
            foreach ($contatos as $contato): ?>
            <tr>
              <td><input class="w3-check w3-black" type="checkbox" onchange="visualizarContato(<?=$contato->id_contato?>)" <?php if($contato->visualizado == 1): echo 'checked'; else: echo""; endif;?>></td>
              <td class="w3-opacity"><?=$contato->data_contato.' às '.$contato->hora_contato?></td>
              <td><?=$contato->nome_contato?></td>
              <td><?=$contato->email?></td>
              <td><?=$contato->telefone?></td>
            </tr>
            <?php endforeach; endif; ?>
            </table>
          </div>
          <p class="w3-right" style="padding-right: 24px">Quantidade: <?=$qtd[0]->qtd?></p>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/contato/main.js');?>"></script>