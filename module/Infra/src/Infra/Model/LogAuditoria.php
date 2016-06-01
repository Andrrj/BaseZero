<?php
/*
 * Arquivo de modelo de usuário para a conexao sem 
 * o doctrine
 */

namespace Infra\Model;

use Zend\Authentication\AuthenticationService;
use Application;
use Application\Entity\AuditoriaLog;

/**
 * Controle de login
 * @author andre
 *
 */
class LogAuditoria  {
    private $data;
    private $hora;
    private $uid;
    private $ambiente;
    private $mensagem;
    private $tipo;
    private $modulo;
    private $service;
    private $schema;
   
    /**
     * Atribui os servicos, usuarios e schemas
     * @param unknown $service
     */
    public function __construct($service)
    {
    	$this->service = $service;
    	//$this->mensagem = $mensagem;
    	$login = $this->service->get('Login');
    	$this->schema = $login->getSchema();
    	$this->uid = $login->getLogin()->codigo;
    }
    
    /**
     * Escreve a mensagem conforme o tiṕo 
     * @param unknown $mensagem
     * @param string $tipo
     * @return boolean
     */
    
    public function Write($mensagem, $tipo=null, $modulo=null)
    {
        if (empty($tipo)) {
            $tipo = 1;
        }
        
        $getEm = $this->service->get('getEmFactory');//fabrica em application
        $tipo = $getEm->getRepository('Application\Entity\TipoLog')->find($tipo);
       
        $banco =  $this->service->get('bancoFactory');
        
        $banco->setSchema($this->service);
        
        $auditoriaLog = new AuditoriaLog();
        
        $data = date('d-m-Y');
    	$hora = date('H:i:s');
        $auditoriaLog->setData(new \DateTime($data));
        $auditoriaLog->setHora(new \DateTime($hora));
        $auditoriaLog->setMensagem($mensagem);
        $auditoriaLog->setTipo($tipo);
        $auditoriaLog->setUid($this->uid);
        
        $banco->save($auditoriaLog);
        
        
    }
    
}