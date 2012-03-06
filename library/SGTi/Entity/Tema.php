<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="tema")
 * @HasLifecycleCallbacks
 */
class Tema {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="string", length=30) 
     * @var string
     */
    private $titulo;
    
    /** @Column(type="string", length=1000) 
     * @var string
     */
    private $contenido;
    
    /** @Column(type="datetime") 
     * @var DateTime
     */
    private $ultimaActualizacion;
    
    /**
    *  @OneToMany(targetEntity="Respuesta", mappedBy="tema", cascade={"persist"})
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private $respuestas;
    
    /**
    * @ManyToOne(targetEntity="Usuario")
    * @JoinColumn(name="usuario_id", referencedColumnName="id")
    * 
    *  @var SGTi\Entity\Usuario
    */
    private $usuario;
    
    /**
     * @ManyToOne(targetEntity="Categoria", inversedBy="temas")
     * @JoinColumn(name="categoria_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Categoria
     */
    private $categoria;
    
    function __construct($temaData = array()) {
        
    }
    
    public function getId() {
	return $this->id;
    }

    public function setId($id) {
	$this->id = $id;
    }

    public function getTitulo() {
	return $this->titulo;
    }

    public function setTitulo($titulo) {
	$this->titulo = $titulo;
    }

    public function getContenido() {
	return $this->contenido;
    }

    public function setContenido($contenido) {
	$this->contenido = $contenido;
    }

    public function getUltimaActualizacion() {
	return $this->ultimaActualizacion;
    }

    public function setUltimaActualizacion($ultimaActualizacion) {
	$this->ultimaActualizacion = $ultimaActualizacion;
    }
    
    public function getRespuestas() {
	return $this->respuestas;
    }

    public function setRespuestas($respuestas) {
	$this->respuestas = $respuestas;
    }
    
    public function getUsuario() {
	return $this->usuario;
    }
    
    public function setUsuario($usuario) {
	$this->usuario = $usuario;
    }
    
    public function getCategoria() {
	return $this->categoria;
    }

    public function setCategoria($categoria) {
	$this->categoria = $categoria;
    }
        
    public function agregarRespuesta($respuesta) {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas->add($respuesta);
        }
    }
    
    /**
     * @PrePersist
     * @PreUpdate
     */
    public function updated() {
        $this->ultimaActualizacion = new \DateTime("now");
    }

}