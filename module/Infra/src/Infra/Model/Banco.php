<?php
namespace Infra\Model;

use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine;
use Doctrine\DBAL\Driver\ExceptionConverterDriver;
use Zend\Session\Container;
use Doctrine\DBAL\DriverManager;
/**
 * 
 * @author andre
 * 
 * Retorna uma instancia do doctrine para ser executada em todos os projetos
 * 
 *
 */
class Banco
{
    /** @var object instancia da fabrica getEmfactory */
    
    private $doctrine;
    private $esquema;
    private $configSQL;
    private $sm;
    private $conn;
    
    /**
     * 
     * @param ServiceLocatorInterface $sm entrada do service locator 
     */
    public function __construct(ServiceLocatorInterface $sm) {
        $this->sm = $sm;
        $this->doctrine = $sm->get('getEmFactory');
        
        $this->configSQL = new \Doctrine\DBAL\Configuration();
        
    }
    /**
     * Clone
     */
    public function __clone() {
        $this->id = null;
    }
    public function clonar($entity) {
     //   $nova = $this.__clone $entity;
        $this->doctrine->detach($entity);
        $this->doctrine->persist($entity);
        $this->execute();
        
    }
    /**
     * 
     * @param Application\Entity $entity
     */
    public function save( $entity) {
        
       	$this->doctrine->persist($entity);
     	$this->execute();
         
    }
   /**
    * 
    * @param Application\Entity $entity
    */
    public function remove($entity) {
        $this->doctrine->remove($entity);
        $this->execute();
    }
    /**
     * Executa o flush do doctrine
     */
    public function execute(){
    	
        $this->doctrine->flush();
      
    }
    
    /**
     * Limpa a tabela para o novo registro
     */
    public function novo(){
    
    	$this->doctrine->clear();
    }
    
    /**
     * Habilita o esquema do postgres
     * @param ServiceLocatorInterface $sm
     * @param string $schema
     */
     
    public function setSchema(ServiceLocatorInterface $sm, $schema = null){
    
    	return;
    	
    }
    /**
     * Retorna o objeto da tabela para o controllador de servicos
     * @param ServiceLocatorInterface $sm
     * @param string $config
     * @return unknown
     */
    public function getTabela(ServiceLocatorInterface $sm, $config = null) {

        $getEm = $sm->get('getEmFactory');//fabrica em application
        
        $dados = $getEm->getRepository($config['tabela'])->findAll();
        
        return $dados; 
    }
   
    /**
     *  
     * Esta funcao verifica se existe o campo descricao na tabela
     * se existir retorna o objeto para o doctrine caso não exista,
     * cria um novo e retorna o objeto para o doctrine
     * @param unknown $descricao
     * @param unknown $tabela
     * @param unknown $objeto
     * @return unknown
     */
     
    public function getDependencia(ServiceLocatorInterface $sm, $config) {
    
        $descricao = $config['descricao'];
        $campo = $config['campo'];
        $objeto = $config['objeto'];
        $tabela = $config['tabela'];
        
        $getEm = $sm->get('getEmFactory');//fabrica em application
    	
    	$registro = $getEm->getRepository($tabela)->
    	findBy(array($config['campo'] =>$descricao) );
    	$registro = $registro[0];
    
    	if (empty($registro)) { //se area nao existe, grava
    		$objeto->setDescricao($descricao);
    
    		$sm->persist($objeto);
    		$sm->flush();
    
    		return $objeto;
    	}else {
    		return $registro;
    	}
    	 
    }
    
    public function executeSQL($sql)
    {
        if ( ! isset($this->conn)) 
        {
            $this->conn = DriverManager::getConnection($this->configParametros, $this->configSQL);
        }
        

        $auth = $this->sm->get('Login');
        $schema = $auth->getSchema();
        
        $sql_schema = "set search_path TO $schema";
        $stmt = $this->conn->query($sql_schema); // Simple, but has several drawbacks
        
        $stmt = $this->conn->query($sql); // Simple, but has several drawbacks
        
        return $stmt;
        
    }
    
}

?>