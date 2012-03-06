<?php
namespace SGTi\Repository;

class Alumno extends AbstractRepository {
    public function getAllMails() {
        //$query = $em->createQuery('SELECT d FROM SGTi\Entity\Docente d');
        $query = $this->_em->createQuery('SELECT a.mail FROM SGTi\Entity\Alumno a');
        $resultado=$query->getResult();
	
        $mails = array();
	
        foreach ($resultado as $mail) {
             $mails[]=$mail['mail'];
        }
	
        return $mails;
    }
    
    public function findInasistenciasCount($cursoId, $alumnoId) {        
        if (!isset($cursoId) || !isset ($alumnoId)) {
            return;
        }
	$queryBuilder = $this->_em->createQueryBuilder();

        $query = $queryBuilder->select($queryBuilder->expr()->count('asi.id'))
                ->from('SGTi\Entity\Asistencia', 'asi')
		->innerJoin('asi.alumno', 'alu')
		->innerJoin('asi.curso', 'cur')
                ->where($queryBuilder->expr()->andX(
			    $queryBuilder->expr()->eq('alu.id', $alumnoId),
			    $queryBuilder->expr()->eq('cur.id', $cursoId),
			    $queryBuilder->expr()->eq('asi.asistio', $queryBuilder->expr()->literal('No'))
			))
                ->getQuery();
        
        $asistencias = $query->getSingleScalarResult();
	
        return $asistencias;
    }
}
