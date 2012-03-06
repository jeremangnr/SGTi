<?php
namespace SGTi\Repository; 

class Usuario extends AbstractRepository {    
    
    public function findByNombre($nombreUsuario) {
	// lo tiro para afuera si no pasa el id
        if (!isset($nombreUsuario)) {
            return;
        }
	$queryBuilder = $this->_em->createQueryBuilder();
	
        $query = $queryBuilder->select('u')
                ->from('SGTi\Entity\Usuario', 'u')
                ->where('u.nombre = :userName')
                ->setParameter('userName', $nombreUsuario)
                ->getQuery();
        
        return $query->getSingleResult();
    }
}