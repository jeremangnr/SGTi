<?php
class Application_Service_Noticia extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Noticia();
        }

        return self::$instance;
    }
    
    public function removeNoticia($noticia) {
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($noticia);
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
    
    public function updateNoticia($noticiaId, $noticiaData) {        
        $noticia = $this->entityManager->find('SGTi\Entity\Noticia', $noticiaId);
        
        // cargo los datos nuevos (en realidad cargo todos, no me fijo que hayan cambiado pero no jode en nada)
        foreach ($noticiaData as $field => $value) {
            $setterName = 'set' . ucfirst($field);
            
            $noticia->$setterName($value);
        }
        
        try {
            $this->entityManager->beginTransaction();

            $updatedNoticia = $this->entityManager->merge($noticia);
            $this->entityManager->flush();

            $this->entityManager->commit();
            return $updatedNoticia;
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