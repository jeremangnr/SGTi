<?php

namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\PlanDeEstudio")
 * @Table(name="plan_de_estudio")
 */
class PlanDeEstudio {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     * */
    private $id;

    /** @Column(type="string", length=30, nullable=false, unique=true) 
     * @var string
     */
    private $nombre;

    /** @Column(type="integer", length=4, nullable=false) 
     * @var integer
     */
    private $anio;

    /** @Column(type="string", length=60) 
     * @var string
     */
    private $descripcion;

    /** @Column(type="integer", length=3) 
     * @var integer
     */
    private $notaExoneracion;

    /** @Column(type="integer", length=3) 
     * @var integer
     */
    private $notaAprobacion;

    /** @Column(type="integer", length=3) 
     * @var integer
     */
    private $notaMaxima;

    /**
     * @OneToMany(targetEntity="Periodo", mappedBy="planDeEstudio", cascade={"persist"})
     * 
     * @var \Doctrine\Common\Collections\ArrayCollection()
     */
    private $periodos;

    /**
     * @OneToMany(targetEntity="RequisitoInscripcion", mappedBy="planDeEstudio", cascade={"persist"})
     * 
     * @var \Doctrine\Common\Collections\ArrayCollection()
     */
    private $requisitosInscripcion;

    function __construct($planData = array()) {
        $this->periodos = new \Doctrine\Common\Collections\ArrayCollection();

        $this->nombre = $planData['nombre'];
        $this->anio = $planData['anio'];
        $this->descripcion = $planData['descripcion'];
        $this->notaAprobacion = $planData['notaAprobacion'];
        $this->notaExoneracion = $planData['notaExoneracion'];
        $this->notaMaxima = $planData['notaMaxima'];
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getAnio() {
        return $this->anio;
    }

    public function setAnio($anio) {
        $this->anio = $anio;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getPeriodos() {
        return $this->periodos;
    }

    public function setPeriodos($periodos) {
        $this->periodos = $periodos;
    }

    public function getNotaExoneracion() {
        return $this->notaExoneracion;
    }

    public function setNotaExoneracion($notaExoneracion) {
        $this->notaExoneracion = $notaExoneracion;
    }

    public function getNotaAprobacion() {
        return $this->notaAprobacion;
    }

    public function setNotaAprobacion($notaAprobacion) {
        $this->notaAprobacion = $notaAprobacion;
    }

    public function getNotaMaxima() {
        return $this->notaMaxima;
    }

    public function setNotaMaxima($notaMaxima) {
        $this->notaMaxima = $notaMaxima;
    }

    public function getRequisitosInscripcion() {
        return $this->requisitosInscripcion;
    }

    public function setRequisitosInscripcion($requisitoInscripcion) {
        $this->requisitosInscripcion = $requisitoInscripcion;
    }

    public function agregarPeriodo($periodo) {
        // setteo a true por defecto
        $addPeriodo = true;

        if (!$this->periodos->isEmpty()) {
            foreach ($this->periodos as $planPeriodo) {
                if ($periodo->getNumero() == $planPeriodo->getNumero()) {
                    // si encuentro uno igual no voy a agregar
                    $addPeriodo = false;
                    break;
                }
            }
        }
        // si no agrego devuelvo null
        if ($addPeriodo) {
            $this->periodos->add($periodo);
            $periodo->setPlanDeEstudio($this);
            
            return $this;
        } else {
            return;
        }
    }

    public function agregarRequisitoInscripcion($requisitoInscripcion) {
        if (!$this->requisitosInscripcion->contains($requisitoInscripcion)) {
            $this->requisitosInscripcion->add($requisitoInscripcion);
            $requisitoInscripcion->setPlanDeEstudio($this);
        }
    }

}