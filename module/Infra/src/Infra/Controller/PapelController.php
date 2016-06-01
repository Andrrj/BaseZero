<?php
namespace Infra\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Papel;
use Application\Entity\Modulo;
use Zend\Form\Form;
/**
 * PapelController
 *
 * @author
 *
 * @version
 *
 */
class PapelController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
       
        $banco =  $this->getServiceLocator()->get('bancoFactory');
        
        $banco->setSchema($this->getServiceLocator(), 'public');
        
        $getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
        
        $dados = $getEm->getRepository('Application\Entity\Papel')->
        findBy(array(), array('codigo'=>'asc') );
        return new ViewModel(array(
        		'registro' => $dados,
        ));
      
    } 


    public function addAction()
    {
        
        /*
         * cadastro de modulos
         * insert into modulo (nome, controller) values('papel', ' Infra\Controller\Papel');
         */
    	$translator = $this->getServiceLocator()->get('translator');
    	$tempFile = null;
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	 
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    	$banco->setSchema($this->getServiceLocator(), 'public');
    
    	$form = $this->getServiceLocator()->get('papelFactory');
    	$dados = new Papel($objectManager);
    	
    	$form->get('submit')->setAttribute('value', $translator->translate('Salvar'));
    	
    	$form->bind($dados);
    
    	if ($this->request->isPost()) {
    		$form->setData($this->request->getPost());
    		 
    		if ($form->isValid()) {
    			$banco->save($dados);
    			$this->flashMessenger()
    			->setNamespace('success')
    			->addMessage('Novo registro');
    			 
    			return $this->redirect()->toRoute('papel');
    		}
    
    	}
    	 
    	 
    	return array('form'=>$form);
    
    }
    
    public function editAction()
    {
        
      
        $translator = $this->getServiceLocator()->get('translator');
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$codigo = (int) $this->params()->fromRoute('codigo', null);
    	 
    	if (is_null($codigo))
    	{
    		return $this->redirect()->toRoute('usuario', array(
    				'action' => 'add'
    		));
    	}
    	
    	$getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
    	 
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    	$banco->setSchema($this->getServiceLocator(), 'public');
    	 
    	$dados = $getEm->getRepository('Application\Entity\Papel')->find($codigo);
    
    	$form = $this->getServiceLocator()->get('papelFactory');
    	 
    	$form->get('submit')->setAttribute('value', $translator->translate('Editar'));
    	   
    	$form->bind($dados);
    
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    	   
    		$form->setData($request->getPost());
    		
    		if ($form->isValid())
    		{
    			
    			$banco->save($dados);
       
    			$this->flashMessenger()
    			->setNamespace('success')
    			->addMessage('Registro atualizado');
    			return $this->redirect()->toRoute('papel');
    		}
    	}
    	 
    	$messages = '';
    	if ($this->flashMessenger()->getMessages())
    	{
    		$messages = implode(',', $this->flashMessenger()->getMessages());
    	}
    	
    	return array(
    			'codigo' => $codigo,
    	    'form' => $form,
    	);
    }
    
    public function deleteAction()
    {
    	$codigo = (int) $this->params()->fromRoute('codigo', null);
    	if (is_null($codigo))
    	{
    		return $this->redirect()->toRoute('papel');
    	}
    
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    
    	$banco->setSchema($this->getServiceLocator(), 'public');
    	$getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
    
    	$dados = $getEm->getRepository('Application\Entity\Papel')->find($codigo);
    
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$del = $request->getPost('del', 'Nao');
    
    		if ($del == 'Sim')
    		{
    			$dados = $getEm->getReference('Application\Entity\Papel', $codigo);
    			$banco->remove($dados);
    			$this->flashMessenger()
    			->setNamespace('success')
    			->addMessage('Excluído');
    
    		}
    
    		return $this->redirect()->toRoute('papel');
    	}
    
    	return array(
    			'codigo' => $codigo,
    			'form' => $this->getDeleteForm($codigo),
    			'objeto' => $dados
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