<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */


return array(
    'db' => array(
    		'driver' => 'Pdo',
    		'dsn' => 'pgsql:host=pgsql.checktaskx3.com.br;port=5432;dbname=checktaskx3;',
    ),
    
    'navigation' => array(
         'default' => array(
              array(
                 'label' => 'Home',
                  'route' => 'home',
                  'order'=> 100,
                  'resource'   => 'publico',
              ),
//              array(
//              		'label' =>'Avaliação' ,
//              		'uri' => '/avaliacao',
//              		'order'=> 102,
//              		'resource'   => 'publico',
//              ),
//              array(
//              		'label' =>'About' ,
//              		'uri' => 'http://www.ambientetecnologico.com.br/#!sobre/c21kz',
//              		'order'=> 101,
//              		'resource'   => 'publico',
//              ),
         ),
      ),
     'service_manager' => array(
         'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
             'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            
         ),
     ),
);
