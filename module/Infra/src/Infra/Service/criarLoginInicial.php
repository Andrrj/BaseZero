<?php
/*
 * Direciona a tela se admin existir,
 * tela de login senao, tela de cadastro
 */
namespace Infra\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Entity\Usuario;


class criarLoginInicial implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $services) {

        $getEm = $services->get('getEmFactory');//fabrica em application
        
        //verifica se o usuário existe
        $usuario = $getEm->getRepository('Application\Entity\Usuario')->
        find(1);

       //usuario nao existe
        if ( count($usuario) <= 0) { //Formulario de cadastro
        	$form = $services->get('criarLoginInicialFactory');//fabrica em service
        	return $form;
        }
        return false;
    }
}