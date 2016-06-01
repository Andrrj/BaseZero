<?php
namespace Infra\Form;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

use Zend\Validator\EmailAddress;

class UsuarioFilter extends InputFilter
{
	private $inputFilter;

	public function __construct()
	{
		$validator = new EmailAddress();
		
		$this->add(array(
				'name'       => 'login',
				'required'   => true,
				)
		);
		
		$this->add(array(
        		    'name'       => 'email',
        		    'required'   => true,
        		)
	    );
		
// 		$this->add(array(
// 				'name'       => 'senha',
// 				'required'   => true,
// 				'filters'    => array(
// 						array(
// 								'name'    => 'StripTags',
// 								'name'    => 'Stringtrim',
		
// 						),
// 				),
// 				'validators' => array(
// 						array(
// 								'name'    => 'StringLength',
// 								'options' => array(
// 										'encoding' => 'UTF-8',
// 										'min'      => 2,
// 										'max'      => 100,
// 								),
// 					          'name' => 'not_empty',
// 						),
// 				),
// 		));
		
// 		$this->add(array(
// 				'name'       => 'senha1',
// 				'required'   => false,
// 				'filters'    => array(
// 						array(
// 								'name'    => 'StripTags',
// 								'name'    => 'Stringtrim',
		
// 						),
// 				),
// 				'validators' => array(
// 						array(
// 								'name'    => 'StringLength',
// 								'options' => array(
// 										'encoding' => 'UTF-8',
// 										'min'      => 2,
// 										'max'      => 100,
// 								),
// 						    'name' => 'not_empty',
// 						),
// 				),
// 		));
		
		return $this;
	}

}
