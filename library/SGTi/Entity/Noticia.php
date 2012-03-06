<?php

namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\Noticia")
 * @Table(name="noticia")
 * @HasLifecycleCallbacks
 */
class Noticia {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     * */
    private $id;

    /** @Column(type="string", length=30) 
     * @var string
     */
    private $titulo;

    /** @Column(type="string", length=1000) 
     * @var string
     */
    private $contenido;

    /** @Column(type="datetime", nullable=true) 
     * @var DateTime
     */
    private $ultimaActualizacion;

    /**
     * @ManyToOne(targetEntity="Usuario", inversedBy="noticias")
     * @JoinColumn(name="usuario_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Usuario
     */
    private $usuario;

    function __construct($noticiaData = array()) {
        $this->titulo = $noticiaData['titulo'];
        $this->contenido = $noticiaData['contenido'];
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
    public function updated() {
        $this->ultimaActualizacion = new \DateTime("now");
    }

    public function preTitulo() {
        if (strlen($this->titulo) > 20) {
            $t = $this->titulo;
            return substr($t,0, 19).'...';
            
        } else {
            return $this->titulo;
        }
    }
    
    public function preNoticia() {
        if (strlen($this->contenido) > 150) {
            $c = $this->contenido;
            return substr($c,0, 149).'...';
            
        } else {
            return $this->contenido;
        }
    }
    
    

}