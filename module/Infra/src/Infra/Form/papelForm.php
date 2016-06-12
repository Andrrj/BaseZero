<?php

namespace Infra\Form;


use Application\Entity\Papel;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class papelForm extends Form
{
	public function __construct(ObjectManager $objectManager=null, $translator = null)
	{
		parent::__construct('papel');
		$this->setAttribute('method', 'post');

		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Papel'))
		->setObject(new Papel());
		
		$this->add(array(
				'name' => 'codigo',
					
				'attributes' => array(
						'type'  => 'hidden',
					//	'required'=>'required',
						// 'class'=>"form-control"
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
						'label' => $translator->translate('Papel'),
				    'for' => 'descricao',
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