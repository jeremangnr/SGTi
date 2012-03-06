<?php

namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\Alumno")
 * @Table(name="alumno")
 * */
class Alumno extends Persona {

    /** @OneToOne(targetEntity="Inscripcion", cascade={"persist"})
     *
     *  @var SGTi\Entity\Inscripcion
     */
    private $inscripcion;

    /**
     *  @OneToMany(targetEntity="Asistencia", mappedBy="alumno", cascade={"persist"})
     *  @var \Doctrine\Common\Collections\ArrayCollection()
     */
    private $asistencias;

    /** @Column(type="string", length=2)
     *
     * @var string
     */
    private $req;

    function __construct($alumnoData = array(), $requisitos=null) {
        parent::__construct($alumnoData);

        $this->req = ($requisitos == "si") ? "si" : "no";
        /*
          if ($requisitos == "si") {
          $this->req="si";
          } else {
          $this->req="no";
          }
         */

        $this->asistencias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getInscripcion() {
        return $this->inscripcion;
    }

    public function setInscripcion($inscripcion) {
        $this->inscripcion = $inscripcion;
    }

    public function getAsistencias() {
        return $this->asistencias;
    }

    public function setAsistencias() {
        $this->asistencias = $asistencias;
    }

    public function getReq() {
        return $this->req;
    }

    public function setReq($requisitos) {
        $this->req = $requisitos;
    }

    public function agregarAsistencia($asistencia) {
        // setteo a true por defecto
        $addAsistencia = true;

        if (!$this->asistencias->isEmpty()) {
            foreach ($this->asistencias as $aluAsistencia) {
                // esto obviamente no va a andar, porque la asistencia no tiene FECHA. una cosa que no tiene goyete
                if ($asistencia->getFecha() == $aluAsistencia->getFecha()) {
                    // si encuentro una igual no voy a agregar
                    $addAsistencia = false;
                    break;
                }
            }
        }
        // si no agrego devuelvo vacio (null)
        if ($addAsistencia) {
            $this->asistencias->add($asistencia);
            $asistencia->setAlumno($this);

            return $this;
        } else {
            return;
        }
    }

    public function puedeInscribirEnCurso($curso) {
        if ($this->inscripcion == null) {
            return false;
        }

        // primero chequear que el alumno este inscripto a el plan en que esta el curso en que lo queremos inscribir
        $aluPlan = $this->getInscripcion()->getPlanDeEstudio();
        $cursoPlan = $curso->getMateria()->getPeriodo()->getPlanDeEstudio();

        $puedeInscribir = ($aluPlan == $cursoPlan) ? true : false;
        
        // despues vamos a verificar que ya no este inscripto a ese curso
        if ($puedeInscribir) {
            $inscripcionesCurso = $this->getInscripcion()->getInscripcionesCurso();
            foreach ($inscripcionesCurso as $inscripcion) {
		    // si ya esta inscripto al curso
		    if ($inscripcion->getCurso() == $curso) {
			//si el alumno ya esta inscripto al curso retorno false
			return false;
		    }
		}
        }

        return $puedeInscribir;
    }
}