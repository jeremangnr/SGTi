<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="clase")
 */
class Clase {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="date")
     * @var Date
     */
    private $fecha;
    

    /**
     * @ManyToOne(targetEntity="Curso", inversedBy="clases")
     * @JoinColumn(name="curso_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Periodo
     */
    private $curso;
    
    /**
    *  @OneToMany(targetEntity="Asistencia", mappedBy="clase", cascade={"persist"})
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private $asistencias;

    function __construct($curso, $fecha) {
	$this->setFecha($fecha);
	$this->curso = $curso;
        $this->asistencias = new \Doctrine\Common\Collections\ArrayCollection();
        
        //PARA SETTEAR LA FECHA ACUERDENSE DE LLAMAR AL SET, NO LA METAN DERECHO EN EL ATRIBUTO
    }
    
    public function getId() {
	return $this->id;
    }

    public function setId($id) {
	$this->id = $id;
    }

    public function getFecha() {
	return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = \DateTime::createFromFormat('d-m-Y', $fecha);
    }
    
    public function getCurso() {
	return $this->curso;
    }

    public function setCurso($curso) {
	$this->curso = $curso;
    }

    public function getAsistencias() {
	return $this->asistencias;
    }

    public function setAsistencias($asistencias) {
	$this->asistencias = $asistencias;
    }
    
    public function agregarAsistencia($asistencia) {
        if(!isset($this->asistencias)) {
            $this->asistencias = new \Doctrine\Common\Collections\ArrayCollection();
        }
	if (!$this->asistencias->contains($asistencia)) {
            $this->asistencias->add($asistencia);
        }
    }

}