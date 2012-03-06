<?php
class Application_Service_Rol extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Rol();
        }

        return self::$instance;
    }

    public function saveRol($rolData) {
        
        $rol = new SGTi\Entity\Rol($rolData);
        
        try {            
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($rol);
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
    
    public function removeRol($rol) {
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($rol);
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
    
    public function updateRol($rol) {
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($rol);
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

?>