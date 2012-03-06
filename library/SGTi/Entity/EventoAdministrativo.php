<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="evento_administrativo")
 * @HasLifecycleCallbacks
 */
class EventoAdministrativo{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="string", length=30)
     * @var string
     */
    private $tipo;
    
    /** @Column(type="string", length=100) 
     * @var string
     */
    private $descripcion;
    
    /** @Column(type="datetime", nullable=true)
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
    
    function __construct($eventoAdministrativoData = array()) {
        
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

    public function getUltimaActualizacion() {
	return $this->ultimaActualizacion;
    }
    
    // ojo con esto, es fecha y hora, hay que ver como persistir esto
    public function setUltimaActualizacion($ultimaActualizacion) {
	$this->ultimaActualizacion = $ultimaActualizacion;
    }
    
    public function getUsuario() {
	return $this->usuario;
    }

    public function setUsuario($usuario) {
	$this->usuario = $usuario;
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
        $this->ultimaActualizacion = new \DateTime("now");
    }
    
}