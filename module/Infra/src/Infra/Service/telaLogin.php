<?php
/*
 * Fábrica de criação do Formulariod e login
 */
namespace Infra\Service;


use Infra\Form\LoginFilter;
use Zend\ServiceManager\FactoryInterface;
use Infra\Form\LoginForm;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class telaLogin implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
	 
	   
        $ambiente = new Container('infra'); //pega ambiente
        $translator = $services->get('translator');
	    
        $translator->setLocale($ambiente->locale);
        
        $objectManager = $services->get('Doctrine\ORM\EntityManager');
        $form = new LoginForm($objectManager, $translator);
       
        $filter  = new LoginFilter();
        $form->setInputFilter($filter);	
        
        return $form;
		
	}
}
