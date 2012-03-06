<?php

class Application_Service_Periodo extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Periodo();
        }

        return self::$instance;
    }

    public function savePeriodo($periodoData) {
        $periodo = new SGTi\Entity\Periodo($periodoData);

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($periodo);
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

    public function removePeriodo($periodo) {

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($periodo);
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

    public function updatePeriodo($periodoId, $periodoData) {

        $periodo = $this->entityManager->find('SGTi\Entity\Periodo', $periodoId);

        foreach ($periodoData as $field => $value) {
            $setterName = 'set' . ucfirst($field);

            $periodo->$setterName($value);
        }

        try {
            $this->entityManager->beginTransaction();

            $updatedPeriodo = $this->entityManager->merge($periodo);
            $this->entityManager->flush();

            $this->entityManager->commit();
            return $updatedPeriodo;
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }

        return true;
    }

    public function agregarMateriaPeriodo($periodoId, $materiaData) {
        $periodo = $this->entityManager->find('SGTi\Entity\Periodo', $periodoId);

        $materia = new SGTi\Entity\Materia($materiaData);

        // falla si hay materia con el mismo nombre
        if (!$periodo->agregarMateria($materia)) {
            // aca habria que tirar un lindo mensaje de error que explique lo que pasa
            return false;
        }

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($periodo);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }

        return $materia->getId();
    }

}
?>

