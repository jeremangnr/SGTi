<?php

namespace SGTi\Security;

class MyAcl extends \Zend_Acl {

    private static $instance;
    
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new MyAcl();
        }

        return self::$instance;
    }
    
    private function __construct() {
        //Se agregan los roles 
        $this->addRole(new \Zend_Acl_Role(\SGTi\Security\Roles::ALU));
        $this->addRole(new \Zend_Acl_Role(\SGTi\Security\Roles::DOC));
        $this->addRole(new \Zend_Acl_Role(\SGTi\Security\Roles::ADMIN));
        //Administrativo-Docente
        $this->addRole(new \Zend_Acl_Role(\SGTi\Security\Roles::ADM_DOC),\SGTi\Security\Roles::ADMIN, \SGTi\Security\Roles::DOC);
        //Alumno-Docente
        $this->addRole(new \Zend_Acl_Role(\SGTi\Security\Roles::ALU_DOC),\SGTi\Security\Roles::ALU, \SGTi\Security\Roles::DOC);
        
        //Se agregan los recursos al ACL
        $this->addResource(new \Zend_Acl_Resource(\SGTi\Security\Recursos::MOD_ADMIN));
        $this->addResource(new \Zend_Acl_Resource(\SGTi\Security\Recursos::MOD_ALU));
        $this->addResource(new \Zend_Acl_Resource(\SGTi\Security\Recursos::MOD_DOC));
        $this->addResource(new \Zend_Acl_Resource(\SGTi\Security\Recursos::FORO));
	$this->addResource(new \Zend_Acl_Resource(\SGTi\Security\Recursos::MATERIAL));

        //Se agregan los permisos
        $this->allow(Roles::ALU, Recursos::MOD_ALU);
        $this->allow(Roles::ALU, Recursos::FORO);
	$this->allow(Roles::ALU, Recursos::MATERIAL);
	
        
        $this->allow(Roles::DOC, Recursos::MOD_DOC);
        $this->allow(Roles::DOC, Recursos::FORO);
        $this->allow(Roles::DOC, Recursos::MATERIAL);
	
        $this->allow(Roles::ADMIN, Recursos::MOD_ADMIN);
        $this->allow(Roles::ADMIN, Recursos::FORO);
	$this->allow(Roles::ADMIN, Recursos::MATERIAL);
    }
    
    public function verificar($rol,$recurso){
        $resultado = $this->isAllowed($rol, $recurso);
        return $resultado;
    }

}