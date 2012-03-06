<?php
namespace SGTi\Repository;

class Periodo extends AbstractRepository {    
    public function findCursosPeriodo($planId, $periodoId) {
        // lo tiro para afuera si no pasa el id de cualquiera de los dos
        if (!isset($planId) || !isset($periodoId)) {
            return;
        }
        $queryBuilder = $this->_em->createQueryBuilder();

        $query = $queryBuilder->select('cur.id, cur.anio, mat.nombre AS mat_nombre')
                ->from('SGTi\Entity\Curso', 'cur')
                ->innerJoin('cur.materia', 'mat')
                ->innerJoin('mat.periodo', 'per')
                ->innerJoin('per.planDeEstudio', 'plan')
                ->where('plan.id = ' . $planId)
                ->andWhere('per.id = ' . $periodoId)
                ->getQuery();
        
        $cursosPlan = $query->getArrayResult();

        return $cursosPlan;
    }
}