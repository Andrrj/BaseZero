<?php
return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'pgsql:host=localhost;port=5432;dbname=basezero;',
        'adapters' => array(
        ),
    ),
    'navigation' => array(
        'default' => array(
            0 => array(
                'label' => 'Início',
                'route' => 'home',
                'order' => 100,
                'resource' => 'publico',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\\Navigation\\Service\\DefaultNavigationFactory',
            'Zend\\Db\\Adapter\\Adapter' => 'Zend\\Db\\Adapter\\AdapterServiceFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'oauth' => array(
                'options' => array(
                    'spec' => '%oauth%',
                    'regex' => '(?P<oauth>(/oauth))',
                ),
                'type' => 'regex',
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authentication' => array(
            'map' => array(
                'User\\V1' => 'oauth2',
                'IListApp\\V1' => 'oauth2',
            ),
        ),
    ),
);
