<?php
namespace Infra\Model;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;

/**
 * Atribui as permisoes dos ACL's do login 
 * @author andre
 *
 */
class AclInfra extends ZendAcl
{
    private $_acl;
	
	/**
	 * Liga ACL aos resources e roles 
	 */
	public function __construct()
	{
	    	    	    
	}
	
	
	/**
	 * Define as regras da acl
	 */
	public function setRegras ($sm)
	{
	    $login = $sm->get('Login');
	     
	    $this->setRole($login->getRoles());
	   
	    $this->setResource($login->getResources());
	    $this->setActions($login->getResources());
	   
	}
	/**
	 * seta os papeis de entrada
	 * tabela de papeis
	 * @param Array $roles
	 */
	public function setRole($roles = array()) 
	{
 
       if (empty($roles)) {
           return null;
       } 
	   foreach ($roles as $role) 
        {
            $this->addRole(new GenericRole($role));
        }
	}
	/**
	 * seta os recursos, modulos
	 * tabela de modulo
	 * @param string $resources
	 */
	public function setResource( $resources = null)
	{

	    if (empty($resources)) {
	    	return null;
	    }
		foreach ($resources as $r)
		{
			$this->addResource(new GenericResource($r));
		}
		
	}
	/**
	 * seta as permissoes,
	 * @param unknown $actions
	 */
	public function setActions($roles = null, $resources=null)
	{
	    if (empty($resources)) {
	    	return null;
	    }
		foreach ($resources as $resource)
		{
		    foreach ($roles as $role)
		    {
		        if ($role=='admin') 
		        {
		            $this->allow($role);
		        }else {
		            $this->allow($role, $resource);
		        }
		    }
			
		}
	}
	
	public function Verifica($roles, $resource) {
	    $verifica = false;
	    foreach ($roles as $role) 
	    {
          if ( $this->hasRole($role)  && 
               $this->hasResource($resource)
             ) 
          {
            $verifica = $this->isAllowed($role, $resource, null);
          }
	       
	    }
	    return $verifica;
	}
	
	
}