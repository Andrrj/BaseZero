<?php
/*
 * Fábrica de criação do Formulariod e login
 */
namespace Infra\Service;

use Infra\Form\moduloFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use Application\Entity\Papel;
use Infra\Form\moduloForm;
use Infra\Form\acaoFieldset;



class moduloFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
 		
	    $ambiente = new \Zend\Session\Container('infra'); //pega ambiente
	    $translator = $services->get('translator');
	     
	    $translator->setLocale($ambiente->locale);
	    
 		$objectManager = $services->get('Doctrine\ORM\EntityManager');
    	$form = new moduloForm($objectManager, $translator);
		
		//adicionando fieldset's
    	
    	$acaoFieldset = new acaoFieldset($objectManager, $translator);

    	$form->add(array(
     			'type' => 'Zend\Form\Element\Collection',
     			'name' => 'acao',
     			'attributes' => array(
     					'id'=> 'acao',
     			),
     			'options' => array(
     					'label' => $translator->translate('Selecione as ações'),
     					//  'count' => 1,
     					'should_create_template' => true,
     					//'allow_add' => true,
     					//  'allow_remove' => true,
     					'target_element' => $acaoFieldset,
     			)
     	));
    	
     	$filter  = new moduloFilter();
    	$form->setInputFilter($filter);
		
		return $form;
		
	}
}
