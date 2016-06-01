<?php

namespace InfraTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class InfraControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    public function setUp()
    {
        $this->setApplicationConfig(
            include '/home/www/prototipo/trunk/checktask2/config/application.config.php'
        );
        parent::setUp();
    }
    public function testIndexActionCanBeAccessed()
    {
                
        $postData = array(
        		'login'  => 'avaliador',
        		'senha' => 'teste',
        );
        
     	$this->dispatch('/infra/login', 'POST', $postData);
     	$this->assertResponseStatusCode(200);
     	//$this->assertRedirectTo('/');
    	$this->assertModuleName('Infra');
    	$this->assertControllerName('Infra\Controller\Infra');
    	$this->assertControllerClass('InfraController');
    	$this->assertMatchedRouteName('infra');
    	
    	
    }
}