<?php

namespace SGTi\Repository;

class Curso extends AbstractRepository {
    public function findCursoActual($materiaId) {
        // lo tiro para afuera si no pasa el id
        if (!isset($materiaId)) {
            return;
        }
        $queryBuilder = $this->_em->createQueryBuilder();

        // uso la funcion date() para traer el anio actual (segun la timezone del servidor, OJO)
        $query = $queryBuilder->select('c')
                ->from('SGTi\Entity\Curso', 'c')
                ->innerJoin('c.materia', 'mat')
                ->where('mat.id = ' . $materiaId)
                ->andWhere('c.anio = ' . date('Y'))
                ->getQuery();

        //getResult() en vez de getSingleResult() porque puede NO haber resultados
        return $query->getSingleResult();
    }

    /*
     * Devuelve los alumnos de un curso
     * @param $cursoId El id del curso
     * @param $examen Booleano que especifica si debe traer SOLO los alumnos que estan cursando (false por defecto), o tambien los que estan a examen
     */

    public function findAlumnos($cursoId, $examen = false) {
        if (!isset($cursoId)) {
            return;
        }
        $queryBuilder = $this->_em->createQueryBuilder();
        $query = null;

        //si quiero los inscriptos Y los que van a examen
        if ($examen) {
            $query = $queryBuilder->select('a.id, a.nombre, a.apellido, a.ci')
                    ->from('SGTi\Entity\Alumno', 'a')
                    ->innerJoin('a.inscripcion', 'i')
                    ->innerJoin('i.inscripcionesCurso', 'ic')
                    ->innerJoin('ic.curso', 'c')
                    ->where('c.id = ' . $cursoId)
                    ->andWhere($queryBuilder->expr()->orx(
                                    $queryBuilder->expr()->eq('ic.estado', ':estadoCursando'),
                                    $queryBuilder->expr()->eq('ic.estado', ':estadoExamen')
                            ))
                    ->setParameter('estadoCursando', \SGTi\Entity\InscripcionCurso::CURSANDO)
                    ->setParameter('estadoExamen', \SGTi\Entity\InscripcionCurso::EXAMEN)
                    ->getQuery();
            //si quiero todos (los que estan cursando)
        } else {
            $query = $queryBuilder->select('a.id, a.nombre, a.apellido, a.ci')
                    ->from('SGTi\Entity\Alumno', 'a')
                    ->innerJoin('a.inscripcion', 'i')
                    ->innerJoin('i.inscripcionesCurso', 'ic')
                    ->innerJoin('ic.curso', 'c')
                    ->where('c.id = :cursoId')
                    ->andWhere('ic.estado = :estado')
                    ->setParameter('cursoId', $cursoId)
                    ->setParameter('estado', \SGTi\Entity\InscripcionCurso::CURSANDO)
                    ->getQuery();
        }
        
        //echo $query->getSQL();
        return $query->getResult();
    }

}