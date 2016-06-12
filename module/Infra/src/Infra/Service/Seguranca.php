<?php
namespace Infra\Service;

use Zend\Crypt\Password\Bcrypt;

class Seguranca
{
    
    public function criaSenha($senha) {
        $bcrypt = new Bcrypt();
        $senha = $bcrypt->create($senha);
        return $senha;
    }
    
    public function verificaSenha($dbCredential, $requestCredential) {
    	return (new Bcrypt())->verify($requestCredential, $dbCredential);
    }
    
}

?>