<?php
namespace Infra\Service;

use Infra\Form\acaoFilter;
use Zend\ServiceManager\FactoryInterface;
use Infra\Form\acaoForm;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Entity\Acao;

class acaoFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $services) {
        
       $ambiente = new \Zend\Session\Container('infra'); //pega ambiente
	    $translator = $services->get('translator');
	     
	    $translator->setLocale($ambiente->locale);
	    
 		$objectManager = $services->get('Doctrine\ORM\EntityManager');
    	$form = new acaoForm($objectManager, $translator);
		
    	$filter  = new acaoFilter();
    	$form->setInputFilter($filter);
		
		return $form;
    }
}