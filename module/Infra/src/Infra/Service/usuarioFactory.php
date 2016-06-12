<?php
/*
 * Fábrica de criação do Formulariod e login
 */
namespace Infra\Service;


use Infra\Form\UsuarioFilter;
use Zend\ServiceManager\FactoryInterface;
use Infra\Form\UsuarioForm;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Entity\Usuario;
use Infra\Form\clienteEmpresaFieldset;
use Infra\Form\senhaFieldset;
use Infra\Form\localeFieldset;
use Infra\Form\papelFieldset;
use Infra\Form\moduloFieldset;


class usuarioFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
		
	    $ambiente = new \Zend\Session\Container('infra'); //pega ambiente
	    $translator = $services->get('translator');
	     
	    $translator->setLocale($ambiente->locale);
	    
 		$objectManager = $services->get('Doctrine\ORM\EntityManager');
    	$form = new UsuarioForm($objectManager, $translator);
    	
		//adicionando fieldset's
    	
    	$localeFieldset = new localeFieldset($objectManager, $translator);
    	//$localeFieldset->setUseAsBaseFieldset(true);
    	$form->add($localeFieldset);
    	
    	$papelFieldset = new papelFieldset($objectManager, $translator, $services);
    	$form->add(array(
    			'type' => 'Zend\Form\Element\Collection',
    			'name' => 'papel',
        	    'attributes' => array(
        	    		'id'=> 'papel',
        	    ),
    			'options' => array(
    			    'label' => $translator->translate('Papel'),
    			    //'count' => 1,
    			    'should_create_template' => true,
    			//    'allow_add' => true,
  					'target_element' => $papelFieldset
    			)
    	));
    	
    	
     	$filter  = new UsuarioFilter();
    	$form->setInputFilter($filter);
		
		return $form;
		
	}
}
