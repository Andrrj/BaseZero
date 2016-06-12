<?php
namespace Infra\Form;

use Application\Entity\Acao;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Element\Radio;

class acaoFieldset extends Fieldset
{
	public function __construct(ObjectManager $objectManager=NULL, $translator = null)
	{
		parent::__construct('acao');
	
		$this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\Acao'))
		->setObject(new Acao());
		
		  //$this->setLabel($translator->translate('Answer'));
		 		  
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
		  				'label' =>$translator->translate('Ação'),
		  				'use_as_base_fieldset' => true,
		  				'object_manager' =>$objectManager,
		  				'target_class'   => 'Application\Entity\Acao',
		  				'property'       => 'descricao',
		  				'for' => 'codigo',
		  		        'empty_option' => $translator->translate('Selecione Ação'),
		  		),
		  			
		  ));
		  
		  $this->add(array(
		  				'name' => 'deletepadrao',
		  				'type'  => 'Zend\Form\Element\Button',
		  				'attributes' => array(
		  								'id'=> 'delete-padrao',
		  								'class'=>"btn btn-warning removeform",
		  								// 'multiple' => 'multiple',
		  						),
		  				'options' => array(
		  								'label' =>$translator->translate('Remover'),
		  
		  						),
		  			
		  		));
		  
	}

	/**
	 * @return array
	 \*/
// 	public function getInputFilterSpecification()
// 	{
// 	}
}