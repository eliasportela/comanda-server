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
.w3-table tr{
  cursor: pointer;
}
.w3-table td{
  vertical-align: middle;
}
.w3-modal-content .modal {
  min-width: 1024px;
}

.w3-red:hover{
  background-color: red!important;
  color: #fff!important;
}

</style>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <header class="w3-container w3-cell-row" style="padding:30px 10px 10px">
    <span class="w3-large"><i class="fa fa fa-user fa-fw"></i>Produtores > <?=$title?></span>
  </header>
  <div style="margin: 0 16px">
    <form method="POST" action="" id="<?=$idFormulario?>">

      <!--Edicao-->
      <?php if (isset($produtor)): ?>
        <input type="hidden" id="id_produtor" name="id_produtor" value="<?=$produtor?>">
      <?php endif;?>

      <div class="w3-padding" style="margin:0 -16px">
        <div>
          <a class="w3-button w3-black" href="<?=base_url('admin/produtor')?>"><i class="fa fa-chevron-left"></i> Voltar</a>
          <button class="w3-button w3-black w3-right" type="submit" style="margin-left: 12px; width: 150px"><i class="fa fa-check"></i> Salvar</button>
          <a class="w3-button w3-black w3-right" href="<?=base_url('admin/produtor/cadastro')?>"><i class="fa fa-plus"></i> Novo Produtor</a>
        </div>
        <br>
        <div class="w3-responsive w3-card w3-white w3-padding-32">
          <div class="w3-row-padding">
            <div class="w3-col m12">Dados Pessoais</div>
            <div class="w3-col m3 w3-margin-top">
              <label for="nome_produtor">Nome Produtor</label>
              <input type="text" class="w3-input w3-border" placeholder="Nome" id="nome_produtor" name="nome_produtor" required>
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label for="email">E-mail</label>
              <input type="text" class="w3-input w3-border" placeholder="E-mail" id="email" name="email">
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label for="certificados">Certificados</label>
              <input type="text" class="w3-input w3-border" placeholder="Certificados" id="certificados" name="certificados">
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label for="telefone">Telefone</label>
              <input type="text" class="w3-input w3-border" placeholder="Ex: (35)3535-0000" id="telefone" name="telefone">
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label>Tipo de Pessoa</label>
              <select class="w3-select w3-white w3-border" id="tipo_pessoa" name="id_tipo_pessoa" onchange="selectTipoPessoa()">
                <?php foreach ($t_pessoas as $p):?>
                  <option value="<?=$p->id_tipo_pessoa?>"><?=$p->nome_tipo_pessoa?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label id="label-cpf_cnpj">CPF</label>
              <input type="text" class="w3-input w3-border" placeholder="Documento do produtor" id="cpf_cnpj" name="cpf_cnpj">
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label id="label-ie_rg">RG</label>
              <input type="text" class="w3-input w3-border" placeholder="Documento do produtor" id="rg_inscricao_estadual" name="rg_inscricao_estadual">
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label>Numéros de dependentes</label>
              <input type="number" class="w3-input w3-border" placeholder="Qtd Membros da família" id="membros_familia" name="membros_familia">
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label>Data Nascimento</label>
              <input type="date" class="w3-input w3-border" placeholder="Data Nascimento" id="data_nascimento" name="data_nascimento" required>
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label>Escolaridade</label>
              <select class="w3-select w3-white w3-border" id="escolaridade" name="escolaridade">
                <option value="0">Não Informado</option>
                <option value="1">Analfabeto</option>
                <option value="2">Educação infantil</option>
                <option value="3">Fundamental</option>
                <option value="4">Médio</option>
                <option value="5">Superior (Graduação)</option>
                <option value="6">Pós-graduação </option>
                <option value="7">Mestrado</option>
                <option value="8">Doutorado.</option>
              </select>
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label for="foto_file">Foto (JPEG,PNG,JPG)</label>
              <button type="button" class="w3-button w3-black w3-block" onclick="modalFoto()"><?=$btnFoto?></button>
              <input type="file" class="w3-input" id="foto_file" name="foto_file" accept="image/*" style="display: none;">
            </div>
            <div class="w3-col m3 w3-margin-top">
              <label>Comprovante Bancário</label>
              <button type="button" class="w3-button w3-black w3-block" onclick="modalComprovante()"><?=$btnFoto?></button>
              <input type="file" class="w3-input" id="comprovante_file" name="comprovante_file" accept="image/*" style="display: none;">
            </div>

            <div class="w3-col m12 w3-margin-top">Correspondência</div>

            <div class="w3-col m4 w3-margin-top">
              <input type="text" class="w3-input w3-border" placeholder="Endereço" id="endereco" name="endereco" required>
            </div>
            <div class="w3-col m2 w3-margin-top">
              <input type="text" class="w3-input w3-border" placeholder="Número" id="numero" name="numero" required>
            </div>
            <div class="w3-col m3 w3-margin-top">
              <input type="text" class="w3-input w3-border" placeholder="Bairro" id="bairro" name="bairro" required>
            </div>
            <div class="w3-col m3 w3-margin-top">
              <input type="text" class="w3-input w3-border" placeholder="Complemento" id="complemento" name="complemento">
            </div>
            <div class="w3-col m4 w3-margin-top">
              <label>Estado</label>
              <select class="w3-select w3-border" onchange="getCidades('selectEstados','selectCidades')" id="selectEstados">
                <option value="0">Selecione</option>
                <?php foreach ($estados as $estado):?>
                  <option value="<?=$estado->id_estado?>"><?=$estado->nome_estado?></option>
                <?php endforeach;?>
              </select>
            </div>
            <div class="w3-col m4 w3-margin-top">
              <label>Cidade</label>
              <select class="w3-select w3-border" id="selectCidades" name="id_cidade" disabled="" required>
                <option value="0">Selecione o Estado</option>
              </select>
            </div>
            <div class="w3-col m4 w3-margin-top">
              <label>CEP</label>
              <input type="text" class="w3-input w3-border" placeholder="CEP" id="cep" name="cep">
            </div>
          </div>
        </div>
      </div>
    </form>
    <br>
    <div>
      <span class="w3-large w3-padding">
        <i class="fa fa-building"></i>
        Propriedades
      </span>
      <button class="w3-button w3-black w3-right" onclick="modalPropriedade()">
        <i class="fa fa-plus"></i>
        Nova Propriedade
      </button>
    </div>
    <br>
    <div class="w3-display-container w3-responsive w3-card w3-white w3-margin-bottom" style="min-height: 150px">
      <table class="w3-table w3-bordered w3-centered w3-hoverable">
        <thead>
          <tr class="w3-black">
            <th style="width: 5%"></th>
            <th style="width: 20%">Nome</th>
            <th style="width: 30%">Cidade</th>
            <th style="width: 20%">Estado</th>
            <th style="width: 25%">Remover</th>
          </tr>
        </thead>
        <tbody id="propriedades">
        </tbody>
      </table>
    </div>
    <br>

    <?php if (isset($produtor)): ?>
      <div>
        <span class="w3-large w3-padding">
          <i class="fa fa-exclamation-triangle"></i>
          Danger Zone
        </span>
      </div>
      <br>
      <div class="w3-responsive w3-card w3-white w3-border w3-margin-bottom" style="min-height: 70px">
        <button type="button" class="w3-button w3-red w3-round w3-margin" onclick="deletarProdutorId(<?=$produtor?>)">
          <i class="fa fa-trash-o"></i>
          Deletar este Produtor
        </button>
      </div>
    <?php endif;?>
  </div>
</div>

<div id="modalFoto" class="w3-modal" style="padding: 60px 0;">
  <div class="w3-modal-content w3-card-4 w3-animate-left" style="width: 400px!important">
    <div class="w3-container w3-padding-16">
      <div class="w3-container w3-padding-16 w3-large">
        <i class="fa fa-file"></i> Selecionar Foto do Produtor
        <span class="w3-right" onclick="closeModalFoto()" style="cursor: pointer;"><i class="fa fa-times"></i></span>
      </div>
      <div class="w3-border w3-padding w3-padding-32">
        <label class="w3-button w3-black w3-block" for="foto_file">Buscar</label>
        <div id="image-foto"></div>
        <img src="" id="image-foto-bd" style="display: none;">
      </div>
    </div>
    <div class="w3-padding-16 w3-padding">
      <button class="w3-button w3-black" onclick="closeModalFoto(1)" style="width: 180px">Cancelar</button>  
      <button class="w3-button w3-black w3-right" onclick="closeModalFoto()" style="width: 180px">Selecionar</button>
    </div>
  </div>
</div>

<div id="modalComprovante" class="w3-modal" style="padding: 60px 0;">
  <div class="w3-modal-content w3-card-4 w3-animate-left" style="width: 400px!important">
    <div class="w3-container w3-padding-16">
      <div class="w3-container w3-padding-16 w3-large">
        <i class="fa fa-file"></i> Selecionar Comprovante
        <span class="w3-right" onclick="closeModalComprovante()" style="cursor: pointer;"><i class="fa fa-times"></i></span>
      </div>
      <div class="w3-border w3-padding w3-padding-32">
        <label class="w3-button w3-black w3-block" for="comprovante_file">Buscar</label>
        <p class="w3-center" id="image-comprovante"></p>
        <div id="image-comprovante-bd" style="display: none;">
          <a href="" target="_blanck" id="view-comprovante" class="w3-button w3-black w3-block">Visualizar comprovante</a>
        </div>
      </div>
    </div>
    <div class="w3-padding-16 w3-padding">
      <button class="w3-button w3-black" onclick="closeModalComprovante(1)" style="width: 180px">Cancelar</button>  
      <button class="w3-button w3-black w3-right" onclick="closeModalComprovante()" style="width: 180px">Selecionar</button>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/produtor/files.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/produtor/cadastrarProdutor.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/dashboard/produtor/editarProdutor.js');?>"></script>