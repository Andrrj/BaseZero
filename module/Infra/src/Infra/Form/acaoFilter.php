<?php
namespace Infra\Form;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class acaoFilter extends InputFilter
{
    private $inputFilter;
    
    public function __construct()
    {
        $this->add(array(
            'name'       => 'descricao',
            'required'   => true,
            'filters'    => array(
                array(
                    'name'    => 'StripTags',
                    'name'    => 'Stringtrim',
                    
                ),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 500,
                    ),
                ),
            ),
        ));
        return $this;
    }
    
}
