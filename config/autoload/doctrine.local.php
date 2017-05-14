<?php
return array(
		'doctrine' => array(
				'connection' => array(
						'orm_default' => array(
							'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
    						'params' => array(
    						    'host'     => 'localhost',
    							'port'     => '5432',
    							'user'     => 'nomedousuariodobanco',
   						        'password' => 'senhadousuariodobanco',
    							'dbname'   => 'nomedobancodedados',
								),
    						),
				            'object_manager' => 'Doctrine\ORM\EntityManager',
				            'identity_class' => 'Application\Entity\Usuario',
    						'identity_property' => 'login',
    						'credential_property' => 'senha',
    						)
    	),
    'db' => array(
			'username' => 'nomedousuariodobanco',
			'password' => 'senhadousuariodobanco',
		),

);
