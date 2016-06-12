<?php
namespace Infra\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class getEmFactory implements FactoryInterface {
    private $em;
    public function createService(ServiceLocatorInterface $sm) {
        if (null === $this->em)
        	$this->em = $sm->get('doctrine.entitymanager.orm_default');
        return $this->em;
    }
}