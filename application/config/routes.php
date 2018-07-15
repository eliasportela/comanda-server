<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Rotas do Tamplate
$route['default_controller'] = 'Dashboard';
$route['api/solicitar-contato'] = 'Site/SolicitarContato';

# Rotas Do Dashboard
$route['admin'] = 'Dashboard';
$route['login'] = 'User/login';

#Usuario
$route['login'] = 'User/Login';
$route['logout'] = 'User/Logout';
$route['alterar-senha'] = 'User/UpdatePassw';
$route['profile-editar'] = 'User/EditarMyUser';
$route['profile/visualizacao'] = 'User/Visualizacao';

#CRUD  usuario
$route['admin/usuarios'] = 'User/ListarUser';
$route['admin/cadastro-usuario'] = 'User/Register';
$route['admin/editar-usuario'] = 'User/EditarUser';
$route['admin/remover-usuario'] = 'User/RemoverUser';

#Produto
$route['admin/produto'] = 'Produto';
$route['admin/produto/(:num)'] = 'Produto/Editar';
$route['admin/produto/cadastro'] = 'Produto/Cadastro';

$route['admin/api/produto/(:num)']['get'] = 'Produto/Get';
$route['admin/api/produto/id/(:num)']['get'] = 'Produto/GetId';
$route['admin/api/produtos-categoria/(:num)']['get'] = 'Produto/getProdutosCategoria';
$route['admin/api/produtos-categoria-tabela/(:num)']['get'] = 'Produto/getProdutosCategoriaTabela';
$route['admin/api/produto']['post'] = 'Produto/Register';
$route['admin/api/produto/editar/(:num)']['post'] = 'Produto/Edit';
$route['admin/api/produto/remover/(:num)'] = 'Produto/Remove';

#Produto Preço e Itens (id-produto/id-tabela)
$route['admin/api/produto-tabela/remover/(:num)/(:num)'] = 'Safra/DeletePrevisao';
$route['admin/api/produto-tabela/remover/(:num)/(:num)'] = 'Safra/DeleteFechamento';

#Comanda
$route['admin/comanda'] = 'Comanda';
$route['admin/comanda/(:num)'] = 'Comanda/Editar';
$route['admin/comanda/cadastro'] = 'Comanda/Cadastro';

$route['admin/api/comandas']['get'] = 'Comanda/Comandas';
$route['admin/api/comanda/id/(:num)']['get'] = 'Comanda/ComandaId';
$route['admin/api/comanda/ref/(:any)']['get'] = 'Comanda/ComandaRef';
$route['admin/api/comanda/inserir-comanda']['post'] = 'Comanda/InserirComanda';
$route['admin/api/comanda-prudutos/(:num)']['get'] = 'Comanda/ProdutosComanda';
$route['admin/api/comanda-pruduto/(:num)']['get'] = 'Comanda/ProdutoComandaId';
$route['admin/api/comanda/inserir-produto']['post'] = 'Comanda/InserirProdutoComanda';

//Pedido
$route['admin/api/pedidos-comanda/(:num)']['get'] = 'Pedido/PedidosComanda';

$route['admin/api/cardapio/(:num)']['get'] = 'Cardapio/ListarCardapio';

#Util
$route['admin/api/categoria-produtos'] = 'Util/GetCategoriasProdutos';
$route['admin/api/tabela-categoria/(:num)'] = 'Util/GetTabelaCategoria';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
