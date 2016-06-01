<?php

namespace Infra\Form;
use Application\Entity\Acao;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class acaoForm extends Form
{
	public function __construct(ObjectManager $objectManager=null, $translator = null)
	{
		parent::__construct('Acao');
		$this->setAttribute('method', 'post');
		//$this->setAttribute('action', 'login');
		

		//$this->setInputFilter(new UsuarioFilter());

		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Acao'))
		->setObject(new Acao());
		
		
		$this->add(array(
				'name' => 'codigo',
					
				'attributes' => array(
						'type'  => 'hidden',
						'required'=>'required',
// 						'class'=>"form-control",
// 				        'id' => 'codigo',
				),
				
					
		));
		
		$this->add(array(
				'name' => 'descricao',
				 
				'attributes' => array(
						'type'  => 'text',
						'required'=>'required',
						 'class'=>"form-control",
				         'id' => 'descricao',
				),
				'options' => array(
						'label' =>  $translator->translate('Descrição'),
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
						'value' => 'Salvar',
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



}