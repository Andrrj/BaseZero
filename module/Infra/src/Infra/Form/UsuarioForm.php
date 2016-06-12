<?php

namespace Infra\Form;


use Application\Entity\Usuario;


use Zend\Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class UsuarioForm extends Form
{
	public function __construct(ObjectManager $objectManager=null, $translator = null)
	{
		parent::__construct('usuario');
		$this->setAttribute('method', 'post');
	//	$this->setAttribute('action', 'usuario/add');

		//$this->setInputFilter(new UsuarioFilter());

		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Usuario'))
		->setObject(new Usuario());
		
		$this->add(array(
				'name' => 'codigo',
					
				'attributes' => array(
						'type'  => 'hidden',
					//	'required'=>'required',
						// 'class'=>"form-control"
				),
				
		));
		
		$this->add(array(
				'name' => 'login',
				 
				'attributes' => array(
						'type'  => 'text',
						'required'=>'required',
						'class'=>"form-control",
				    'id' => 'login',
				    
				),
				'options' => array(
				'label' => $translator->translate('Login'),
				    'for' => 'login',
				),
					
		));
		
		$this->add(array(
				'name' => 'senha',
					
				'attributes' => array(
						'type'  => 'password',
						'required'=>'required',
						'class'=>"form-control",
				        'id' => 'senha',
				),
				'options' => array(
						'label' => $translator->translate('Password'),
				        'for' => 'senha',
				),
					
		));
		$this->add(array(
				'name' => 'senha1',
					
				'attributes' => array(
						'type'  => 'password',
						'required'=>'required',
						'class'=>"form-control",
				        'id' => 'senha1',
				),
				'options' => array(
						'label' => $translator->translate('Retype Password'),
				        'for' => 'senha1',
				),
					
		));
		$this->add(array(
				'name' => 'email',
					
				'attributes' => array(
						'type'  => 'Zend\Form\Element\Email',
						'required'=>'required',
						'class'=>"form-control",
				        'id' => 'email',
				),
				'options' => array(
						'label' => $translator->translate('Email'),
				        'for' => 'email',
				),
					
		));
				
		$this->add(array(
				'type' => 'Zend\Form\Element\Csrf',
				'name' => 'csrf',
				'options' => array(
						'csrf_options' => array(
								'timeout' => 600
						)
				)
		));
		
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => $translator->translate('Enviar'),
						'id' => 'submitbutton',
						'class'=>"btn btn-success"
				),
		));
		
	}

	/**
	 * @return array
	 \*/
	public function getInputFilterSpecification()
	{

	}



}