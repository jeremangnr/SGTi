<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="inscripcion")
 * @HasLifecycleCallbacks
 */
class Inscripcion {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @OneToOne(targetEntity="Alumno")
     *  @var SGTi\Entity\Alumno
     */
    private $alumno;
    
   /**
    * @ManyToMany(targetEntity="InscripcionCurso", cascade={"persist"})
    * @JoinTable(name="inscripcion_inscripciones_curso",
    *      joinColumns={@JoinColumn(name="inscripcion_id", referencedColumnName="id")},
    *      inverseJoinColumns={@JoinColumn(name="inscripciones_curso_id", referencedColumnName="id", unique=true)}
    *      )
    * 
    *  @var \Doctrine\Common\Collections\ArrayCollection
    **/
    /**
     * @OneToMany(targetEntity="InscripcionCurso", mappedBy="inscripcion", cascade={"persist"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $inscripcionesCurso;
    
    
     /** @Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    private $fechaInscripcion;
    
    /**
     * @ManyToOne(targetEntity="PlanDeEstudio")
     * @JoinColumn(name="plan_de_estudio_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\PlanDeEstudio
     */
    private $planDeEstudio;

    function __construct($inscripcionData = array()) {
        $this->alumno = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inscripcionesCurso = new \Doctrine\Common\Collections\ArrayCollection();
        $this->planDeEstudio = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getId() {
	return $this->id;
    }

    public function setId($id) {
	$this->id = $id;
    }

    public function getAlumno() {
	return $this->alumno;
    }

    public function setAlumno($alumno) {
	$this->alumno = $alumno;
    }
    
    public function getInscripcionesCurso() {
	return $this->inscripcionesCurso;
    }

    public function setInscripcionesCurso($inscripcionesCurso) {
	$this->inscripcionesCurso = $inscripcionesCurso;
    }
    
    public function getPlanDeEstudio() {
	return $this->planDeEstudio;
    }

    public function setPlanDeEstudio($planDeEstudio) {
	$this->planDeEstudio = $planDeEstudio;
    }
    
    public function agregarInscripcionCurso($inscripcionCurso) {
        if (!$this->inscripcionesCurso->contains($inscripcionCurso)) {
            $this->inscripcionesCurso->add($inscripcionCurso);
        }
    }
    
    public function getFechaInscripcion() {
        return $this->fechaInscripcion;
    }

    public function setFechaInscripcion($fechaInscripcion) {
        $this->fechaInscripcion = $fechaInscripcion;
    }
    
    /**
     * 
     * lo que hace esta pachanga de aca es, CADA VEZ que se persista o se actualize esta entidad, SOLO, SOLITO
     * SI SOLITO, Doctrine actualiza la fecha a la fecha actual.
     * 
     * @PrePersist
     * @PreUpdate
     */
    public function updated() {
        $this->fechaInscripcion = new \DateTime("now");
    }
}