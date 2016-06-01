<?php
namespace Infra\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Modulo;
use Infra\Form\clienteModuloForm;
use Zend\Form\Form;

/**
 * ModuloController
 * Cadastro de Modulos
 * @author
 *
 * @version
 *
 */
class ModuloController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        $banco =  $this->getServiceLocator()->get('bancoFactory');
        
        $banco->setSchema($this->getServiceLocator(), 'public');
        
        $getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
        
        $data = $getEm->getRepository('Application\Entity\Modulo')->
        findBy(array(), array('codigo'=>'asc') );
        
        return new ViewModel(array(
        		'registro' => $data,
        ));
      
    }

    public function addAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    	$banco->setSchema($this->getServiceLocator(), 'public');
    	
    	$form = $this->getServiceLocator()->get('moduloFactory');
    	
    	$dados = new Modulo();
    	
    	$form->bind($dados);
    	
    	$form->get('submit')->setValue('Salvar');   	

    	if ($this->request->isPost()) {
    	   $form->setData($this->request->getPost());
    	   
    	   if ($form->isValid()) {
    	       $banco->save($dados);
    	       
    	       $this->flashMessenger()
    	       ->setNamespace('success')
    	       ->addMessage('Novo registro');
    	       
              return $this->redirect()->toRoute('modulo');
    		
    		}//fim valido
    	}//fim post
    	 
    	
    	return array('form' => $form);
    
    }
    
    public function editAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
    	$codigo = (int) $this->params()->fromRoute('codigo', null);
    	 
    
    	if (is_null($codigo))
    	{
    		return $this->redirect()->toRoute('modulo', array(
    				'action' => 'add'
    		));
    	}
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    
    	$banco->setSchema($this->getServiceLocator(), 'public');
    	$getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
    	 
    	$form = $this->getServiceLocator()->get('moduloFactory');
    
    	$form->get('submit')->setAttribute('value', 'Editar');
    	 
    	$dados = $getEm->getRepository('Application\Entity\Modulo')->find($codigo);

    	$form->bind($dados);
    	 
    	if ($this->request->isPost()) {
    			
    		$form->setData($this->request->getPost());
    		 
    
    		if ($form->isValid()) {
    
    			$banco->save($dados);
    
    			$this->flashMessenger()
    			->setNamespace('success')
    			->addMessage('Atualizado');
    
    			return $this->redirect()->toRoute('modulo');
    
    		}
    
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
    		return $this->redirect()->toRoute('modulo');
    	}
    
    	$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$banco =  $this->getServiceLocator()->get('bancoFactory');
    	 
    	$banco->setSchema($this->getServiceLocator(), 'public');
    	$getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
    	 
    	$dados = $getEm->getRepository('Application\Entity\Modulo')->find($codigo);
    
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$del = $request->getPost('del', 'Nao');
    
    		if ($del == 'Sim')
    		{
    			$dados = $getEm->getReference('Application\Entity\Modulo', $codigo);
    			$banco->remove($dados);
    			$this->flashMessenger()
    			->setNamespace('success')
    			->addMessage('Excluído');
    
    		}
    
    		return $this->redirect()->toRoute('modulo');
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