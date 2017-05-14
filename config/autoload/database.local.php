<?php
return array(
		'db' => array(
				'driver'    => 'PdoPgsql',
			        'hostname'  => 'localhost',
				'database'  => 'nomedobancodedados',
				'username'  => 'nomedousuariodobanco',
			        'password'  => 'senhadousuariodobanco',
		),
		'service_manager' => array(
				'factories' => array(
						'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
				),
		),
);
