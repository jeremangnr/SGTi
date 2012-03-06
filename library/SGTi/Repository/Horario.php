<?php

namespace SGTi\Repository;

class Horario extends AbstractRepository {

    public function findHorarioActual($cursoId, $hIni, $hFin, $dia, $salonId) {
        // lo tiro para afuera si no pasa el id

        $queryBuilder = $this->_em->createQueryBuilder();

        // uso la funcion date() para traer el anio actual (segun la timezone del servidor, OJO)
        $query = $queryBuilder->select('h')
                ->from('SGTi\Entity\Horario', 'h')
                ->innerJoin('h.curso', 'cur')
                ->innerJoin('h.salon', 'sal')
                ->where('sal.id = ' . $salonId)
                ->andWhere('cur.id = ' . $cursoId)
                ->andWhere('h.horaInicio = ' . $hIni)
                ->andWhere('h.horaInicio = ' . $hFin)
                ->getQuery();

        //getResult() en vez de getSingleResult() porque puede NO haber resultados
        return $query->getSingleResult();
    }

}