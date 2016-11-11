<?php
use Zend\Log\Writer\Stream;

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
                'publico' => true,
            ),
            1 => array(
                'label' => 'Cursos',
                'route' => 'ofertacursos',
                'order' => 200,
                'publico' => true,
            ),
        ),
            
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\\Navigation\\Service\\DefaultNavigationFactory',
            'Zend\\Db\\Adapter\\Adapter' => 'Zend\\Db\\Adapter\\AdapterServiceFactory',
            'Log' => function ($sm) {
                    $log = new Zend\Log\Logger();
                    $writer = new Stream('data/logs/logfile.log');
                    $log->addWriter($writer);
            
                 return $log;
            }
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
            ),
        ),
    ),
);
