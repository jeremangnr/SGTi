<?php

class Application_Service_Usuario extends Application_Service_AbstractService {
    const No_Encontrado = 1;
    const Pass_Incorrecta = 2;

    private static $instance;

    private function __construct() {
	parent::__initResources();
    }

    public static function getInstance() {
	if (is_null(self::$instance)) {
	    self::$instance = new Application_Service_Usuario();
	}

	return self::$instance;
    }

    /**
     *
     * @param type string
     * @param type string
     * @throws Exception
     * @return SGTi\Entity\Usuario 
     */
    public function authenticate($username, $pasword) {
	//Busco al usuario pasandole su nombre
	try {
	    $user = $this->entityManager->getRepository('SGTi\Entity\Usuario')->findByNombre($username);
	} catch (Exception $e) {
	    $user = null;
	}

	//Si el usuario exite
	if ($user != null) {
	    //Y ese usuario tiene la mimsa contraseña que me pasaron por parametro
	    if ($user->getPassword() == $pasword) {
		// lo retorno
		return $user;
	    } else {
		throw new Exception(self::Pass_Incorrecta);
	    }
	} else {
	    throw new Exception(self::No_Encontrado);
	}
    }

    public function createRoot() {
	//Busco si existe el usuario root
	try {
	    $user = $this->entityManager->getRepository('SGTi\Entity\Usuario')->findByNombre('root');
	} catch (Exception $e) {
	    $user = null;
	}
        
	if ($user == null) {
	    $administrativoData['ci'] = 'root';
	    $administrativoData['nombre'] = 'Administrador';
	    $administrativoData['apellido'] = '';
	    $administrativoData['fechaNac'] = date('d-m-Y');
	    $administrativoData['telefono'] = '';
	    $administrativoData['celular'] = '';
	    $administrativoData['localidad'] = 'localhost';
	    $administrativoData['mail'] = 'root@sgti.com';

	    $administrativo = new \SGTi\Entity\Administrativo($administrativoData);

	    //creo el usuario, el nombre y password por defecto van a ser la cedula
	    $usuario = new SGTi\Entity\Usuario($administrativo->getCi(), SGTi\Security\Roles::ADMIN);
	    //le asigno el administrativo
	    $usuario->setPersona($administrativo);
	    //asigno el usuario al administrativo
	    $administrativo->setUsuario($usuario);
	    try {
		$this->entityManager->beginTransaction();

		$this->entityManager->persist($administrativo);
		$this->entityManager->flush();

		$this->entityManager->commit();
	    } catch (Exception $e) {
		$this->entityManager->rollback();
		$this->entityManager->close();

		echo $e->getMessage();
		return false;
	    }
	    return true;
	}
    }
    
    public function agregarNoticia($noticiaData) {
	$noticia = new SGTi\Entity\Noticia($noticiaData);
	$usuario = Zend_Auth::getInstance()->getIdentity();
	//lo tengo que trer de vuelta porque si lo traigo con la funcion de arriba
	// me da problemas al hacer merge y me borra el rol
	$user = $this->entityManager->find('SGTi\Entity\Usuario', $usuario->getId());
	$user->agregarNoticia($noticia);

	try {
	    $this->entityManager->beginTransaction();	    
	    $this->entityManager->merge($user);
	    $this->entityManager->flush();

	    $this->entityManager->commit();
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}

	return true;
    }
}