<?php
/*
 * Arquivo de modelo de usuário para a conexao sem 
 * o doctrine
 */

namespace Infra\Model;

use Zend\Math\Rand;

/**
 * Pega as descricoes dos campos para o autocomplete
 * @author andre
 *
 */
class Autocomplete  {
    private $service;
    private $table;
    private $descricao;
    private $field;
    
    
    public function __construct($service, $table, $field=null)
    {
            $this->service = $service;
            $this->table = $table;
            $this->field = $field;
    }
    
    /**
     * retorna um array com a descricao do banco
     */
    public function getDescricao()
    {
        
        if (empty($this->descricao)) {
            
            $getEm = $this->service->get('getEmFactory');//fabrica em application
            
            $query = $getEm->createQuery('SELECT distinct d 
                                        FROM '.$this->table.' d
                                        ');
            $resultado = $query->getResult();
            
            foreach ($resultado as $registro) {
                $this->descricao[] = $registro->getDescricao();
            }
            
            return $this->descricao = json_encode($this->descricao); //pega usuario
        }

        
        return $this->descricao; //pega usuario
    }
    
    /**
     * retorna um array com a descricao do banco
     */
    public function getItem()
    {
    
    	if (empty($this->descricao)) {
    
    		$getEm = $this->service->get('getEmFactory');//fabrica em application
    
    		$query = $getEm->createQuery('SELECT distinct d.'.$this->field.' as descricao
                                        FROM '.$this->table.' d
                                        ');
    		$resultado = $query->getResult();
    
    		foreach ($resultado as $registro) {
    			$this->descricao[] = $registro['descricao'];
    		}
    
    		return $this->descricao = json_encode($this->descricao); //pega usuario
    	}
    
    
    	return $this->descricao; //pega usuario
    }
    
}