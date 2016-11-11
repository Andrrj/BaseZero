<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Infra for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Infra;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
//use Infra\Model\Acl;

use Zend\View\HelperPluginManager;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
use Infra\Model\AclInfra;
use Infra\Model\Login;
use Zend\Session\Container;


class Module implements AutoloaderProviderInterface
{
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        //verifica usuario
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this,'verificaUsuario'), -100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this,'checkRights'), -101);
        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'));
      
        /**
         * Log any Uncaught Errors
         */
        $sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
        $sm = $e->getApplication()->getServiceManager();
        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
            function($e) use ($sm) {
                if ($e->getParam('exception')){
                    $sm->get('Logger')->crit($e->getParam('exception'));
                }
            }
            );
        
        
    }
    
    public function verificaUsuario(MvcEvent $event) {
        $match = $event->getRouteMatch();
        $services = $event->getApplication()->getServiceManager();
        
        $controller = $match->getParam('controller');//Infra\Controller\Infra
        $publico = $match->getParam('publico');
        
        if (! $match){
            return ;
        }

        $auth = $services->get('Login'); //instancia login
        
               
        /** 
         * Todo controlador que tiver o rest
         * não fara parte da autehticacao do site
         * pois já esta sendo testado pelo oauth
         */ 
        
        //procura o nome Rest no controlador
        $achouRest = strpos($match->getParam('controller'), 'Rest');
        $achouOAuth = strpos($match->getParam('controller'), 'Auth');
        
        if (! $publico and ! $achouRest and ! $achouOAuth) {
        	
            if (!$auth->isLoggedIn()) {                
                //login e controller colocados como publico na mão
            	$match->setParam('controller', 'Infra\Controller\Infra');
            	$match->setParam('action', 'login');
            
            }    
        }  
    }
    
    public function checkRights(MvcEvent $event)
    {
        $matches   = $event->getRouteMatch();
        $controller = $matches->getParam('controller');//Infra\Controller\Infra
        $action    = $matches->getParam('action'); //'login'
        $serviceManager = $event->getApplication()->getServiceManager();
        
        //exibe localidade
        $event->getViewModel()->setVariable(
                    'languageForm', 
                    $serviceManager->get('application-switch-language-form')
        );
        
        $publico = $matches->getParam('publico');
      //  var_dump($publico);
        //exit;
        
        if (! $publico ) {
        //   var_dump($controller);
            
            $login = $serviceManager->get('Login');
          
//             var_dump($login->getAmbienteRoles());
//             var_dump($login->getAmbienteResources());

             if ( !  $login->reloadRegras() ) {
                return false;
             }
             
             $verifica = $login->Verifica(null, $controller, $action);
              
//               var_dump($verifica);
//              exit; 
              if (! $verifica ) {
                 
                  $url = $event->getRouter()->assemble(
                            array('action' => 'acessonegado'), 
                            array('name' => 'infra')
                        );

                $response = $event->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit;
                 
              }
        }
        
        
    }
    public function onRender(MvcEvent $e)
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$language = $e->getRequest()->getQuery('language'); //pega o retorno do formulario
    	//echo "<br><br><br><br><br><br>aqui";
    	

    	$ambiente = new Container('infra'); //pega ambiente
    	$translator = $sm->get('translator'); //seta como default
    	
    	if ($language) {
    		$languageList = $sm->get('languages');
    		if (in_array($language, array_keys($languageList), TRUE)) { ///veridica se existe a lingua

    		    $ambiente->locale = $language;
    		    
    		}
    	}else {
    	    if (isset($ambiente->locale)) {
    	        $language = $ambiente->locale;//acl de login
    	    }
    	}
    	
    	
    	$translator->setLocale($language);
    	
    	$sm->get('ViewHelperManager')->get('translate')
    	->setTranslator($translator); //passa para o view helper
    	

    }
    public function getViewHelperConfig()
    {
        
    	return array(
    			'factories' => array(
    					// This will overwrite the native navigation helper
    					'navigation' => function(HelperPluginManager $pm ) {
    				//echo "<br><br><br><br><br><br>";
    					   
                            $login = new Login();
                            
                             // Get an instance of the proxy helper
                             $navigation = $pm->get('Zend\View\Helper\Navigation');
                           
                             if ($login->isLoggedIn() && ! $login->isAdmin() ) { 
                                
                                 $login->Privado();
                                 $role ='autenticado';
                             } else  {   
                                $login->Publico();
                                $role = 'publico';
                             }
                          
                          //  var_dump( $login->Verifica( $role, $resource) );
                          //  var_dump($login->getAmbienteRoles());
                            /**
                             * Controle de menu aqui exibe
                             *  o menu caso nao seja admin
                             */
                             if ( ! $login->isAdmin()  ) {
                             	
                                 $navigation->setAcl($login)->setRole($role);
                                
                                if ($login->hasRole('planejamento') ) {
                                    $navigation->setAcl($login)->setRole('planejamento');
                                }
                               
                          //  var_dump($navigation->setAcl($login)->getRole());
                                
                             }
                          //  $login->reloadRegras();
                         // var_dump($navigation->getRole(), $navigation->getUseAcl() );
                            // Return the new navigation helper instance
                            return $navigation;
    					}
    			)
    	);
    }
    
}
