<?php
namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\Calificacion")
 * @Table(name="calificacion")
 */
class Calificacion {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="integer", length=3) 
     * @var integer
     */
    private $notaObtenida;
    
    /** @Column(type="string", length=160)
     * @var string
     */
    private $observaciones;
    
    /**
     * @ManyToOne(targetEntity="Alumno")
     * @JoinColumn(name="alumno_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Alumno
     */
    private $alumno;
    
    /**
     * @ManyToOne(targetEntity="Curso")
     * @JoinColumn(name="curso_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Curso
     */
    private $curso;
    
    /**
     * @ManyToOne(targetEntity="EventoCalificacion", inversedBy="calificaciones")
     * @JoinColumn(name="evento_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Curso
     */
    private $eventoCalificacion;
    
    
    function __construct($nota, $observacion) {
        $this->notaObtenida = $nota;
	$this->observaciones = $observacion;
    }
    
    public function getId() {
	return $this->id;
    }

    public function setId($id) {
	$this->id = $id;
    }

    public function getNotaObtenida() {
	return $this->notaObtenida;
    }

    public function setNotaObtenida($notaObtenida) {
	$this->notaObtenida = $notaObtenida;
    }

    public function getObservaciones() {
	return $this->observaciones;
    }

    public function setObservaciones($observaciones) {
	$this->observaciones = $observaciones;
    }
    
    public function getAlumno() {
	return $this->alumno;
    }

    public function setAlumno($alumno) {
	$this->alumno = $alumno;
    }

    public function getCurso() {
	return $this->curso;
    }

    public function setCurso($curso) {
	$this->curso = $curso;
    }

    public function getEventoCalificacion() {
	return $this->eventoCalificacion;
    }

    public function setEventoCalificacion($eventoCalificacion) {
	$this->eventoCalificacion = $eventoCalificacion;
    }

}