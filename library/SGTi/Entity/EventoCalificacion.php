<?php

namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="evento_calificacion")
 * @HasLifecycleCallbacks
 */
class EventoCalificacion {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     * */
    private $id;

    /**
     * @ManyToOne(targetEntity="TipoEventoCalificacion", inversedBy="eventoscalificacion")
     * @JoinColumn(name="tipo_evento_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\TipoEventoCalificacion
     */
    private $tipo;

    /** @Column(type="string", length=100, nullable=true) 
     * @var string
     */
    private $descripcion;

    /** @Column(type="integer", length=3) 
     * @var integer
     */
    private $notaMax;

    /** @Column(type="integer", length=3) 
     * @var integer
     */
    private $notaAprobacion;

    /** @Column(type="datetime", nullable=true) 
     * @var DateTime
     */
    private $ultimaActualizacion;

    /**
    *  @OneToMany(targetEntity="Calificacion", mappedBy="eventoCalificacion", cascade={"persist"})
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private $calificaciones;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="usuario_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Usuario
     */
    private $usuario;
    
    /**
     * @ManyToOne(targetEntity="Curso", inversedBy="eventosCalificacion")
     * @JoinColumn(name="curso_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Curso
     */
    private $curso;

    function __construct($evCalData = array()) {
        $this->calificaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->curso = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipo = $evCalData['nombre'];
        $this->descripcion = $evCalData['descripcion'];
        $this->notaMax = $evCalData['notaMax'];
        $this->notaAprobacion = $evCalData['notaAprobacion'];
	$this->ultimaActualizacion = new \DateTime("now");
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getNotaMax() {
        return $this->notaMax;
    }

    public function setNotaMax($notaMax) {
        $this->notaMax = $notaMax;
    }

    public function getNotaAprobacion() {
        return $this->notaAprobacion;
    }

    public function setNotaAprobacion($notaAprobacion) {
        $this->notaAprobacion = $notaAprobacion;
    }

    public function getUltimaActualizacion() {
        return $this->ultimaActualizacion;
    }

    public function setUltimaActualizacion($ultimaActualizacion) {
        $this->ultimaActualizacion = $ultimaActualizacion;
    }

    public function getCalificaciones() {
        return $this->calificaciones;
    }

    public function setCalificaciones($calificaciones) {
        $this->calificaciones = $calificaciones;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
    
    public function getCurso() {
	return $this->curso;
    }

    public function setCurso($curso) {
	$this->curso = $curso;
    }

    public function agregarCalificacion($calificacion) {
        if(!isset($this->calificaciones)) {
            $this->calificaciones = new \Doctrine\Common\Collections\ArrayCollection();
        }
	if (!$this->calificaciones->contains($calificacion)) {
            $this->calificaciones->add($calificacion);
        }
    }
    
    /**
     * 
     * lo que hace esta pachanga de aca es, CADA VEZ que se persista o se actualize esta entidad, SOLO, SOLITO
     * SI SOLITO, Doctrine actualiza la fecha a la fecha actual.
     * 
     * @PrePersist
     * @PreUpdate
     */
    private function updated() {
        $this->ultimaActualizacion= new \DateTime("now");
    }

}