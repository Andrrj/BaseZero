<?php
/*
 * Serviços relacionados a infra estrutura.
 * Copiar este arquivo para a pasta config/autoad 
 * do projeto.
 * Servicos:
 * 
 */
return array(
	'service_manager'=> array(
	       'factories' => array(
    			'getEmFactory'=>'Infra\Service\getEmFactory', //doctrine
    			'bancoFactory'=>'Infra\Service\bancoFactory', //conexao com o doctrine
	        
		),
	    'invokables' => array(
	       	'Login'=>'Infra\Model\Login',
	       )
    ),

);