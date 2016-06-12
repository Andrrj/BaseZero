<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Infra for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Infra\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Entity\Usuario;
use Infra\Model\Login;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;
use Application\Entity\Auditoria;
use Questionario\Service\questionarioView;
use Questionario\Model\questionarioJSON;
use Questionario\Model\questionarioBase;
use Infra\Model\LogAuditoria;

class InfraController extends AbstractActionController
{
    /*
     * tela de cadastro do primeiro usuario
     */
    public function indexAction()
    {
        $form = $this->getServiceLocator()->get('criarLoginInicial');
       
        return array('form'=>$form);

    }
/*
 * cadastra o primeiro usuário uid=1
 * geralmente é o admin 
 * 
 */
    public function addAction()
    {
        $getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
    	
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	    	
    	$form = $this->getServiceLocator()->get('telaCadastro'); //monta tela de cadastro inicial

    	$request = $this->getRequest();
    	$usuario = new Usuario($objectManager);
    	$form->bind($usuario);
    	
    	
    	if ($this->request->isPost()) {
    	    
            $form->setData($this->request->getPost());
            
    		if ($form->isValid()) {
    		    
    		    $senha = $usuario->getSenha();
    		    $usuario->setSenha(md5($senha));
    		    
    		    $banco =  $this->getServiceLocator()->get('bancoFactory');
    		    
    		    // Persist
    			$banco->save($area);
    			
    		    return $this->redirect()->toRoute('home');
    		}

    	}
    	
    	return array('form' => $form);
    
    }
    /**
     * 
     * Monta e valida tela de login
     * 
     * @return multitype:array
     */
    public function loginAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        $form = $this->getServiceLocator()->get('telaLogin');
                 
         $request = $this->getRequest();
         
         if ($this->request->isPost()) {
             
         	$form->setData($this->request->getPost());
         
         	if ($form->isValid()) {
         
         	  $login = $this->request->getPost('login');
         	  $senha = $this->request->getPost('senha');
         	  $token = $this->request->getPost('token');
         	  
         	  $autentica = $this->getServiceLocator()->get('Login');
         	  
         	  $autentica->setLogin($login, $senha);
         	  
         	  if($autentica->authenticate($this->getServiceLocator()))
         	  {
         	      return $this->redirect()->toRoute('home');
         	  }else {
         	     // echo "nao autenticou";
         	      $this->flashMessenger()
         	      ->setNamespace('error')
         	      ->addMessage($autentica->messages);
         	  }
         	  
         	}
         
         }
                  
        return array('form'=>$form);
    }
    
    /**
     *
     * Monta e valida tela de login para as avaliacoes online
     * retorna um json com as informações dos questionários
     *
     * @return multitype:array
     */
    public function logintokenAction()
    {
        header('Access-Control-Allow-Origin: *'); 
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        
       // $postdata = file_get_contents("php://input");
        $request = $this->getRequest();
        $user = $this->request->getPost('login');
        $senha = $this->request->getPost('password');
        $dados = $this->request->getPost('dados');
        
        if ( empty($user) && empty($senha) ){
            $saida = false;
        }else {
            $login =  $this->getServiceLocator()->get('login');
             
            if (! $login->isLoggedIn() && !empty($user) && !empty($senha))
            {
            
            	//loga
            	$login->setLogin($user, $senha);
            
            	if($login->authenticate($this->getServiceLocator()))
            	{
            
                	if (empty($dados) ) {
                        $saida = $this->carregaQuestionario($login);
                    }else {
                        $saida = $this->sincroniza($dados);
                    }
            
            	}else {
            		$saida = array ('token'=> "false");
            	}
            
            }else {
                
                if (empty($dados) ) {
                    $saida = $this->carregaQuestionario($login);
                }else {
                    $saida = $this->sincroniza($dados);
                }
            	
            }
            
        }
            
 	    $response = $this->getResponse();
 	    $response->setContent(\Zend\Json\Json::encode( $saida));		
 	    return $response;
    }
    
    /**
     * carrega quesitonario
     */
    
    public function carregaQuestionario($login)
    {
        
        //pega o questionario
        $banco =  $this->getServiceLocator()->get('bancoFactory');
        
        $banco->setSchema($this->getServiceLocator());
        
        $getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
        
        $usuario = $login->getLogin();
        
        $login->setAbreAvaliacao($this->getServiceLocator());
        
        $data = date("d/m/Y");
    	$hora = date("H:i:s");
    	
    	$mensagem = "O usuário ".$login->getLogin()->login;
    	$mensagem .= " carregou a auditoria em ".$data;
    	$mensagem .= " as ".$hora;
    	
    	$auditoria = new LogAuditoria($this->getServiceLocator());
    	$auditoria->Write($mensagem);
    	
        $getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
        
       // $questionario = new questionarioJSON($this->getServiceLocator()) ;
        
        $saida = array();
        
        $avaliador =  $getEm->
        getRepository('Application\Entity\AuditoriaAvaliador')->
        findBy(array('usuario'=>$usuario->codigo)) ;
        
        $questionario = new questionarioJSON($this->getServiceLocator()) ;
        
        foreach ($avaliador as $a  ) {
        	$getauditoria = array(
        			'codigoauditoria'=>$a->getAuditoria()->getCodigo(),
        			'descricao'=>  $a->getAuditoria()->getDescricao(),
        			'objetivo'=>   $a->getAuditoria()->getObjetivo(),
        			'objeto1'=>    $a->getAuditoria()->getObjeto1(),
        			'objeto2'=>    $a->getAuditoria()->getObjeto2(),
        			'objeto3'=>    $a->getAuditoria()->getObjeto3(),
        	);
        	 
        	foreach ( $a->getAuditoria()->getProjeto() as $projeto ){
        		$questionario->setQuestao($projeto->getCodigo());
        		$getquestionario[] =  $questionario->getQuestionario();
        	}
        
        	$getauditorias[$a->getAuditoria()->getCodigo()] = array(
        			'codigoauditoria'=>$a->getAuditoria()->getCodigo(),
        			'auditoria'=>$getauditoria,
        			'questionario'=>$getquestionario,
        	);
        	$getquestionario = array();
        }
        $saida= array(
        		'auditorias'=>$getauditorias,
        		'token'=>$login->getToken(),
        );
        
        return $saida;
        
    }
    /**
     *
     * Monta e valida tela de login para as avaliacoes online
     * retorna um json com as informações dos questionários
     *
     * @return multitype:array
     */
    public function sincroniza($dados)
    {
    	
    	$questionario = new questionarioJSON($this->getServiceLocator()) ;
    	
    	$saida = $questionario->sincroniza($dados);
    	
    	//desconecta usuario
    	$auth = $this->getServiceLocator()->get('Login');
    	$data = date("d/m/Y");
    	$hora = date("H:i:s");
    	
    	$mensagem = "O usuário ".$auth->getLogin()->login;
    	$mensagem .= " sincronizou a Avaliação em ".$data;
    	$mensagem .= " as ".$hora;
    	
    	$auditoria = new LogAuditoria($this->getServiceLocator());
    	$auditoria->Write($mensagem);
    	
    	$auth->setFechaAvaliacao();
    	$auth->logout();
    	$auth->cleanToken($this->getServiceLocator());
    	
    	return $saida;
    }
    
    
    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('Login');
        $auth->logout();
        $auth->cleanToken($this->getServiceLocator());
    	return $this->redirect()->toRoute('home');
    }
    
    public function verificaAction()
    {
        $auth = $this->getServiceLocator()->get('Login');
                
    	if (! $auth->isLoggedIn()) {
    	//    echo "nao logou";
    	    
    	}else {
    	 //   echo "esta logado";
    	}
    	
        exit;
    }
    
    public function acessonegadoAction()
    {
        return array();
    }
    
}
