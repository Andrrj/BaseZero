<?php

namespace Infra\Form;

use Zend\Form\Element;
use Zend\Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Application\Entity\Papel;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class papelFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct(ObjectManager $objectManager=null, $translator = null, $sm =null)
	{
		parent::__construct('papel');
		$this->setAttribute('method', 'post');
		
		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Papel'))
		->setObject(new Papel());
		
		$login = $sm->get('login');
		
		if ( $login->isAdmin()) {
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
		    				'label' =>$translator->translate('Papel'),
		    				'use_as_base_fieldset' => true,
		    				'object_manager' =>$objectManager,
		    				'target_class'   => 'Application\Entity\Papel',
		    				'property'       => 'descricao',
		    				'for' => 'codigo',
		    				'empty_option' => $translator->translate('Selecione o Papel'),
		    					
		    		),
		    			
		    ));
		}else {
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
		    				'label' =>$translator->translate('Papel'),
		    				'use_as_base_fieldset' => true,
		    				'object_manager' =>$objectManager,
		    				'target_class'   => 'Application\Entity\Papel',
		    				'property'       => 'descricao',
		    				'for' => 'codigo',
		    				'empty_option' => $translator->translate('Selecione o Papel'),
		    				'is_method'      => true,
		    				'find_method'    => array(
		    						'name'   => 'findBy',
		    						'params' => array(
		    						   'criteria' => array('publico'=>1),//exibe todos menos o admin
		    
		    						),
		    				),
		    
		    		),
		    			
		    ));
		}
		
		
		
		$this->add(array(
				'name' => 'deletepapel',
				'type'  => 'Zend\Form\Element\Button',
				'attributes' => array(
						'id'=> 'delete-papel',
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