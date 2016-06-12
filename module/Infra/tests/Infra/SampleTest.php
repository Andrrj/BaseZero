<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Infra for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace InfraTest;

use Infra\Model;
use Infra\Service;
use Zend\ServiceManager\ServiceManager;
use Infra\Model\Login;
use Zend\Di\ServiceLocator;
use Infra\Model\Acl;
use Infra\Model\AclInfra;

class SampleTest extends Framework\TestCase
{
   

    public function testACL()
    {
        $resource = array('Infra\Controller\Usuario','autenticado');
        $role = array('autenticado', 'admin');
        $acl = new AclInfra();
        $acl->setResource($resource);
        $acl->setRole($role);
        
        $acl->setActions($role, $resource);
        
        $acl->allow('convidado','area');
        
        $this->assertEquals(1, $acl->isAllowed('admin', 'autenticado'));
        $this->assertEquals(1, $acl->isAllowed('admin', 'Infra\Controller\Usuario'));
        $this->assertEquals(0, $acl->isAllowed('admin', 'Infra\Controller\Empresa'));
        $this->assertEquals(0, $acl->isAllowed('convidado', 'padrao'));
        $this->assertEquals(0, $acl->isAllowed('guest', 'padrao'));
        
    }
    
}
