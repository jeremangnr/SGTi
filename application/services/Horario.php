<?php
class Application_Service_Horario extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Horario();
        }

        return self::$instance;
    }

    public function saveHorario($horarioData) {
        
        $horario = new SGTi\Entity\Horario($horarioData);
	
        try {            
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($horario);
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
    
    public function removeHorario($horario) {
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($horario);
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
    
    public function updateHorario($idHorario, $idSalon, $horarioData) {
        
	$salon = $this->entityManager->find('SGTi\Entity\Salon', $idSalon);
	$horario = $this->entityManager->find('SGTi\Entity\Horario', $idHorario);
        
        foreach ($horarioData as $field => $value) {
            $setterName = 'set' . ucfirst($field);
            
            $horario->$setterName($value);
        }
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($horario);
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
