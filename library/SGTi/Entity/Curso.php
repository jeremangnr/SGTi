<?php

namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\Curso")
 * @Table(name="curso")
 */
class Curso {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /** @Column(type="integer", length=5) 
     * @var integer
     */
    private $anio;

    /**
    *  @OneToMany(targetEntity="EventoCalificacion", mappedBy="curso", cascade={"persist"})
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private $eventosCalificacion;

    /**
    *  @OneToMany(targetEntity="Clase", mappedBy="curso", cascade={"persist"})
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private $clases;
    
    /**
     * @ManyToMany(targetEntity="Horario", cascade={"persist"})
     * @JoinTable(name="curso_horarios",
     *      joinColumns={@JoinColumn(name="curso_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="horario_id", referencedColumnName="id", unique=true)}
     *      )
     *
     *  @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $horarios;
    
    /**
     * @ManyToOne(targetEntity="Materia", inversedBy="cursos")
     * @JoinColumn(name="materia_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Materia
     */
    private $materia;
    
    /**
     * @OneToMany(targetEntity="InscripcionCurso", mappedBy="curso", cascade={"persist"})
     * 
     *  @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $inscripcionesCurso;
    
    /**
    * @ManyToMany(targetEntity="Docente", mappedBy="cursos")  
    *  @var \Doctrine\Common\Collections\ArrayCollection
    */
    private $docentes;
    
    function __construct($cursoData = array()) {
        $this->eventosCalificacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clases = new \Doctrine\Common\Collections\ArrayCollection();
        $this->horarios = new \Doctrine\Common\Collections\ArrayCollection();
	$this->inscripcionesCurso = new \Doctrine\Common\Collections\ArrayCollection();
	$this->docentes = new \Doctrine\Common\Collections\ArrayCollection();
	$this->anio = $cursoData['anio'];
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getAnio() {
        return $this->anio;
    }

    public function setAnio($anio) {
        $this->anio = $anio;
    }

    public function getEventosCalificacion() {
        return $this->eventosCalificacion;
    }

    public function setEventosCalificacion($eventosCalificacion) {
        $this->eventosCalificacion = $eventosCalificacion;
    }

    public function getClases() {
        return $this->clases;
    }

    public function setClases($clases) {
        $this->clases = $clases;
    }
    
    public function getHorarios() {
	return $this->horarios;
    }

    public function setHorarios($horarios) {
	$this->horarios = $horarios;
    }
    
    public function getMateria() {
        return $this->materia;
    }

    public function setMateria($materia) {
        $this->materia = $materia;
    }
    
    public function getDocentes() {
	return $this->docentes;
    }

    public function setDocentes($docentes) {
	$this->docentes = $docentes;
    }
        
    public function agregarEventoCalificacion($eventoCalificacion) {
        if(!isset($this->eventosCalificacion)) {
            $this->eventosCalificacion = new \Doctrine\Common\Collections\ArrayCollection();
        }
	
	if (!$this->eventosCalificacion->contains($eventoCalificacion)) {
            $this->eventosCalificacion->add($eventoCalificacion);
        }
    }
    
    public function agregarClase($clase) {
        if (!$this->clases->contains($clase)) {
            $this->clases->add($clase);
        }
    }
    
    public function agregarDocente($docente) {
        if (!$this->docentes->contains($docente)) {
            $this->docentes->add($docente);
        }
    }
    
    public function getInscripcionesCurso() {
	return $this->inscripcionesCurso;
    }

    public function setInscripcionesCurso($inscripcionesCurso) {
	$this->inscripcionesCurso = $inscripcionesCurso;
    }

}
