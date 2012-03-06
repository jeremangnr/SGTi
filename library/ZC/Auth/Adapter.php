<?php

/**
 * Description of Adapter
 *
 * @author elfozi
 */
class ZC_Auth_Adapter implements Zend_Auth_Adapter_Interface {
    const NOT_FOUND_MSG = "Usuario inexistente";
    const BAD_PWD = "Contraseña incorrecta";

    /**
     *
     * @var SGTi\Entity\Usuario
     */
    protected $user;

    /**
     *
     * @var string 
     */
    protected $username = "";

    /**
     *
     * @var string
     */
    protected $password = "";

    public function __construct($username,$password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        $usuarioService = Application_Service_Usuario::getInstance();
        try {
            $this->user = $usuarioService->authenticate($this->username, $this->password);
            $result = $this->createResult(Zend_Auth_Result::SUCCESS);
            return $result;
        } catch (Exception $exc) {
            if ($exc->getMessage() == Application_Service_Usuario::No_Encontrado) {
                    return $this->createResult(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, array(self::NOT_FOUND_MSG));
                }
            if ($exc->getMessage() == Application_Service_Usuario::Pass_Incorrecta) {
                return $this->createResult(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, array(self::BAD_PWD));     
            }
        }
    }

    private function createResult($codigo, $mensajes = array()) {
        if (!is_array($mensajes)) {
            $mensajes = array($mensajes);
        }
        return new Zend_Auth_Result($codigo, $this->user, $mensajes);
    }

}

?>
