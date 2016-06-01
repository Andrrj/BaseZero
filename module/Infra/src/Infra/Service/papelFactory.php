<?php
/*
 * Fábrica de criação do Formulariod e login
 */
namespace Infra\Service;

use Infra\Form\papelFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use Application\Entity\Papel;
use Infra\Form\papelForm;
use Infra\Form\moduloFieldset;



class papelFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
 		
	    $ambiente = new \Zend\Session\Container('infra'); //pega ambiente
	    $translator = $services->get('translator');
	     
	    $translator->setLocale($ambiente->locale);
	    
 		$objectManager = $services->get('Doctrine\ORM\EntityManager');
    	$form = new papelForm($objectManager, $translator);
		
		//adicionando fieldset's
    	
    	$moduloFieldset = new moduloFieldset($objectManager, $translator);
//     	//$localeFieldset->setUseAsBaseFieldset(true);

    	$form->add(array(
    			'type' => 'Zend\Form\Element\Collection',
    			'name' => 'modulo',
        	    'attributes' => array(
        	    		'id'=> 'modulo',
        	    ),
    			'options' => array(
    			    'label' => $translator->translate('Modulos'),
    			  //  'count' => 1,
    			    'should_create_template' => true,
    			 //   'allow_add' => true,
    			  //  'allow_remove' => true,
  					'target_element' => $moduloFieldset
    			)
    	));
    	
     	$filter  = new papelFilter();
    	$form->setInputFilter($filter);
		
		return $form;
		
	}
}
