<div id="modalComanda" class="w3-modal" style="padding: 60px 0">
  <div class="w3-modal-content modal w3-card-4 w3-animate-left">
    <form method="POST" action="" id="inserirPropriedade">
      <div class="w3-container w3-padding-16 w3-large w3-border-bottom">
        <i class="fa fa-building"></i> <span id="titleForm">Cadastro de Propriedade</span>
        <span class="w3-right" onclick="closeModalPropriedade()" style="cursor: pointer;"><i class="fa fa-times"></i></span>
      </div>
      <div class="w3-section" >
        <div class="w3-row-padding">
            <div class="w3-col l2">
                <label class="w3-margin-top"><b>Referência</b></label>
                <input type="text" name="referencia" class="w3-input w3-border" value="" placeholder="Ex: R123">
            </div>
            <div class="w3-col l2">
                <label class="w3-margin-top"><b>Status</b></label>
                <select class="w3-select w3-border w3-white" name="status">
                    <option value="1">Aberta</option>
                    <option value="0">Fechada</option>
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
                <label class="w3-margin-top"><b>Data Comanda</b></label>
                <input type="date" name="data_de" class="w3-input w3-border" value="">
            </div>
        </div>
        <div class="w3-container">
          <div class="w3-padding-16">
            <span class="w3-large"><b>Previsão de Safra</b></span>
          </div>
          <table class="w3-table w3-bordered w3-centered">
            <thead>
              <tr class="w3-red">
                <th>Ano</th>
                <th>Quantidade (sacas)</th>
                <th>Remover</th>
              </tr>
            </thead>
            <tbody id="tabelaSafraPrevisao">
            </tbody>
          </table>
          <button class="w3-button w3-gray w3-right w3-block" type="button" id="btnAddSafra" style="margin:12px 0" onclick="addSafraPrevisao()"><i class="fa fa-plus"></i> Adicionar previsão</button>
        </div>
      </div>
      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        <button onclick="closeModalPropriedade()" type="button" class="w3-button w3-gray" style="width: 150px">
          <i class="fa fa-times"></i>
          Fechar
        </button>
        <button type="submit" class="w3-button w3-red w3-right" style="width: 150px">
          <i class="fa fa-check"></i>
          Salvar
        </button>
      </div>
    </form>
  </div>
</div>

</div>