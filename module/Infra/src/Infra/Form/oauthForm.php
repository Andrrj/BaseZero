<?php

namespace Infra\Form;


use Application\Entity\Usuario;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class oauthForm extends Form
{
	public function __construct(ObjectManager $objectManager=null, $translator = null)
	{
		parent::__construct('oauthForm');
		$this->setAttribute('method', 'post');
	//	$this->setAttribute('action', 'usuario/add');

		//$this->setInputFilter(new UsuarioFilter());

		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\OauthUsers'))
		->setObject(new Usuario());
		
		$this->add(array(
				'name' => 'id',
					
				'attributes' => array(
						'type'  => 'hidden',
					//	'required'=>'required',
						// 'class'=>"form-control"
				),
				
		));
		
		$this->add(array(
				'name' => 'username',
				'attributes' => array(
						'type'  => 'Zend\Form\Element\Email',
						'required'=>'required',
						'class'=>"form-control",
						'id' => 'username',
						'placeholder'=>$translator->translate('Digite o email'),
				),
				'options' => array(
						'label' => $translator->translate('Login do usuário'),
						'for' => 'username',
				),
					
		));
		
		
		$this->add(array(
				'name' => 'name',
				'attributes' => array(
					'type'  => 'text',
					'required'=>'required',
					'class'=>"form-control",
			    	'id' => 'name',
					'placeholder'=>$translator->translate('Nome'),						
				),
				'options' => array(
						'label' => $translator->translate('Nome'),
				    'for' => 'name',
				),
					
		));
		
		$this->add(array(
				'name' => 'password',
					
				'attributes' => array(
						'type'  => 'password',
						'required'=>'required',
						'class'=>"form-control",
				        'id' => 'password',
				),
				'options' => array(
						'label' => $translator->translate('Senha'),
				        'for' => 'password',
				),
					
		));
		$this->add(array(
				'name' => 'senha1',
					
				'attributes' => array(
						'type'  => 'password',
						//'required'=>'required',
						'class'=>"form-control",
				        'id' => 'senha1',
				),
				'options' => array(
						'label' => $translator->translate('Redigite Senha'),
				        'for' => 'senha1',
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