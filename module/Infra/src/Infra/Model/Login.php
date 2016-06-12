<?php
/*
 * Arquivo de modelo de usuário para a conexao sem 
 * o doctrine
 */

namespace Infra\Model;

use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Application\Entity\ClienteEmpresa;
use DoctrineModule\Options\Authentication;
use Zend\Session\Container;
use Application;
use Zend\Escaper\Escaper;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Math\Rand;
use Avaliacoes\Model\ImagemAvaliacao;

/**
 * Controle de login
 * @author andre
 *
 */
class Login extends ZendAcl {
    private $login;
    private $senha;
    private $id;
    private $ambiente;
    private $token;
    private $logotipo;
    public  $messages = array();
    private $auth;
    private $recursos;
    protected $roles = array();
    protected $resources = array();
    protected $acao = array();
    
       /**
        * Atribui o login e senha na classe
        * @param string $login
        * @param string $senha
        */
    public function setLogin($login=null, $senha=null)
    {
            $this->login = $login;
            $this->senha = $senha;
    }
    
    /**
     * retorna login e senha
     * @param string $login
     * @param string $senha
     */
    public function getLogin()
    {
        $auth = $this->getAuth();
        return $auth->getIdentity(); //pega usuario
    }
    
    /**
     * Retorna a classe de autenticacao utilziando singleton
     * 
     * @return \Zend\Authentication\AuthenticationService
     */
    private function getAuth()
    {
    	if (! $this->auth ){
    	    $this->auth = new AuthenticationService();
    	}
    	
    	return $this->auth;
    
    }
    /**
     * Verifica se o usuário está autenticado
     * @param unknown $sm
     * @return boolean
     */
    public function authenticate($sm)
    {
        
        $this->sm = $sm;
        
        $auth = $this->getAuth();
        
        //Bcrypt
        $adapter = new AdapterBcrypt($this->login, $this->senha, $this->sm);
        $auth->setAdapter($adapter);
       
        $result = $auth->authenticate();
        
        if($result->isValid()){
            $usuario = $auth->getAdapter()->getResultRowObject(null,'senha');
                        
            $auth->getStorage()->write($usuario);
           
            //pega o ambiente do schema e das regras da acl
            $this->setAmbiente($sm);
            return true;
        }else {
            $this->messages=$result->getMessages();
            return false;
        }
                
    }
    /**
     * Seta o esquema do usuário
     * @param AuthenticationService $sm
     */
    public function setAmbiente($sm) 
    {
        $auth = $this->getAuth();
                
        if ($auth->hasIdentity()) 
        {
            $ambiente = new Container('infra');
            $usuario = $auth->getIdentity(); //pega usuario
            $getEm = $sm->get('getEmFactory');//fabrica em application
            
            //define schema do usuario
            if (!$ambiente->schema)
            {
                $banco =  $sm->get('bancoFactory');
                
                $banco->setSchema($sm, 'public'); //sxhema public do postgres
                
            	$ambiente->schema = 'public';
            	
            }
            //define acl do usuario
            $this->setRegras($sm);
        }
               
    }
    
    /**
     * Pega as regras das tabelas
     * e armazena nas variáveis
     * roles e recursos
     * @param unknown $sm
     */
    public function setRegras($sm) {
    
    	$login = $this->getLogin();
    	//var_dump($login->codigo); exit;
    	$getEm = $sm->get('getEmFactory');//fabrica em application
    	$usuario = $getEm->getRepository('Application\Entity\Usuario')->
    	find($login->codigo);
    
    	foreach ($usuario->getPapel() as $p  )
    	{
    		  //var_dump($p->getDescricao());
    		$this->roles[] = $p->getDescricao();
    
    		$this->addRole(new GenericRole($p->getDescricao()));
    
    		foreach ($p->getModulo() as $m)
    		{
    		    
    			$this->recursos[] = $m->getController();
    			if (! $this->hasResource($m->getController())) 
    			{
    			    $this->addResource(new GenericResource($m->getController()));
    			}
    			
    
    			foreach ($m->getAcao() as $a)
    			{
    				$this->acao[] = array ( $m->getController() => $a->getDescricao() );
    
    				$this->allow($p->getDescricao(), $m->getController(), $a->getDescricao());
    			}
    		}
    
    
    	}
    	$ambiente = new Container('infra');
    	 
    	$ambiente->roles = $this->roles;
    	$ambiente->recursos = $this->recursos;
    	$ambiente->acao = $this->acao;
    	 
    }
    
    /**
     * Pega as regras das tabelas
     * e armazena nas variáveis
     * roles e recursos
     * @param unknown $sm
     */
    public function reloadRegras() {
    
    	$roles = $this->getAmbienteRoles();
    	$resources = $this->getAmbienteResources();
    	
    	$acao = $this->getAmbienteAcao();
    	
    	if (empty($roles)) {
    	    return false;
    	}
    	foreach ($roles as $r  )
    	{
    	   foreach($acao as $a )
    	   {
    	       foreach($a as $indice=>$action )
    	       {
    	           
        	       if (! $this->hasRole($r) ) 
        	       {
        	           $this->addRole(new GenericRole($r));
        	           
        	       }
    
        	       if ( ! $this->hasResource($indice) ) 
        	       {
        	           $this->addResource(new GenericResource($indice));
        	       }
        	           
        	       $this->allow($r, $indice, $action);
    	       }
    	   }
    	}
     return $this;
    }
    
    /**
     * Define o logout do clientes
     */
    public function logout()
    {
     //   $this->setFechaAvaliacao();
        $auth = $this->getAuth();
    	$auth->clearIdentity();
    	unset($_SESSION['infra']); 
    	return ;
    }
    /**
     * Verifica se está logado
     */
    public function isLoggedIn()
    {
        $auth = $this->getAuth();
    	return $auth->hasIdentity();
    }
    /**
     * Retorna o esquema do cliente
     * @return NULL
     */
    public function getSchema() {
    
    	if ($this->isLoggedIn())
    	{
    		$ambiente = new Container('infra');
    		
    		if (isset($ambiente->schema))
    		{
    			return $ambiente->schema;
    		}
    
    	}
    	return null;
    }
    
    /**
     * Retorna os roles para acl
     * @return multitype:
     */
    public function getAmbienteRoles()
    {
        $ambiente = new Container('infra');
        
        if (isset($ambiente->roles))
        {
            return $ambiente->roles;
        }else {
            return false;
        }
    
    }
    /**
     * retorna os recursos para acl
     */
    public function getAmbienteResources()
    {
        $ambiente = new Container('infra');
    
        if (isset($ambiente->recursos))
        {
        	return $ambiente->recursos;
        }else {
            return false;
        }
    
    }
    /**
     * retorna os acoes para acl
     */
    public function getAmbienteAcao()
    {
    	$ambiente = new Container('infra');
    
    	if (isset($ambiente->acao))
    	{
    		return $ambiente->acao;
    	}else {
    		return false;
    	}
    
    }

    public function isAdmin()
    {
        $roles = $this->getAmbienteRoles();
        $flag  =false;
        if (! $roles ){
            return $flag;
        }
        
        if (in_array('admin', $roles)) { ///veridica se e admin
        
        	$flag = true;
        
        }

        return $flag;
    }
    
    /**
     * seta os papeis de entrada
     * tabela de papeis
     * @param Array $roles
     */
    public function setRole($roles = null)
    {
        $feito = false;
        	
        $this->roles = $this->getAmbienteRoles();
        
        if ( ! is_array($this->roles) )
        {
        	return false;
        }
        
        if( !empty($roles)) {
            if (! in_array($roles, $this->roles)) 
            {
                $this->roles[] = $roles;//adciona ao array
            }
        }
        
        foreach ($this->roles as $role)
        {
        	if ( ! $this->hasRole($role)) {
        		$this->addRole(new GenericRole($role));
        	}
        
        	$feito = true;
        }
        
        $ambiente = new Container('infra');
         
        $ambiente->roles = $this->roles;
         
        return $feito;
    }
    
    /**
     * seta os recursos, modulos
     * tabela de modulo
     * @param string $recursos
     */
    public function setResource( $recurso = null )
    {
        $recursos = $this->getAmbienteResources();
        
        $feito = false;
        
        if (empty($recursos))
        {
           return $feito;
        }else {
            if (! in_array($recurso, $recursos))
            {
            	$recursos[] = $recurso;
            }
            
        }
        
        return $feito;
    }
    
    /**
     * seta as acoes, modulos
     * tabela de modulo
     * @param string $recursos
     */
    public function setAcao( $resources = null, $acao = null )
    {
    	$feito = false;
    	 
    	$this->acao = $this->getAmbienteAcao();
    
    	if( !empty($acao)) {
    		$this->acao[] = array ( $resources => $acao);//adciona ao array
    	}
    	 
    	return $feito;
    }
    /**
     * seta as permissoes,
     * @param unknown $actions
     */
    public function setActions($role = null, $recurso=null)
    {   
        $feito = false;
    	
    	$recursos = $this->getAmbienteResources();
    		
    	$roles =  $this->getAmbienteRoles();
    
    	
    	if(!in_array($role, $roles)) 
    	{
    	    
    	}
    	if (! is_array($roles) && ! is_array($recursos) ) {
    	    $this->allow($roles, $recursos);
    	    $feito=true;
    	}else  {
    	    foreach ($roles as $role)
    	    {
            	foreach ($recursos as $recurso)
            	{
        		    if (! $this->hasRole($role) )
        		    {
        		    	$this->addRole(new GenericRole($role));
        		    
        		    }
        		    
        		    if ( ! $this->hasResource($recurso) )
        		    {
        		    	$this->addResource(new GenericResource($recurso));
        		    }
        			
        				$this->allow($role, $recurso);
        				$feito=true;
        		}
        	}
    	}
    	
    	return $feito;
    }
    /**
     * Verifica se o role pode acessar aquele recurso
     * @param string $roles
     * @param string $recurso
     * @return boolean|NULL|Ambigous <boolean, NULL>
     */
    public function Verifica( $roles=null, $recurso, $acao=null) {

        $verifica = false;
        if (empty($roles)) {
        	$roles =  $this->getAmbienteRoles();
        }
        
        if (empty($recurso)) {
        	return false; // entrada unica e obrigatoria
        }
        
        if ($this->isAdmin()){
            return true;
        }
    	
    	if (! is_array($roles)) { // converte para array
    	    $roles = array($roles);
    	}

    	
    	foreach ($roles as $role)
    	{
    		if ( $this->hasRole($role)  &&	$this->hasResource($recurso) )
    		{
    			$verifica = $this->isAllowed($role, $recurso, $acao);
    			
    		}
    
    	}
    	return $verifica;
    }
    /**
     * Definindo o acesso publico pelo menu 
     * @return \Infra\Model\Login
     */
    public function Publico()
    {
        $this->removeAllow();
        $role = 'publico';
        $resource = 'publico';
        
       if (! $this->hasRole($role)   )
       {
           $this->addRole(new GenericRole($role));
       }
       
       if (! $this->hasResource($resource) ) 
       {
           $this->addResource(new GenericResource($resource));
       }
        
       $this->allow($role, $resource);
           
        return true;
    }
    
    /**
     * Definindo o acesso publico pelo menu
     * @return \Infra\Model\Login
     */
    public function Privado()
    {
        $roles = $this->getAmbienteRoles();
        
        $roles[] = 'autenticado';
        
        $resources = $this->getAmbienteResources();
        $resources[] = 'autenticado';
        $acao = $this->getAmbienteAcao();
         
       
       //coloca os menus nos recursos para serem permitidos
       if (in_array('autenticado', $roles)) {
            $resources[] = 'Cadastros';
       }
       
       if (in_array('juridico', $roles)) {
       	    $resources[] = 'juridico';
       }
       
       if (in_array('gerenciamentocontas', $roles)) {
       	$resources[] = 'gerenciamentocontas';
       }
        
        foreach ($roles as $role  )
        {   //cria o role se nao existir
            
            if (! $this->hasRole($role) )
            {
            	$this->addRole(new GenericRole($role));
            	
            }
            
            foreach($resources as $resource )
            {
                //cria o recuros se nao existir
            	if (! $this->hasResource($resource) )
            	{
            		$this->addResource(new GenericResource($resource));
            	}
            	
            	//atribui e permite a role no recurso
            	$this->allow($role,$resource);
            }
        }
        
    	
    	return $this;
    }
    

    /**
     * Função para gerar um token único este token serve para as próximas
     * validações da tela de avaliação   
     * @return string
     */
    public function setToken() {
        
        $bytes = Rand::getBytes(64, true);
        $this->token = base64_encode($bytes);
    }
    
    /**
     * Retorna o token gerado a cada sessao
     * @return string
     */
    public function getToken() {
    
        return $this->token;
    }
    /**
     * Atualiza o token no bancos
     */
    public function updateToken($sm) {
    
        $getEm = $sm->get('getEmFactory');//fabrica em application
        //chama banco
        $banco =  $sm->get('bancoFactory');
        
        //seta esquema publico
        $banco->setSchema($sm, 'public');
        
        $usuario = $this->getLogin(); //pega usuario
        
        //busca usuario no banco
        $login = $getEm->getRepository('Application\Entity\OauthUsers')->
        find($usuario->codigo);
        
        //atualiza em memoria
        $auth = $this->getAuth();
        $auth->getStorage()->write($usuario);
        
        $banco->save($login);
        
    	return true;
    }
    /**
     * Limpa o token no bancos
     */
    public function cleanToken($sm) {
    
    	$getEm = $sm->get('getEmFactory');//fabrica em application
    	//chama banco
    	$banco =  $sm->get('bancoFactory');
    
    	//seta esquema publico
    	$banco->setSchema($sm, 'public');
    
    	return true;
    }
    /**
     * Compara  o token gerado e retorna true ou false
     * @return string
     */
    public function isToken($token="") {
    
        $retorno= false;
        
        if (empty($token)) {
            $retorno=false;
        } else if ($token===$this->getToken()) {
            $retorno=true;
        }
        
    	return $retorno;
    }
    
    public function loginToken($sm, $token="") {
    
        $retorno = false;
        
        if (empty($token))
        {
        	return false;
        }
        
        $getEm = $sm->get('getEmFactory');//fabrica em application
        //chama banco
        $banco =  $sm->get('bancoFactory');
        
        //seta esquema publico
        $banco->setSchema($sm, 'public');
        
        //busca usuario no banco
        $usuario = $getEm->getRepository('Application\Entity\OauthUsers')->
        findBy(array('token'=>$token));
        
        if (empty($usuario))
        {
        	$retorno = false;
        	
        }else if (! empty($usuario[0]->getCodigo()))
        {
            $login = array(
                'codigo'=>$usuario[0]->getCodigo(),
                'login'=>$usuario[0]->getLogin(),
                'token'=>$usuario[0]->getToken(),        
            );
            
            $auth = $this->getAuth();
            $auth->getStorage()->write($login);
            
            $retorno = true;
        }
    	
        return $retorno;
    }
    
    public function setAbreAvaliacao() {
    
    	$retorno = false;
    
    	$sm = $this->sm;
    	 
    	$getEm = $sm->get('getEmFactory');//fabrica em application
    	//chama banco
    	$banco =  $sm->get('bancoFactory');
    
    	//seta esquema publico
    	$banco->setSchema($sm, 'public');
         
    	$codigo = $this->getLogin()->codigo;
    	//busca usuario no banco
    	$usuario = $getEm->getRepository('Application\Entity\OauthUsers')->
    	find($codigo);
    
        $usuario->setToken(1);
        
        $data = date('d-m-Y');
        $hora = date('H:i:s');
        
        $usuario->setData(new \DateTime($data));
        $usuario->setHora(new \DateTime($hora));
        
        $banco->save($usuario);
        	 
    	return $retorno;
    }
    public function setFechaAvaliacao() {
    
    	$retorno = false;
    
    	$sm = $this->sm;
    		
    	$getEm = $sm->get('getEmFactory');//fabrica em application
    	//chama banco
    	$banco =  $sm->get('bancoFactory');
    
    	//seta esquema publico
    	$banco->setSchema($sm, 'public');
    	 
    	$codigo = $this->getLogin()->codigo;
    	//busca usuario no banco
    	$usuario = $getEm->getRepository('Application\Entity\OauthUsers')->
    	find($codigo);
    
    	$usuario->setToken(0);
    
    	$banco->save($usuario);
    
    	return $retorno;
    }
    /**
     * Verifica se email existe na tabela de usuarios
     * @param unknown $email
     * @return boolean
     */
    
    public function existeEmail($sm,$email) {
    	
    	//$sm = $this->sm;
    
    	$getEm = $sm->get('getEmFactory');//fabrica em application
    	//chama banco
    	$banco =  $sm->get('bancoFactory');
    
    	//seta esquema publico
    	$banco->setSchema($sm, 'public');
        $codigo = $this->getLogin()->codigo ;
    	$query = $getEm->
    	createQuery("select u
    			from Application\Entity\OauthUsers u
    			where u.codigo != $codigo and u.email = '$email' ");
    	
    	$usuario = $query->getResult();
    
    	if (!empty($usuario)) {
    	    return true; // existe
    	}else {
    	    return false; //nao existe
    	}
    	
    }
    
    public function getLogotipo() {
        
        if ($this->isLoggedIn())
        {
        	$ambiente = new Container('infra');
        
        	if (isset($ambiente->logotipo))
        	{
        	    $imagem = new ImagemAvaliacao(null, null, null);
        		return $imagem->getLogotipo($ambiente->logotipo);//nome do logotipo
        	}
        
        }
        return null;
        
    }
    
}
