<?php

namespace Infra\Form;


use Application\Entity\Usuario;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element\Captcha;

class LoginForm extends Form
{
	public function __construct(ObjectManager $objectManager=null, $translator = null)
	{
		parent::__construct('login');
		$this->setAttribute('method', 'post');
		//$this->setAttribute('action', 'login');
		

		//$this->setInputFilter(new UsuarioFilter());

		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Usuario'))
		->setObject(new Usuario());
		 
		$this->add(array(
				'name' => 'login',
				 
				'attributes' => array(
						'type'  => 'text',
						'required'=>'required',
						'class'=>"form-control",
				        'id'=>'login',
				),
				'options' => array(
						'label' => $translator->translate('Login'),
				    'for'=>'login',
				),
					
		));
		
		$this->add(array(
				'name' => 'senha',
					
				'attributes' => array(
						'type'  => 'password',
						'required'=>'required',
						'class'=>"form-control",
				        'id'=>'senha',
				),
				'options' => array(
						'label' => $translator->translate('Password'),
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
						'value' => $translator->translate('Send'),
						'id' => 'submitbutton',
						'class'=>"btn btn-default"
				),
		));


	}

	/**
	 * @return array
	 \*/
	public function getInputFilterSpecification()
	{

	}
	
	//public function get



}
