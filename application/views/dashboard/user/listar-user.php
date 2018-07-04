<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container w3-cell-row" style="padding-top:22px">
    <h5><i class="fa fa-user-o"></i><b> Usuários</b></h5>
  </header>
    <div class="w3-row-padding w3-margin-bottom">
      
      <div class="w3-quarter" onclick="document.getElementById('cadastro').style.display='block'">
        <a href="#">
          <div class="w3-container w3-red w3-padding-16 w3-center">
            <i class="fa fa-user-plus w3-xxxlarge"></i>
            <div class="w3-clear"></div>
            <h4>Novo Usuário</h4>
          </div>
        </a>
      </div>

      <div class="w3-quarter">
        <div class="w3-container w3-blue w3-padding-16 w3-center">
          <div><i class="fa fa-check-circle-o w3-xxxlarge"></i></div>
          <div class="w3-clear"></div>
          <h4>Permissões</h4>
        </div>
      </div>
      
      <div class="w3-quarter">
        <a href="admin/usuarios" style="">
          <div class="w3-container w3-teal w3-text-white w3-padding-16 w3-center">
            <i class="fa fa-user-times w3-xxxlarge"></i>
            <div class="w3-clear"></div>
            <h4>Restaurar</h4>
          </div>
        </a>
      </div>

      <div class="w3-quarter">
        <a href="../admin">
          <div class="w3-container w3-orange w3-text-white w3-padding-16 w3-center">
            <i class="fa fa-th w3-xxxlarge"></i>
            <div class="w3-clear"></div>
            <h4>Menu Principal</h4>
          </div>
        </a>
      </div>

    </div>

  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
        <div class="w3-container w3-card w3-white w3-padding w3-padding-32">
            <div class="w3-col" style="width:80%">
                <input type="search" name="pesquisa" class="w3-input" placeholder="Buscar Usuarios">      
            </div>
            <div class="w3-rest w3-center">
                <button class="w3-button w3-mobile w3-teal" id="inserir_cidade"><i class="fa fa-search"></i></button>
            </div>
        </div>
        
        <br>
        <div class="w3-responsive w3-card">
            <table class="w3-table w3-hoverable w3-centered w3-white">
              <tr class="w3-teal">
                <th>Editar</th>
                <th>Nome</th>
                <th>User</th>
                <th>Permissão</th>
              </tr>
            <?php foreach ($users as $usuarios): ?>
            <tr onclick="editarUsuario('<?=$usuarios->id_usuario?>','<?=$usuarios->user?>','<?=$usuarios->nome?>','<?=$usuarios->id_tipo_usuario?>')">
                <td><button type="button" class="w3-button" href="#"><i class="fa fa-pencil-square-o"></i></button></td>
                <td><?=$usuarios->nome?></td>
                <td><?=$usuarios->user;?></td>
                <td><?=$usuarios->ds_tipo_usuario;?></td>
            </tr>
            <?php endforeach ?>
            </table>
        </div>
    </div>
  </div>
</div>

 <div id="cadastro" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

      <div class="w3-center"><br>
        <span onclick="document.getElementById('cadastro').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
      </div>

      <form class="w3-container" method="POST" action="" id="inserir">
        <div class="w3-section">
          <label><b>Nome</b></label>
          <input class="w3-input" type="text" placeholder="Insira o nome usuario" id="nome" required minlength="4">
          <br>
          <label class="w3-margin-top"><b>Username</b></label>
          <input class="w3-input" type="text" placeholder="Insira o Username" id="user" required minlength="4">
          <br>
          <label class="w3-margin-top"><b>Senha</b></label>
          <input class="w3-input" type="password" placeholder="Insira a senha" id="senha" required minlength="6">
          <br>
          <label class="w3-margin-top"><b>Permissão</b></label>
          <select class="w3-select" id="permissao" required>
              <option value="3">Moderador</option>
              <option value="2">Editor</option>
              <option value="1">Administrador</option>
          </select>
          <button class="w3-button w3-block w3-teal w3-section w3-padding" type="submit">Cadastrar</button>
        </div>
      </form>

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        <button onclick="document.getElementById('cadastro').style.display='none'" type="button" class="w3-button w3-block w3-gray">Cancel</button>
      </div>

    </div>
  </div>

  <div id="edicao" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
      <input type="hidden" id="idUser" value="">
      <div class="w3-container"><br>
        <span class="w3-button w3-large w3-red w3-left" onclick="deletarItem()" title="Deletar"><i class="fa fa-trash-o"></i> Excluir</span>
        <span class="w3-button w3-large w3-teal w3-right" onclick="alerarSenha()" title="Alterar Senha"><i class="fa fa-key"></i> Alterar Senha</span>
      </div>

      <form class="w3-container" method="post" action="" id="editar">
        <div class="w3-section">
          <label><b>Nome</b></label>
          <input class="w3-input" type="text" placeholder="Insira o nome usuario" id="editarNome" value="" required>
          <br>
          <label class="w3-margin-top"><b>Username</b></label>
          <input class="w3-input" type="text" placeholder="Insira o Username" id="editarUser" value="" required>
          <br>
          <label class="w3-margin-top"><b>Permissão</b></label>
          <select class="w3-select" id="editarPermissao">
              <option value="3" >Moderador</option>
              <option value="2">Editor</option>
              <option value="1">Administrador</option>
          </select>
          <button class="w3-button w3-block w3-teal w3-section w3-padding" type="submit">Editar</button>
        </div>
      </form>

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        <button onclick="document.getElementById('edicao').style.display='none'" type="button" class="w3-button w3-block w3-gray">Cancelar</button>
      </div>

    </div>
  </div>
