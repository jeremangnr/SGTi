<?php
namespace SGTi\Repository;

class Docente extends AbstractRepository {
    public function hasCurso($cursoId) {
	// lo tiro para afuera si no pasa el id
        if (!isset($cursoId)) {
            return;
        }
	$query = $em->createQuery('SELECT COUNT(d.id) FROM SGTi\Entity\Docente d INNER JOIN d.cursos c WHERE c.id = :cursoId');
	$query->setParameter('cursoId', $cursoId);
	
	$count = $query->getSingleScalarResult();
	
	return $count;
	
	if ($count) {
	    return true;
	} else {
	    return false;
	}
    }
    
    public function getAllMails() {
        //$query = $em->createQuery('SELECT d FROM SGTi\Entity\Docente d');
        $query = $this->_em->createQuery('SELECT d.mail FROM SGTi\Entity\Docente d');
        $resultado=$query->getResult();
        $mails = array();
        foreach ($resultado as $mail) {
             $mails[]=$mail['mail'];
        }
        return $mails;
    }
}