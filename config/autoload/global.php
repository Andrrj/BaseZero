<?php
return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'pgsql:host=localhost;port=5432;dbname=nomedobancoddedados;',
        'adapters' => array(),
    ),
    'navigation' => array(
        'default' => array(
            0 => array(
                'label' => 'Início',
                'route' => 'home',
                'order' => 100,
                'publico' => true,
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
);
