<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="salon")
 */
class Salon {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="string", length=30) 
     * @var string
     */
    private $nombre;
    
    /** @Column(type="integer", length=4) 
     * @var integer
     */
    private $capacidad;
    
    function __construct($salonData = array()) {
       $this->nombre=$salonData['nombre'];
       $this->capacidad=$salonData['capacidad'];       
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

    public function getCapacidad() {
	return $this->capacidad;
    }

    public function setCapacidad($capacidad) {
	$this->capacidad = $capacidad;
    }


}