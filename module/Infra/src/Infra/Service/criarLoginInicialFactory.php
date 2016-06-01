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

class criarLoginInicialFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
 		
 		$objectManager = $services->get('Doctrine\ORM\EntityManager');
    	$form = new UsuarioForm($objectManager);
		
    	$filter  = new UsuarioFilter();
    	$form->setInputFilter($filter);
		
    	$form->add(array(
    			'type' => 'Zend\Form\Element\Csrf',
    			'name' => 'csrf',
    			'options' => array(
    					'csrf_options' => array(
    							'timeout' => 600
    					)
    			)
    	));
    	 
    	$form->add(array(
    			'name' => 'submit',
    			'attributes' => array(
    					'type'  => 'submit',
    					'value' => 'Enviar',
    					'id' => 'submitbutton',
    					'class'=>"btn btn-default"
    			),
    	));
		return $form;
		
	}
}
