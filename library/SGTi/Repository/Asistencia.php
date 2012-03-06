<?php
namespace SGTi\Repository;

class Asistencia extends AbstractRepository{
    
    public function findByAlumnoCurso($cursoId, $alumnoId){
	
	if (!isset($cursoId) || !isset ($alumnoId)) {
            return;
        }
	
	$queryBuilder = $this->_em->createQueryBuilder();

        $query = $queryBuilder->select('asi.asistio, cla.fecha')
                ->from('SGTi\Entity\Asistencia', 'asi')
                ->innerJoin('asi.clase', 'cla')
		->innerJoin('asi.alumno', 'alu')
		->innerJoin('asi.curso', 'cur')
                ->where('alu.id = ' . $alumnoId)
                ->andWhere('cur.id = ' . $cursoId)
                ->getQuery();
        
        $alumnoCurso = $query->getArrayResult();

        return $alumnoCurso;
    }
}