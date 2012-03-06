<?php
namespace SGTi\Repository;

class Noticia extends AbstractRepository{
    
    public function findByDate() {	
	$queryBuilder = $this->_em->createQueryBuilder();

        $query = $queryBuilder->select('noticia')
                ->from('SGTi\Entity\Noticia', 'noticia')
                ->orderBy('noticia.ultimaActualizacion','DESC')
                ->getQuery();
        
        $noticias = $query->getResult();

        return $noticias;
    }
}