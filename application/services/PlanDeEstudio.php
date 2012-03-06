<?php

class Application_Service_PlanDeEstudio extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_PlanDeEstudio();
        }

        return self::$instance;
    }

    public function savePlanDeEstudio($planData) {
        $plan = new SGTi\Entity\PlanDeEstudio($planData);

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($plan);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }

        return $plan->getId();
    }

    public function removePlan($plan) {
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($plan);
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

    // $planData es un ARRAY de datos.
    public function updatePlan($planId, $planData) {
        // traigo el plan de la BD
        $plan = $this->entityManager->find('SGTi\Entity\PlanDeEstudio', $planId);

        // cargo los datos nuevos (en realidad cargo todos, no me fijo que hayan cambiado pero no jode en nada)
        foreach ($planData as $field => $value) {
            $setterName = 'set' . ucfirst($field);

            $plan->$setterName($value);
        }

        try {
            $this->entityManager->beginTransaction();

            $updatedPlan = $this->entityManager->merge($plan);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return $updatedPlan;
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();

            return null;
        }
    }

    public function agregarPeriodoPlan($planId, $periodoData) {
        // traigo el plan
        $plan = $this->entityManager->find('SGTi\Entity\PlanDeEstudio', $planId);
        
        // creo el periodo
        $periodo = new SGTi\Entity\Periodo($periodoData);

        // lo agrego a la coleccion de periodos del plan, evalua a FALSE si el pariodo ya esta agregado
        if (!$plan->agregarPeriodo($periodo)) {
            //aca habria que tirar un lindo mensaje de error que explique lo que pasa
            return false;
        }

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($plan);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }

        return $periodo->getId();
    }
    
    
    
     public function agregarRequisitoPlan($planId, $requisito) {
        // traigo el plan
        $plan = $this->entityManager->find('SGTi\Entity\PlanDeEstudio', $planId);

        // creo el periodo
        $requisito = new SGTi\Entity\RequisitoInscripcion($requisito);

        // lo agrego a la coleccion de periodos del plan
        $plan->agregarRequisitoInscripcion($requisito);

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($plan);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }

        return $requisito->getId();
    }

    public function inscribirAlumnoPlan($alumnos, $idPlan) {
        $plan = $this->entityManager->find('SGTi\Entity\PlanDeEstudio', $idPlan);
	
	//variable para guardar los alumnos que se pueden inscribir al curso
	$listaAlumnos = array();
	
        foreach ($alumnos as $id) {
            $alumnoSelected = $this->entityManager->find('SGTi\Entity\Alumno', $id);

            if ($alumnoSelected->getInscripcion() == null) {
                $inscripcion = new SGTi\Entity\Inscripcion();

                $inscripcion->setAlumno($alumnoSelected);
                $inscripcion->setPlanDeEstudio($plan);

                $alumnoSelected->setInscripcion($inscripcion);
		
		//creo el usuario, el nombre y password por defecto van a ser la cedula
		$usuario =  new SGTi\Entity\Usuario($alumnoSelected->getCi(), SGTi\Security\Roles::ALU);
		//le asigno el alumno
		$usuario->setPersona($alumnoSelected);
		//asigno el usuario al alumno
		$alumnoSelected->setUsuario($usuario);
		$listaAlumnos[] = $alumnoSelected; 
                
            } else{ return false;}
	    
	    try {
                    $this->entityManager->beginTransaction();
		    foreach ($listaAlumnos as $alumno){
			$this->entityManager->merge($alumno);
		    }                    
                    $this->entityManager->flush();
                    $this->entityManager->commit();
                } catch (Exception $e) {
                    $this->entityManager->rollback();
                    $this->entityManager->close();

                    echo $e->getMessage();
		    return false;
                }
        }
	
	return true;
    }

}

