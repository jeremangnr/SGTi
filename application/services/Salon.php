<?php
class Application_Service_Salon extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Salon();
        }

        return self::$instance;
    }

    public function saveSalon($salonData) {
        $salon = new SGTi\Entity\Salon($salonData);
	
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($salon);
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
    
    public function removeSalon($salon) {
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($salon);
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
    
    public function updateSalon($salonId, $salonData) {
        
        $salon = $this->entityManager->find('SGTi\Entity\Salon', $salonId);
        
        foreach ($salonData as $field => $value) {
            $setterName = 'set' . ucfirst($field);
            
            $salon->$setterName($value);
        }
	
        try {
            $this->entityManager->beginTransaction();

            $updatedSalon = $this->entityManager->merge($salon);
            $this->entityManager->flush();

            $this->entityManager->commit();
	    return $updatedSalon;
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