<?php
namespace Infra\Model;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;

class AdapterBcrypt implements AdapterInterface
{
	protected $username, $password, $serviceManager, $usuario;
	/**
	 * Sets username and password for authentication
	 *
	 * @return void
	 */
	public function __construct($username, $password, $serviceManager)
	{
	   $this->username = $username;
	   $this->password = $password;
	   $this->serviceManager = $serviceManager;
	}
	
	/**
	 * Performs an authentication attempt
	 *
	 * @return \Zend\Authentication\Result
	 * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
	 *               If authentication cannot be performed
	 */
	public function authenticate()
	{
		
		$banco =  $this->serviceManager->get('bancoFactory');
		 
		$banco->setSchema($this->serviceManager, 'public');
		
		$getEm = $this->serviceManager->get('getEmFactory');//fabrica em application
		
		
		$usuario = $getEm->getRepository('Application\Entity\Usuario')->
		 	    findBy(array('login'=> $this->username)  );

		$bcrypt = new Bcrypt();

		if ( ! isset ($usuario) or empty($usuario)) 
		{
			$result = new Result(
					Result::FAILURE_IDENTITY_NOT_FOUND,
					$this->username,
					array('Usuário Inexistente!'));
			
		} else if ( ! $bcrypt->verify($this->password, $usuario[0]->getSenha()) ) 
		{
			$result =  new Result(
					Result::FAILURE_CREDENTIAL_INVALID,
					$this->username,
					array('Senha Inválida !'));
		} else {
			
			$this->usuario= $this->Hydrate($usuario[0]);

			$result =  new Result(
					Result::SUCCESS,
					$this->username);
		}
		
		return $result;
	    
	}
	
	public function getResultRowObject()
	{
		return $this->usuario;
	}
	
	protected function Hydrate($usuario)
	{
		$objeto = (object) array (
			'codigo'=>$usuario->getCodigo(),
			'login'=>$usuario->getLogin(),
		    'empresa'=>$usuario->getEmpresa(),
		);
		return $objeto;
	}
}

?>
