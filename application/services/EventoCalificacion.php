<?php
class Application_Service_EventoCalificacion extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_EventoCalificacion();
        }

        return self::$instance;
    }

    public function saveEventoCalificacion($evCalData) {
        
        $eventoCalificacion = new SGTi\Entity\EventoCalificacion($evCalData);
	
        try {            
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($eventoCalificacion);
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
    
    public function removeEventoCalificacion($eventoCal) {
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($eventoCal);
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
    
    public function updateEventoCalificacion($eventoCal) {
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($eventoCal);
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
    
    public function saveTipoCalificacion($nombre) {
        
        $tipoCalificacion = new SGTi\Entity\TipoEventoCalificacion($nombre);
	
        try {            
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($tipoCalificacion);
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
    
    public function removeTipoCalificacion($tipoCal) {
        
        $tipoCalificacion = $tipoCal;
	
        try {            
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($tipoCalificacion);
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