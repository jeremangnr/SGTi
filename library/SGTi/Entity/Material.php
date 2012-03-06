<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="material")
 */
class Material{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="string", length=200) 
     * @var string
     */
    private $path;
    
     /** @Column(type="string", length=30) 
     * @var string
     */
    private $nombre;
    
      /** @Column(type="string", length=15) 
     * @var string
     */
    private $tipo;
    
    function __construct($path, $nombre, $tipo) {
        $this->path = $path;
        $this->nombre = $nombre;
         $this->tipo = $tipo;
    }
    
    public function getId() {
	return $this->id;
    }

    public function setId($id) {
	$this->id = $id;
    }

    public function getPath() {
	return $this->path;
    }

    public function setPath($path) {
	$this->path = $path;
    }
    
     public function getNombre() {
	return $this->nombre;
    }

    public function setNombre($nombre) {
	$this->path = $nombre;
    }
    
         public function getTipo() {
	return $this->tipo;
    }

    public function setTipo($tipo) {
	$this->tipo= $tipo;
    }

}    
