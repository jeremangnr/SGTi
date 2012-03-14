<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="inscripcion_curso")
 */
class InscripcionCurso {

    const CURSANDO = 0;
    const APROBADO = 1;    
    const EXAMEN = 2;    
    const RECURSA = 3;
    
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="integer", length=1) 
     * @var integer
     */    
    private $estado;
    
    /** @Column(type="integer", length=3, nullable = true) 
     * @var integer
     */
    private $notaObtenida;
    
    /**
    * @ManyToMany(targetEntity="EventoAdministrativo")
    * @JoinTable(name="inscripcion_curso_evento_administrativos",
    *      joinColumns={@JoinColumn(name="inscripcion_curso_id", referencedColumnName="id")},
    *      inverseJoinColumns={@JoinColumn(name="evento_administrativo_id", referencedColumnName="id", unique=true)}
    *      )
    * 
    *  @var \Doctrine\Common\Collections\ArrayCollection
    */
    private $eventosAdministrativos;
    
    /**
     * @ManyToOne(targetEntity="Curso", inversedBy = "inscripcionesCurso")
     * @JoinColumn(name="curso_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Curso
     */
    private $curso;
    
    /**
     * @ManyToOne(targetEntity="Inscripcion", inversedBy="inscripcionesCurso")
     * @JoinColumn(name="inscripcion_curso_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\InscripcionCurso
     */
    private $inscripcion;
    
    /**
    * @ManyToMany(targetEntity="Calificacion", cascade={"persist"})
    * @JoinTable(name="inscripcion_curso_calificaciones",
    *      joinColumns={@JoinColumn(name="inscripcion_curso_id", referencedColumnName="id")},
    *      inverseJoinColumns={@JoinColumn(name="calificacion_id", referencedColumnName="id", unique=true)}
    *      )
    * 
    *  @var \Doctrine\Common\Collections\ArrayCollection
    */
    private $calificaciones;
    
    function __construct() {
        $this->calificaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventosAdministrativos = new \Doctrine\Common\Collections\ArrayCollection();
	$this->estado = \SGTi\Entity\InscripcionCurso::CURSANDO;
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
    
    public function getEstado() {
	return $this->estado;
    }

    public function setEstado($estado) {
	$this->estado = $estado;
    }

    public function getEventosAdministrativos() {
	return $this->eventosAdministrativos;
    }

    public function setEventosAdministrativos($eventosAdministrativos) {
	$this->eventosAdministrativos = $eventosAdministrativos;
    }
    
    public function getCurso() {
	return $this->curso;
    }

    public function setCurso($curso) {
	$this->curso = $curso;
    }
    
    public function getInscripcion() {
        return $this->inscripcion;
    }

    public function setInscripcion($inscripcion) {
        $this->inscripcion = $inscripcion;
    }
        
    public function getCalificaciones() {
	return $this->calificaciones;
    }

    public function setCalificaciones($calificaciones) {
	$this->calificaciones = $calificaciones;
    }
    
    public function agregarEventoAdministrativo($eventoAdministrativo) {
        if (!$this->eventosAdministrativos->contains($eventoAdministrativo)) {
            $this->eventosAdministrativos->add($eventoAdministrativo);
        }
    }
    
    public function agregarCalificacion($calificacion) {
        if (!$this->calificaciones->contains($calificacion)) {
            $this->calificaciones->add($calificacion);
        }
    }
}
