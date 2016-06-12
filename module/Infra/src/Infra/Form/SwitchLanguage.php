<?php
namespace Infra\Form;
use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Session\Container;
class SwitchLanguage extends Form
{
	public function __construct(Array $languages)
	{
		parent::__construct();
		$language = new Select('language');
		$language->setValueOptions($languages);
		$this->add($language);
		
		$ambiente = new Container('infra'); //pega ambiente
		if (isset($ambiente->locale)) {
			$language = $ambiente->locale;
			$this->setAttributes(array('method' => 'get', 'onChange' => 'submit();', ''));
		}
		
		$this->setAttributes(array('method' => 'get', 'onChange' => 'submit();'));
	}
}