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
    
    /*
     * The extra attribute cursoId is used for a specific use case when we also need to check if the student
     * is already signed up for a given course.
     */
    public function findAlumnosPlan($planId, $cursoId = null) {
        if (!isset($planId)) {
            return;
        }
        // I built this query in particular using DQL instead of QueryBuilder because there is no conveniance method
        // for adding a WITH clause
        $query = $this->_em->createQuery("SELECT a.id, a.apellido, a.nombre
                                          FROM SGTi\Entity\Alumno as a
                                          INNER JOIN a.inscripcion ins
                                          INNER JOIN ins.planDeEstudio plan WITH plan.id = " . $planId . "
                                          LEFT JOIN ins. ");
        
        //echo $query->getSQL();
        $alumnos = $query->getArrayResult();
        return $alumnos;
    }

    public function lastPlanAdded() {
        // y esto? probar usando MAX(plan.id). esto te esta devolviendo TODOS los planes ordenados... al re pedo
        
        // esto no debe ni andar... la entided se llama PlanDeEstudio. ahreboludo
	$query = $this->_em->createQuery('SELECT plan FROM SGTi\Entity\Plan plan ORDER BY plan.id DESC');
	$plan = $query->getResult();

	return $plan;
    }

}
