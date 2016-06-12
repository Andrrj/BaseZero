<?php
namespace Infra\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Infra\Model\Acl;

class aclFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $services) {
        
        $banco =  $services->get('bancoFactory');
        $login =  $services->get('Login');
               
        $getEm = $services->get('getEmFactory');//fabrica em application
        
        $data = $getEm->getRepository('Application\Entity\Usuario')->
        findBy(array('uid', $login->isLoggedIn()) );
        
        return $banco;
    }
}