<?php
namespace Infra\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Infra\Model\Banco;

class bancoFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $sm) {
        
        $banco = new Banco($sm);    
        return $banco;
    }
}