<?php

namespace SGTi\Repository;

class Materia extends AbstractRepository {
    /*
     * Devuelve un array con las materias que se pueden elegir como previas para una materia dada
     * (del mismo plan, que ya no sean previas (incluyendo que no sea la misma materia) y que sean de periodos anteriores)
     * 
     * @param $idMateria La id de la materia de la que queremos obtener las previas disponibles
     * @return Array con las previas disponibles
     */
    public function findPreviaturasDisponibles($idMateria, $nroPeriodo) {
        if (!isset($idMateria) || !isset($nroPeriodo)) {
            return;
        }
        
        $queryBuilder = $this->_em->createQueryBuilder();
        
        $query = $queryBuilder->select('mat.id, mat.nombre, per.numero, mat.creditos')
        ->from('SGTi\Entity\Materia', 'mat')
        ->innerJoin('mat.periodo', 'per')
        ->innerJoin('per.planDeEstudio', 'plan')
        ->where($queryBuilder->expr()->andx(
                    $queryBuilder->expr()->neq('mat.id', $idMateria),
                    $queryBuilder->expr()->lt('per.numero', $nroPeriodo),
                    $queryBuilder->expr()->notIn('mat.id', 'SELECT prev.id FROM SGTI\Entity\Materia prev INNER JOIN prev.materiaPadre matPadre WHERE matPadre.id = ' . $idMateria . '')
                ))
        //->setParameter('idMateria', $idMateria)
        //->setParameter('nroPeriodo', $nroPeriodo)
        ->getQuery();

        //echo $query->getSQL();
        //getResult() en vez de getSingleResult() porque puede NO haber resultados
        return $query->getResult();
    }

}

