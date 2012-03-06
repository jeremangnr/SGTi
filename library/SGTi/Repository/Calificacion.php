<?php
namespace SGTi\Repository;

class Calificacion extends AbstractRepository{
    public function findByAlumnoCurso($cursoId, $alumnoId){
	
	if (!isset($cursoId) || !isset ($alumnoId)) {
            return;
        }
	
	$queryBuilder = $this->_em->createQueryBuilder();

        $query = $queryBuilder->select('tip.nombre, eve.ultimaActualizacion, cal.notaObtenida, cal.observaciones')
                ->from('SGTi\Entity\Calificacion', 'cal')
                ->innerJoin('cal.eventoCalificacion', 'eve')
		->innerJoin('eve.tipo', 'tip')
		->innerJoin('cal.alumno', 'alu')
		->innerJoin('cal.curso', 'cur')
                ->where('alu.id = ' . $alumnoId)
                ->andWhere('cur.id = ' . $cursoId)
                ->getQuery();
        
        $alumnosCurso = $query->getArrayResult();

        return $alumnosCurso;	
    }
}