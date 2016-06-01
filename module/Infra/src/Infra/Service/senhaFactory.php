<?php
/*
 * Fábrica de criação do Formulariod e login
 */
namespace Infra\Service;


use Infra\Form\senhaFilter;
use Zend\ServiceManager\FactoryInterface;
use Infra\Form\UsuarioForm;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Entity\Usuario;

class senhaFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
 		
	    $ambiente = new \Zend\Session\Container('infra'); //pega ambiente
	    $translator = $services->get('translator');
	     
	    $translator->setLocale($ambiente->locale);
	    
 		$objectManager = $services->get('Doctrine\ORM\EntityManager');
    	$form = new UsuarioForm($objectManager, $translator);
		
    	
//      	$filter  = new senhaFilter();
//     	$form->setInputFilter($filter);
		
		return $form;
		
	}
}
