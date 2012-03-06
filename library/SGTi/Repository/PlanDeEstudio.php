<?php
namespace SGTi\Repository;

class PlanDeEstudio extends AbstractRepository {

    public function findMateriasPlan($planId) {
	// lo tiro para afuera si no pasa el id
	if (!isset($planId)) {
	    return;
	}
	$queryBuilder = $this->_em->createQueryBuilder();

	$query = $queryBuilder->select('mat.id, mat.nombre')
		->from('SGTi\Entity\Materia', 'mat')
		->innerJoin('mat.periodo', 'per')
		->innerJoin('per.planDeEstudio', 'plan')
		->where('plan.id = ' . $planId)
		->getQuery();

	$materiasPlan = $query->getArrayResult();
	return $materiasPlan;
    }

    public function findPeriodosPlan($planId) {
	// lo tiro para afuera si no pasa el id
	if (!isset($planId)) {
	    return;
	}
	$queryBuilder = $this->_em->createQueryBuilder();

	$query = $queryBuilder->select('per.id, per.numero')
		->from('SGTi\Entity\Periodo', 'per')
		->innerJoin('per.planDeEstudio', 'plan')
		->where('plan.id = ' . $planId)
		->getQuery();

	$periodosPlan = $query->getArrayResult();
	return $periodosPlan;
    }
    
    public function findAlumnosPlan($planId) {
        if (!isset($planId)) {
            return;
        }
        $queryBuilder = $this->_em->createQueryBuilder();
        
        $query = $queryBuilder->select('a')
                ->from('SGTi\Entity\Alumno', 'a')
                ->innerJoin('a.inscripcion', 'ins')
                ->innerJoin('ins.planDeEstudio', 'plan')
                ->where('plan.id = ' .$planId)
                ->getQuery();
        
        $alumnos = $query->getResult();
        return $alumnos;
    }

    public function lastPlanAdded() {
	$query = $this->_em->createQuery('SELECT plan FROM SGTi\Entity\Plan plan ORDER BY plan.id DESC');
	$plan = $query->getResult();

	return $plan;
    }

}
