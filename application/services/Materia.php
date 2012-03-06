<?php
class Application_Service_Materia extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Materia();
        }

        return self::$instance;
    }

    public function saveMateria($materiaData) {
        $materia = new SGTi\Entity\Materia($materiaData);
	
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($materia);
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
    
    public function removeMateria($materia) {
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($materia);
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
    
    public function updateMateria($materiaId, $materiaData) {
        $materia = $this->entityManager->find('SGTi\Entity\Materia', $materiaId);
                
        foreach ($materiaData as $field => $value) {
            $setterName = 'set' . ucfirst($field);            
            $materia->$setterName($value);
        }
        
        try {
            $this->entityManager->beginTransaction();

            $updatedMateria = $this->entityManager->merge($materia);
            $this->entityManager->flush();

            $this->entityManager->commit();
	    return $updatedMateria;
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();
            
            echo $e->getMessage();
            return false;
        }
        
        return true;
    }
    
    
    public function removeMaterialMateria($materiaId, $materiaData) {
        $materia = $this->entityManager->find('SGTi\Entity\Materia', $materiaId);
                
        foreach ($materiaData as $field => $value) {
            $setterName = 'set' . ucfirst($field);
            
            $materia->$setterName($value);
        }
        try {
            $this->entityManager->beginTransaction();

            $updatedMateria = $this->entityManager->merge($materia);
            $this->entityManager->flush();

            $this->entityManager->commit();
	    return $updatedMateria;
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();
            
            echo $e->getMessage();
            return false;
        }
        
        return true;
    }
    
    
    public function agregarCursoMateria($cursoData, $materia) {
	$curso = new SGTi\Entity\Curso($cursoData);
	
        if (!$materia->agregarCurso($curso)) {
            // tirar algo de error antes
            return false;
        }
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($materia);
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
    
    public function agregarPreviaturaMateria($materia, $materias) {	
	foreach ($materias as $id){
	    $materiaSelected = $this->entityManager->find('SGTi\Entity\Materia', $id);
	    $materia->agregarPreviatura($materiaSelected);
	}
	        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($materia);
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
    
    
     public function agregarMaterialMateria($path, $materia, $nombre, $tipo) {	
	
	
	$material = new SGTi\Entity\Material($path, $nombre, $tipo);
	
        $materia->agregarMaterial($material);
        
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($materia);
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