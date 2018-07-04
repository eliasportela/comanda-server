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

#Produtores
$route['admin/produtor'] = 'Produtor';
$route['admin/produtor/(:num)'] = 'Produtor/Editar';
$route['admin/produtor/cadastro'] = 'Produtor/Cadastro';

$route['admin/api/produtor/(:num)']['get'] = 'Produtor/Get';
$route['admin/api/produtor/id/(:num)']['get'] = 'Produtor/GetId';
$route['admin/api/produtor']['post'] = 'Produtor/Register';
$route['admin/api/produtor/editar/(:num)']['post'] = 'Produtor/Edit';
$route['admin/api/produtor/remover/(:num)'] = 'Produtor/Remove';

#Propriedades
$route['admin/api/propriedade/(:num)']['get'] = 'Propriedade/Get';
$route['admin/api/propriedade/id/(:num)']['get'] = 'Propriedade/GetId';
$route['admin/api/propriedade']['post'] = 'Propriedade/Register';
$route['admin/api/propriedade/(:num)']['post'] = 'Propriedade/Edit';
$route['admin/api/propriedade/remover/(:num)'] = 'Propriedade/Remove';

#Safras
$route['admin/api/safra-previsao/(:num)']['get'] = 'Safra/GetPrevisao';
$route['admin/api/safra-fechamento/(:num)']['get'] = 'Safra/GetFechamento';
$route['admin/api/safra-cafe/(:num)']['get'] = 'Safra/GetCafe';
$route['admin/api/safra-previsao/remover/(:num)'] = 'Safra/DeletePrevisao';
$route['admin/api/safra-fechamento/remover/(:num)'] = 'Safra/DeleteFechamento';
$route['admin/api/safra-cafe/remover/(:num)'] = 'Safra/DeleteCafe';

#Contato
$route['admin/contatos'] = 'Contato';
$route['admin/notificacao-contato'] = 'Contato/NotificacaoContato';
$route['admin/visualizacao-contato'] = 'Contato/VisualizacaoContato';

#Util
$route['admin/api/cidade'] = 'Util/GetCidades';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
