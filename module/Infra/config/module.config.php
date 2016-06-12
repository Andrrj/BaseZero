<?php


return array(
    'controllers' => array(
        'invokables' => array(
            'Infra\Controller\Infra' => 'Infra\Controller\InfraController',
            'Infra\Controller\Empresa' => 'Infra\Controller\EmpresaController',
            'Infra\Controller\Usuario' => 'Infra\Controller\UsuarioController',
            'Infra\Controller\Servicos' => 'Infra\Controller\ServicosController',
            'Infra\Controller\Papel' => 'Infra\Controller\PapelController',
            'Infra\Controller\Acao' => 'Infra\Controller\AcaoController',
            'Infra\Controller\Modulo' => 'Infra\Controller\ModuloController',
            'Infra\Controller\Log' => 'Infra\Controller\LogController',
       // 	'Infra\Controller\OAuth' => 'Infra\Controller\OAuthController',
        ),
    ),
     'router' => array(
        'routes' => array(
            'sobre' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '#sobre',
            				'defaults' => array(
//             						'controller' => 'Infra\Controller\Infra',
//             						'action'     => 'index',
            						'publico' => true,
            				),
            		),
            
            ),
            'como' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '#como',
            				'defaults' => array(
            						//'controller' => 'Infra\Controller\Infra',
            						//'action'     => 'index',
            						'publico' => true,
            				),
            		),
            
            ),
            'clientes' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '#clientes',
            				'defaults' => array(
            						//'controller' => 'Infra\Controller\Infra',
            						//'action'     => 'index',
            						'publico' => true,
            				),
            		),
            
            ),
            'contato' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '#contato',
            				'defaults' => array(
            						//'controller' => 'Infra\Controller\Infra',
            						//'action'     => 'index',
            						'publico' => true,
            				),
            		),
            
            ),
            'infra' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '/infra[/:action][/:codigo][/]',
            				'defaults' => array(
            						'controller' => 'Infra\Controller\Infra',
            						'action'     => 'index',
            				        'publico' => true,
            				),
            		),
            
            ),
           
            'usuario' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '/usuario[/:action][/:codigo][/]',
            				'defaults' => array(
            						'controller' => 'Infra\Controller\Usuario',
            						'action'     => 'index',
            						'publico' => false,
            				),
            		),
            
            ),
            'servicos' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '/servicos[/:action][/:codigo][/]',
            				'defaults' => array(
            						'controller' => 'Infra\Controller\servicos',
            						'action'     => 'index',
            						'publico' => false,
            				),
            		),
            
            ),
            'papel' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '/papel[/:action][/:codigo][/]',
            				'defaults' => array(
            						'controller' => 'Infra\Controller\Papel',
            						'action'     => 'index',
            						'publico' => false,
            				),
            		),
            
            ),
            'acao' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '/acao[/:action][/:codigo][/]',
            				'defaults' => array(
            						'controller' => 'Infra\Controller\Acao',
            						'action'     => 'index',
            						'publico' => false,
            				),
            		),
            
            ),
            'modulo' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '/modulo[/:action][/:codigo][/]',
            				'defaults' => array(
            						'controller' => 'Infra\Controller\Modulo',
            						'action'     => 'index',
            						'publico' => false,
            				),
            		),
            
            ),
            'log' => array(
            		'type'    => 'segment',
            		'options' => array(
            				// Basta alterar a rota para este modelo!!!
            				'route'    => '/log[/:action][/:codigo][/]',
            				'defaults' => array(
            						'controller' => 'Infra\Controller\Log',
            						'action'     => 'index',
            						'publico' => false,
            				),
            		),
            
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Infra' => __DIR__ . '/../view',
        ),
    		
    	'template_map' => array(
    		'oauth/authorize' => __DIR__ . '/../view/oauth/authorize.phtml',
    		'oauth/receive-code' => __DIR__ . '/../view/oauth/receive-code.phtml',
    	),
    ),
    'service_manager'=> array(
		'factories' => array(
			'telaLogin'               => 'Infra\Service\telaLogin', //tela de login
		    'criarLoginInicial'       => 'Infra\Service\montaTela', //tela inicial regra de negocio
		    'criarLoginInicialFactory'=> 'Infra\Service\criarLoginInicialFactory', //tela de 1o cadastro
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory', //conexao com o banco
		    'clienteEmpresaFactory'   => 'Infra\Service\clienteEmpresaFactory', //
		    'usuarioFactory'=> 'Infra\Service\usuarioFactory', //
		    'senhaFactory'=> 'Infra\Service\senhaFactory', //
		    'papelFactory'=> 'Infra\Service\papelFactory', //
		    'acaoFactory'   => 'Infra\Service\acaoFactory', //
		    'moduloFactory'   => 'Infra\Service\moduloFactory', //
		    'application-switch-language-form' => function ($sm) {

		    	return new Infra\Form\SwitchLanguage($sm->get('languages'));
		    	},
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'fpdf'   => 'Infra\Model\fpdf17\fpdf',
		),
    	'services' => array(
    		// languages supported
    		'languages' => array(
    			'Choose' => 'Choose',
		    	
		    	'en_US'	=> 'English (US)',
		    	'pt_BR' => 'português do Brasil',
		    	    		
    		),
    	),
    	'translator' => array(
    			'locale' => 'en_US',
    			'translation_file_patterns' => array(
    					array(
    							'type'     => 'gettext',
    							'base_dir' => __DIR__ . '/../language',
    							'pattern'  => '%s.mo',
    					        'text_domain' => __NAMESPACE__,
    					),
    			),
    	),
        'invokables'=>array(
			'Infra' => 'Infra\Controller\InfraController',//para verificar se esta logado pela rota Module.php
			'Seguranca' => 'Infra\Service\Seguranca', //gera a senha 
      //      'AclInfra'=> 'Infra\Model\AclInfra', //conexao com o banco
        //    'Banco'=> 'Infra\Model\Banco', //conexao com o banco
		),
    ),
    'db' => array (
        'driver' => 'Pdo_Pgsql',
        'username'=> 'postgres',
        'password'=>'admlinux',
        'database'=>'ilist',
    ),
    'navigation' => array(
             'default' => array(
                 array(
             		'label' => 'Login',
             		'route' => 'infra',
                    'action' => 'login',
             		'id'=>'login',
             		'order'=> 1000,
             		'class'=>'dropdown-toggle',
                    'resource'   => 'publico',
	             ),
                 array(
                 		'label' => 'Logout',
                 		'route' => 'infra',
                 		'action' => 'logout',
                 		'id'=>'logout',
                 		'order'=> 1001,
                 		'class'=>'dropdown-toggle',
                 		'resource'   => 'autenticado',
                 ),
                 array(
                    'label' => 'Admin',
                    'route' => 'home',
                     'order'=> 999,
                     'resource'   => 'admin',
                     'pages' => array(
                         array(
                         		'label' => 'Ação',
                         		'route' => 'acao',
                         		'order'=> 101,
                         		'resource'   => 'autenticado', // resource
                                      
                         ),
                         array(
                         		'label' => 'Módulos',
                         		'route' => 'modulo',
                         		'order'=> 102,
                         		'resource'   => 'autenticado', // resource
                         
                         ),
                        
                        array(
                    		'label' => 'Cadastro de Papeis',
                    		'route' => 'papel',
                    		'action' => 'index',
                            'order'=> 300,
                            'resource'   => 'Infra\Controller\Papel', // resource
                        ),
                        array(
                            'label' => 'Cadastro de Usuários',
                            'route' => 'usuario',
                            'action' => 'index',
                            'order'=> 400,
                            'resource'   => 'Infra\Controller\Usuario', // resource
                        ),
                       
                    ),
                ),
    				 
             ),
         ),
         
         'view_helper_config' => array(
         		'flashmessenger' => array(
         				'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
         				'message_close_string'     => '</li></ul></div>',
         				'message_separator_string' => '</li><li>'
         		)
         )
);
