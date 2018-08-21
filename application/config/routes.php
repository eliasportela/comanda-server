<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Rotas do Tamplate
$route['default_controller'] = 'Dashboard';

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

#Comanda
$route['admin/comanda'] = 'Comanda';
$route['admin/comanda/(:num)'] = 'Comanda/Editar';
$route['admin/comanda/cadastro'] = 'Comanda/Cadastro';


//Pedido
$route['admin/pedidos']['get'] = 'Pedido';


/*
 * APIS do sistema
 */

//Autenticação do Usuário
$route['api/autenticar']['post'] = 'User/getAutenticacao';

// Produto

$route['api/produtos/precos/(:num)/(:any)']['get'] = 'Produto/getProdutosTabelaPreco';
$route['api/produtos/itens/(:any)']['get'] = 'Produto/GetProdutosItens';

$route['admin/api/produto/id/(:num)']['get'] = 'Produto/GetId'; //REMOCOES
$route['admin/api/produtos-categoria-tabela/(:num)']['get'] = 'Produto/getProdutosCategoriaTabela';
$route['admin/api/produto']['post'] = 'Produto/Register';
$route['admin/api/produto/editar']['post'] = 'Produto/Edit';
$route['admin/api/produto/remover/(:num)'] = 'Produto/Remove';

#Produto Preço e Ingredientes

$route['admin/api/produto-tabela/remover/(:num)/(:num)'] = 'Safra/DeletePrevisao';
$route['admin/api/produto-tabela/remover/(:num)/(:num)'] = 'Safra/DeleteFechamento';

//Comanda

$route['api/comandas/(:num)/(:any)']['get'] = 'Comanda/GetComandas';
$route['api/comandas/prudutos/(:any)/(:num)']['get'] = 'Comanda/GetProdutosComanda';
$route['api/comandas/inserir/(:any)']['post'] = 'Comanda/RegisterComanda';
$route['api/comandas/editar/(:any)']['post'] = 'Comanda/EditComanda';
$route['api/comandas/inserir/produtos/(:any)']['post'] = 'Comanda/InserirProdutoComanda';
$route['api/comandas/editar/produtos/(:num)/(:any)']['post'] = 'Comanda/EditarProdutoComanda';
$route['api/comandas/remover/produtos/(:any)']['post'] = 'Comanda/RemoveProdutoComanda';

// Pedidos

$route['api/pedidos/(:num)/(:any)'] = 'Pedido/GetPedidos';
$route['api/pedidos/editar/(:any)']['post'] = 'Pedido/EditPedido';

// Cardapio
$route['api/cardapio/(:any)']['get'] = 'Cardapio/ListarCardapio';

//Util

$route['api/categorias/(:any)']['get'] = 'Util/GetCategoriasProdutos';
$route['admin/api/tabela-categoria/(:num)'] = 'Util/GetTabelaCategoria';

// 404 e erros

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
