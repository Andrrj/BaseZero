<?php

namespace Infra\Form;

use Zend\Form\Element;
use Zend\Form\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Application\Entity\Locale;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class localeFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct(ObjectManager $objectManager=null, $translator = null)
	{
		parent::__construct('locale');
		$this->setAttribute('method', 'post');
		
		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Locale'))
		->setObject(new Locale());
		 
		$this->add(array(
				'name' => 'codigo',
				'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
				'attributes' => array(
				        'id'=> 'codigo',
						'required'=>'required',
						'class'=>"form-control",
				  //  'multiple' => 'multiple',
				),
				'options' => array(
				    'label' =>$translator->translate('Localização:'),
				    'object_manager' =>$objectManager,
				    'target_class'   => 'Application\Entity\Locale',
				    'property'       => 'descricao',
				    'for' => 'descricao',
				    'empty_option' => $translator->translate('Selecione o idioma'),
					
		          )
		   		)
		);

	}

	/**
	 * @return array
	 \*/
	public function getInputFilterSpecification()
	{
	    return array(
	    		'codigo' => array(
	    				'required' => true,
	    		),
	    );
	}

}