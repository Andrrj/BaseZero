<?php

namespace Infra\Form;


use Application\Entity\Modulo;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class moduloForm extends Form
{
	public function __construct(ObjectManager $objectManager=null, $translator = null)
	{
		parent::__construct('modulo');
		$this->setAttribute('method', 'post');

		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Modulo'))
		->setObject(new Modulo());
		
		$this->add(array(
				'name' => 'codigo',
					
				'attributes' => array(
						'type'  => 'hidden',
					//	'required'=>'required',
						// 'class'=>"form-control"
				),
				
		));
		
		$this->add(array(
				'name' => 'nome',
				 
				'attributes' => array(
						'type'  => 'text',
						'required'=>'required',
						'class'=>"form-control",
				    'id' => 'nome',
				    
				),
				'options' => array(
						'label' => $translator->translate('Nome'),
				    'for' => 'nome',
				),
					
		));
		
		$this->add(array(
				'name' => 'controller',
					
				'attributes' => array(
						'type'  => 'text',
						'required'=>'required',
						'class'=>"form-control",
						'id' => 'controller',
		
				),
				'options' => array(
						'label' => $translator->translate('Controller'),
						'for' => 'controller',
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