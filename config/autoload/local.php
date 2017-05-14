<?php
return array(
    'zf-mvc-auth' => array(
        'authentication' => array(
            'adapters' => array(
                'oauth2_pdo' => array(
                    'adapter' => 'ZF\\MvcAuth\\Authentication\\OAuth2Adapter',
                    'storage' => array(
                        'adapter' => 'pdo',
                        'dsn' => 'pgsql:host=localhost;port=5432;dbname=nomedobancodedados;user=nomedousuariodobanco;password=senhadousuariodobanco',
                        'route' => '/oauth',
                        'username' => 'usuariodobanco',
                        'password' => 'senhadobanco',
                    ),
                ),
            ),
        ),
    ),
    'zf-oauth2' => array(
        'allow_implicit' => true,
    ),
    'db' => array(
        'adapters' => array(
            'DB\\aulabasezero' => array(
                'database' => 'nomedobancodedados',
                'driver' => 'PDO_Pgsql',
                'hostname' => 'localhost',
                'username' => 'nomedousuariodobanco',
                'password' => 'senhadousuariodobanco',
            ),
        ),
    ),
);
