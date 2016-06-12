<?php

namespace Infra\Form;

use Zend\Form\Element;
use Zend\Form\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Application\Entity\Modulo;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class moduloFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct(ObjectManager $objectManager=null, $translator = null)
	{
		parent::__construct('modulo');
		$this->setAttribute('method', 'post');
		
		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Modulo'))
		->setObject(new Modulo());
		
		$this->add(array(
				'name' => 'codigo',
				'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
				'attributes' => array(
				        'id'=> 'codigo',
						//'required'=>'required',
						'class'=>"form-control",
				 // 'multiple' => 'multiple',
				),
				'options' => array(
				    'label' =>$translator->translate('Módulo'),
				    'use_as_base_fieldset' => true,
				    'object_manager' =>$objectManager,
				    'target_class'   => 'Application\Entity\Modulo',
				    'property'       => 'nome',
				    'for' => 'codigo',
				    'empty_option' => $translator->translate('Selecione o Módulo'),
				),
					
		));
		
		$this->add(array(
				'name' => 'deletemodulo',
				'type'  => 'Zend\Form\Element\Button',
				'attributes' => array(
						'id'=> 'delete-modulo',
						'class'=>"btn btn-warning removeform",
						// 'multiple' => 'multiple',
				),
				'options' => array(
						'label' =>$translator->translate('Delete'),
						
				),
					
		));


	}

	/**
	 * @return array
	 \*/
	public function getInputFilterSpecification()
	{
	    return array(
	    		'codigo' => array(
	    				'required' => false,
	    		),
	    );
	}

}