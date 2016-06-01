<?php
namespace Infra\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * LogController
 *
 * @author
 *
 * @version
 *
 */
class LogController extends AbstractActionController
{

    public function indexAction()
    {
       // $form = new PadraoForm();
        $banco =  $this->getServiceLocator()->get('bancoFactory');
        
        $banco->setSchema($this->getServiceLocator());
        
        $getEm = $this->getServiceLocator()->get('getEmFactory');//fabrica em application
        
        $dados = $getEm->getRepository('Application\Entity\AuditoriaLog')->findAll();
        return new ViewModel(array(
        		'registro' => $dados,
              //  'form' => $this->getBuscaForm($codigo)
        ));
        
    }
}