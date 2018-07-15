<div class="dash-video">
  <video autoplay muted loop id="myVideo">
    <source src="<?=base_url("assets/img/banner.mp4")?>" type="video/mp4">
    </video>
  </div>
  <div>
    <div class="w3-container w3-padding w3-display-middle w3-center" id="container-login">
      <div class="w3-row-padding">
        <div class="w3-col l6" id="container-img">
          <div class="w3-display-container w3-text-white">
            <div class="w3-center w3-padding-32" style="width: 100%;text-shadow: 0px 8px 9px rgb(0, 0, 0);">
              <img class="w3-image" src="<?=base_url("assets/img/icon.svg")?>">
              <h1><b>LeCard</b></h1>
              <p class=""><b>Uma Comanda virtual para seu negócio real</b></p>
              <div class="w3-center w3-padding-16 w3-margin-top w3-hide-large">
                <button class="w3-button w3-block w3-hover-white" id="btn-login" onclick='$("#modal-login").css("display","block")'>LOGIN</button>
              </div>
            </div>
          </div>
        </div>
        <div class="w3-col l6 w3-hide-small">
          <div class="w3-card-4 w3-round w3-white w3-padding w3-padding-16" id="card-login">
            <h4>Acesso ao Dashboard</h4>
            <br>
            <form method="POST" action="<?php echo base_url('login') ?>">
              <div class="w3-margin-top">
                <label>Usuário</label>
                <input type="text" name="email" class="w3-input w3-border" placeholder="Digite seu Usuário">
              </div>
              <?php if($error == 1): ?>
                <div class="w3-text-red">Usuario inválido</div>
              <?php endif; ?>
              <div class="w3-margin-top">
                <label>Senha</label>
                <input type="password" name="senha" class="w3-input w3-border" placeholder="Digite sua senha">
              </div>
              <?php if($error == 2): ?>
                <div class="w3-text-red">Senha incorreta</div>
              <?php endif; ?>
              <hr>
              <div class="w3-center w3-padding-16 w3-margin-top">
                <button class="w3-button w3-round w3-border w3-block">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-login" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-round w3-animate-zoom w3-padding-large">
      <i onclick="document.getElementById('modal-login').style.display='none'" class="fa fa-remove w3-button w3-right w3-transparent"></i>
      <h4>Acesso ao Dashboard</h4>
      <br>
      <form method="POST" action="<?php echo base_url('login') ?>">
        <div class="w3-margin-top">
          <label>Usuário</label>
          <input type="text" name="email" class="w3-input w3-border" placeholder="Digite seu Usuário">
        </div>
        <?php if($error == 1): ?>
          <div class="w3-text-red">Usuario inválido</div>
        <?php endif; ?>
        <div class="w3-margin-top">
          <label>Senha</label>
          <input type="password" name="senha" class="w3-input w3-border" placeholder="Digite sua senha">
        </div>
        <?php if($error == 2): ?>
          <div class="w3-text-red">Senha incorreta</div>
        <?php endif; ?>
        <hr>
        <div class="w3-center w3-padding-16 w3-margin-top">
          <button class="w3-button w3-black w3-round w3-block">Login</button>
        </div>
      </form>
    </div>
  </div>
