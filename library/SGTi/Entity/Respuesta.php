<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="respuesta")
 * @HasLifecycleCallbacks
 */
class Respuesta {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="string", length=1000)	
     * @var string
     */
    private $contenido;
    
    /** @Column(type="datetime") 
     * @var DateTime
     */
    private $ultimaActualizacion;
    
    /**
    * @ManyToOne(targetEntity="Usuario")
    * @JoinColumn(name="usuario_id", referencedColumnName="id")
    * 
    *  @var SGTi\Entity\Usuario
    */
    private $usuario;
    
    /**
     * @ManyToOne(targetEntity="Tema", inversedBy="respuestas")
     * @JoinColumn(name="tema_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Tema
     */
    private $tema;
    
    function __construct($respuestaData = array()) {
        
    }
    
    public function getId() {
	return $this->id;
    }

    public function setId($id) {
	$this->id = $id;
    }

    public function getContenido() {
	return $this->contenido;
    }

    public function setContenido($contenido) {
	$this->contenido = $contenido;
    }

    public function getultimaActualizacion() {
	return $this->ultimaActualizacion;
    }

    public function setultimaActualizacion($ultimaActualizacion) {
	$this->ultimaActualizacion = $ultimaActualizacion;
    }
    
    public function getUsuario() {
	return $this->usuario;
    }

    public function setUsuario($usuario) {
	$this->usuario = $usuario;
    }
    
    public function getTema() {
	return $this->tema;
    }

    public function setTema($tema) {
	$this->tema = $tema;
    }
        
    /**
     * @PreUpdate
     * @PrePersist 
     */
    public function updated() {
        $this->ultimaActualizacion = new \DateTime("now");
    }

}
