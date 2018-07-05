<style type="text/css">
h6{
  margin: 3px 0;
}
.table-produtor{
  min-height: 200px;
  position: relative;
}
.w3-centered tr td {
  cursor: pointer;
  padding: 13px
}
.w3-table td{
  vertical-align: middle;
}

</style>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <!-- Header -->
  <header class="w3-container w3-cell-row" style="padding-top:22px">
    <span class="w3-xlarge"><i class="fa fa fa-edit fa-fw"></i>Produtos</span>
    <button onclick="window.location.href='<?=base_url("admin/produtor/cadastro")?>'" class="w3-button w3-round w3-red w3-right"><i class="fa fa-plus"></i> Cadastrar</button>  
    <button class="w3-button w3-red w3-right" style="margin-right: 12px" onclick="limparSearch(1)">Limpar Filtros</button>
  </header>
  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-container w3-card w3-white w3-padding w3-padding-32">
        <div class="w3-row-padding w3-center">
          <div class="w3-col l3">
            <label class="w3-margin-top"><b>Referência</b></label>
            <input type="text" class="w3-input w3-border" placeholder="Referencia do Produto"  id="referenciasearch">
          </div>
          <div class="w3-col l3">
            <label class="w3-margin-top"><b>Nome</b></label>
            <input type="text" class="w3-input w3-border" placeholder="Nome do Produto"  id="nomesearch">
          </div>
          <div class="w3-col l3">
            <label class="w3-margin-top"><b>Tipo</b></label>
            <select class="w3-select w3-border w3-white" id="tiposearch">
              <option value="0">Todas</option>
              <?php foreach ($categoria_produto as $c) { ?>
                <option value="<?=$c->id_categoria?>"><?=$c->nome_categoria?></option>
              <?php } ?>
            </select>
          </div>
          <div class="w3-col l3">
            <label class="w3-margin-top"><b>Pesquisar</b></label>
            <button class="w3-button w3-block w3-red" onclick="getProdutos(1)"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </div>
      <br>
      <div class="w3-responsive w3-card">
        <div class="w3-white table-produtor">
          <table class="w3-table w3-hoverable w3-bordered w3-centered">
            <thead>
              <tr class="w3-red">
                <th style='width:5%'></th>
                <th style='width:15%'>Referência</th>
                <th style='width:40%'>Nome</th>
                <th style='width:40%'>Categoria</th>
              </tr>
            </thead>
            <tbody id="produtos">
              <tr></tr>
            </tbody>
          </table>
          <div class="w3-display-middle w3-center" id="naoencontrado">
            <h2><i class="fa fa-frown-o"></i></h2>
            <span>Nenhum produto encotrado</span>
          </div>
        </div>
        <div class="w3-bar w3-border">
          <button onclick="pagination(0)" id="btnanterior" class="w3-button">&#10094; Anterior</button>
          <button onclick="pagination(1)" id="btnproximo" class="w3-button w3-right">Próximo &#10095;</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/produto/main.js');?>"></script>