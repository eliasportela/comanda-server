<style type="text/css">
h6{
  margin: 3px 0;
}
img{
  max-width: 100%;
  margin-top: 12px
}
.w3-centered tr td {
  padding: 13px;
}
.w3-table td{
  vertical-align: middle;
}
.w3-modal-content .modal {
  min-width: 1024px;
}
</style>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <header class="w3-container w3-cell-row" style="padding:30px 10px 10px">
    <span class="w3-large"><i class="fa fa fa-tag fa-fw"></i>Produto > <?=$title?></span>
  </header>
  <div style="margin: 0 16px">
    <form method="POST" action="" id="<?=$idFormulario?>">

      <!--Edicao-->
      <?php if ($editar): ?>
        <input type="hidden" id="id_produto" name="id_produto" value="<?=$produto?>">
      <?php endif;?>

      <div class="w3-padding" style="margin:0 -16px">
        <div>
          <a class="w3-button w3-red" href="<?=base_url('admin/produto/')?>"><i class="fa fa-chevron-left"></i> Voltar</a>
          <button class="w3-button w3-red w3-right" type="submit" style="margin-left: 12px; width: 150px"><i class="fa fa-check"></i> Salvar</button>
          <a class="w3-button w3-red w3-right" href="<?=base_url('admin/produto/cadastro')?>"><i class="fa fa-plus"></i> Novo Produto</a>
        </div>
        <br>
        <div class="w3-responsive w3-card w3-white w3-padding-32">
          <div class="w3-row-padding">
            <div class="w3-col <?=$coluna?> w3-margin-top">
              <label>Categoria do Produto</label>
              <select class="w3-select w3-white w3-border" id="id_categoria" name="id_categoria">
                <?php foreach ($categoria_produto as $c):?>
                  <option value="<?=$c->id_categoria?>"><?=$c->nome_categoria?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="w3-col <?=$coluna?> w3-margin-top">
              <label for="nome_produto">Nome Produto</label>
              <input type="text" class="w3-input w3-border" placeholder="Nome Produto" id="nome_produto" name="nome_produto" required>
            </div>
            <?php if (!$editar): ?>
            <div class="w3-col <?=$coluna?> w3-margin-top">
              <label for="email">Gerar Referência</label>  
              <select class="w3-select w3-white w3-border" id="gerar_referencia" name="gerar_referencia" onchange="toogleGerarReferencia()">
                <option value="1">Sim</option>
                <option value="0">Referência manual</option>
              </select>
            </div>
            <?php endif;?>
            <div class="w3-col <?=$coluna?> w3-margin-top">
              <label for="referencia">Referência</label>
              <input type="text" class="w3-input w3-border" placeholder="Informe a referência" id="referencia" name="referencia" <?=(!$editar ? "disabled" : "")?> >
            </div>
          </div>
        </div>
      </div>
    </form>
    <hr>

    <?php if ($editar): ?>
    <div class="w3-margin-bottom">
      <span class="w3-large w3-padding">
        <i class="fa fa-tags"></i>
        Composição
      </span>
    </div>

    <div class="w3-card w3-white w3-padding w3-padding-16">
      <div class="w3-row-padding">
        <div class="w3-col m12">
          <h5>Inserir Produto</h5>
        </div>
        <div class="w3-col m8">
          <label>Selecione o produto a ser inserido</label>
          <select class="w3-select">
            <option>Teste</option>
          </select>
        </div>
        <div class="w3-col m4">
          <label>Inserir Produto</label>
          <button class="w3-button w3-black w3-dark-gray w3-block" type="button" onclick="addItemTabela()"><i class="fa fa-plus"></i> Adicionar Produto</button>
        </div>
      </div>
      <br>
      <div class="w3-responsive" style="min-height: 150px">
        <table class="w3-table w3-bordered w3-centered" id="tableItens">
          <thead>
            <tr class="w3-red">
              <th style="width: 30%">Referência</th>
              <th style="width: 40%">Nome</th>
              <th style="width: 30%">Remover</th>
            </tr>
          </thead>
          <tbody id="itens">
          </tbody>
        </table>
      </div>
    </div>
    <!-- tsetset -->
    <hr>
    <div>
      <span class="w3-large w3-padding">
        <i class="fa fa-usd"></i>
        Tabela de Preços
      </span>
      <button class="w3-button w3-dark-gray w3-right" type="button" onclick="addSafraCafe()"><i class="fa fa-plus"></i> Adicionar Tabela</button>
    </div>
    <br>
    <div class="w3-display-container w3-responsive w3-card w3-white w3-margin-bottom" style="min-height: 150px">
      <table class="w3-table w3-bordered w3-centered ">
        <thead>
          <tr class="w3-red">
            <th style="width: 30%">Tabela</th>
            <th style="width: 40%">Valor</th>
            <th style="width: 30%">Remover</th>
          </tr>
        </thead>
        <tbody id="tabelaPreco">
        </tbody>
      </table>
    </div>
    <hr>
    <div>
      <span class="w3-large w3-padding">
        <i class="fa fa-exclamation-triangle"></i>
        Danger Zone
      </span>
    </div>
    <br>
    <div class="w3-responsive w3-card w3-white w3-border w3-margin-bottom" style="min-height: 70px">
      <button type="button" class="w3-button w3-red w3-margin" onclick="deletarProdutorId(<?=$produto?>)">
        <i class="fa fa-trash-o"></i>
        Deletar este Produto
      </button>
    </div>
    <?php endif;?>

  </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/produto/cadastrarProduto.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/produto/editarProduto.js');?>"></script>