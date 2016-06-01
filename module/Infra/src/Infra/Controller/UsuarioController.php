<?php
namespace Infra\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Usuario;
use Application\Entity\ClienteEmpresa;
use Zend\Form\Form;
use Zend\Crypt\Password\Bcrypt;

/**
 * UsuarioController
 * Cadastro de usuários
 * @author
 *
 * @version
 *
 */
class UsuarioController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
       
        $banco =  $this->getServiceLocator()->get('bancoFactory');
        
        $banco->setSchema($this->getServiceLocator(), 'public');
        
        $getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
        
        $dados = $getEm->getRepository('Application\Entity\Usuario')->
        findBy(array(), array('codigo'=>'asc') );
       
        return new ViewModel(array(
        		'registro' => $dados,
        ));
      
    }
    
    public function addAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
       	$tempFile = null;
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    
    	
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    	$banco->setSchema($this->getServiceLocator(), 'public');
    
    	$form = $this->getServiceLocator()->get('usuarioFactory');
    	
    	$dados = new Usuario($objectManager);
    	
    	$form->bind($dados);
    
    
    	if ($this->request->isPost()) {
    	 
    	    $form->setData($this->request->getPost());
   	   
    	          	   
    		if ($form->isValid()) {
    		        		
    			$dados->setEmpresa(NULL);
		        $bcrypt = new Bcrypt();
		        $senha = $bcrypt->create($dados->getSenha());
		        
        		$dados->setSenha($senha); 
        		
    			$banco->save($dados);
    			$this->flashMessenger()
    			->setNamespace('success')
    			->addMessage('Novo registro');
    			
    			return $this->redirect()->toRoute('usuario');
    		}else {
    		    
    		    $this->flashMessenger()
    		    ->setNamespace('error')
    		    ->addMessage($form->getMessages());
    		}
    
    	}
    	
    	return array('form'=>$form);
    
    }
    
    public function editAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$codigo = (int) $this->params()->fromRoute('codigo', null);
    	 
    	$codigo = $this->verificaUsuario($codigo);
    	
    	if (is_null($codigo))
    	{
    		return $this->redirect()->toRoute('usuario', array(
    				'action' => 'add'
    		));
    	}
    
    	$getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
    	
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    	$banco->setSchema($this->getServiceLocator(), 'public');
    	
    	$dados = $getEm->getRepository('Application\Entity\Usuario')->find($codigo);
    
    	$form = $this->getServiceLocator()->get('usuarioFactory');
    	
    	$form->remove('senha');
    	$form->remove('senha1');
    	$form->get('submit')->setAttribute('value', $translator->translate('Salvar'));
    	
    	$dados->setEmpresa(NULL);
    	
    	$form->bind($dados);
    
    	$request = $this->getRequest();
    	
    	if ($request->isPost())
    	{
    	    $form->setData($this->request->getPost());
    	    
//     	    var_dump($request->getPost());
//     	    var_dump($form->isValid());
    	    
//     	    var_dump($form->getMessages());
//     	    exit;
    		
    		if ($form->isValid())
    		{
    			$banco->save($dados);
    		    
    		    $this->flashMessenger()
    		    ->setNamespace('success')
    		    ->addMessage('Registro atualizado');
    			return $this->redirect()->toRoute('usuario');
    			
    		}else {
    		    
    		    $this->flashMessenger()
    		    ->setNamespace('error')
    		    ->addMessage($form->getMessages());
    		    
    		   //var_dump("<br><br><br>", $form->isValid());
    		}
    	}
    	
    	$messages = '';
    	if ($this->flashMessenger()->getMessages())
    	{
    		$messages = implode(',', $this->flashMessenger()->getMessages());
    	}
    
    	return array(
    			'codigo' => $codigo,
    			//'usuario' => $this->limpaUsuarioForm($form),
    	        'form'=>$form,
    	);
    }
    /**
     * Altera a senha dos usuaroos server para admin ou usuario comum
     * 
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|multitype:number NULL
     */
    public function passwordAction()
    {
    	$translator = $this->getServiceLocator()->get('translator');
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$codigo = (int) $this->params()->fromRoute('codigo', null);
    
    	
    	$login = $this->getServiceLocator()->get('Login');
    	 
    	$codigo = $this->verificaUsuario($codigo);
    	
    	$getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
    	 
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    	$banco->setSchema($this->getServiceLocator(), 'public');
    	 
    	$dados = $getEm->getRepository('Application\Entity\Usuario')->find($codigo);
    
    	$form = $this->getServiceLocator()->get('senhaFactory');
    	 
    	$form->get('submit')->setAttribute('value', $translator->translate('Edit'));
    	 
    	$form->bind($dados);
    
    	$request = $this->getRequest();
    	
    	if ($request->isPost())
    	{
    			
    		$form->setData($request->getPost());
    
    
    		if ($form->isValid())
    		{
        		$bcrypt = new Bcrypt();
		        $senha = $bcrypt->create($request->getPost()->senha);
		        
        		$dados->setSenha($senha); 
    			$banco->save($dados);
    
    			$this->flashMessenger()
    			->setNamespace('success')
    			->addMessage('Registro atualizado');
    			return $this->redirect()->toRoute('usuario');
    		}
    	}
    	 
    	$messages = '';
    	if ($this->flashMessenger()->getMessages())
    	{
    		$messages = implode(',', $this->flashMessenger()->getMessages());
    	}
    
    	$usuario = $login->getLogin();
    	
    	
    	return array(
    			'codigo' => $codigo,
    			'senha' => $this->limpaSenhaForm($form),
    	        'login'=>$usuario->login,
    	        'usuario'=>$dados->getLogin(),
    	);
    }
    
    /**
     * Funcao para que o usuario não altere a senha de outro 
     * se for admin, recebe o codigo normal
     * se for usuario comum, pega o codigo do ambiente
     * se codigo for vazio para os dois, direciona para a tela de 
     * adicionar usuario
     * 
     * @param unknown $codigo
     */
    public function verificaUsuario($codigo) 
    {
        $login = $this->getServiceLocator()->get('Login');

         
        if ($login->isAdmin() && is_null($codigo) )
        {
        	return $this->redirect()->toRoute('usuario', array(
        			'action' => 'add'
        	));
        }else if (! $login->isAdmin() ){
        	$codigo = $login->getLogin()->codigo;
        }
        return $codigo;
    }
    /**
     * retira os campos do form para alterar o usuario
     * @param unknown $form
     * @return unknown
     */
    public function limpaUsuarioForm($form) {
    
    	$form->remove('senha');
    	$form->remove('senha1');
    	
    	return $form;
    }
    /**
     * idem ao anterior
     * @param unknown $form
     * @return unknown
     */
    public function limpaSenhaForm($form) {
    	
    	$form->remove('login');
    	$form->remove('email');
    	$form->remove('empresa');
    	$form->remove('locale');
    	return $form;
    }
    
    public function deleteAction()
    {
    	$codigo = (int) $this->params()->fromRoute('codigo', null);
    	if (is_null($codigo))
    	{
    		return $this->redirect()->toRoute('usuario');
    	}
    	$getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
    	$usuario = $getEm->getRepository('Application\Entity\Usuario')->find($codigo);
    
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    	$banco->setSchema($this->getServiceLocator(),'public');
    
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$del = $request->getPost('del', 'Nao');
    
    		if ($del == 'Sim')
    		{
    			$usuario = $getEm->getReference('Application\Entity\Usuario', $codigo);
    			$banco->remove($usuario);
    			
    			$this->flashMessenger()
    			->setNamespace('success')
    			->addMessage('Registro excluído');
    
    		}
    
    		return $this->redirect()->toRoute('usuario');
    	}
    
    	return array(
    			'codigo' => $codigo,
    			'form' => $this->getDeleteForm($codigo),
    			'objeto' => $usuario
    	);
    }
    
    public function getDeleteForm($codigo)
    {
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    
    	$form = new Form();
    	$form->setAttribute('method', 'post');
    	$form->add(array(
    			'name' => 'del',
    			'attributes' => array(
    					'type'  => 'submit',
    					'value' => 'Sim',
    					'id' => 'del',
    					'class'=>"btn btn-default"
    			),
    	));
    
    	$form->add(array(
    			'name' => 'return',
    			'attributes' => array(
    					'type'  => 'submit',
    					'value' => 'Não',
    					'id' => 'return',
    					'class'=>"btn btn-default"
    			),
    	));
    
    
    	return $form;
    }
    
}