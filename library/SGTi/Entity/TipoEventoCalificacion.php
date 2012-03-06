<?php

namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="tipo_evento_calificacion") 
 */
class TipoEventoCalificacion {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     * */
    private $id;

    /** @Column(type="string", length=30) 
     * @var string
     */
    private $nombre;
    
    /**
    *  @OneToMany(targetEntity="EventoCalificacion", mappedBy="tipo")
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private  $eventoscalificacion;
    
    function __construct($nombre) {
        $this->nombre = $nombre;         
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
    
    public function getEventoscalificacion() {
	return $this->eventoscalificacion;
    }

    public function setEventoscalificacion($eventoscalificacion) {
	$this->eventoscalificacion = $eventoscalificacion;
    }
    
    public function agregarEventoCalificacion($evento) {
        if(!isset($this->eventoscalificacion)) {
            $this->eventoscalificacion = new \Doctrine\Common\Collections\ArrayCollection();
        }
        
        if (!$this->eventoscalificacion->contains($evento)) {
            $this->eventoscalificacion->add($evento);
        }
    }
}