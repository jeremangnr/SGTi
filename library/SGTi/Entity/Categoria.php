<?php

namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="categoria")
 */
class Categoria {

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

    /** @OneToOne(targetEntity="Categoria", cascade={"persist"})
     *  
     *  @var SGTi\Entity\Categoria
     */
    private $padre;

    /**
     * @ManyToMany(targetEntity="Categoria")
     * @JoinTable(name="categoria_hijas",
     *      joinColumns={@JoinColumn(name="categoria_id_padre", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="categoria_id_hija", referencedColumnName="id", unique=true)}
     *      )
     *
     *  @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $hijas;

    /**
    *  @OneToMany(targetEntity="Tema", mappedBy="categoria", cascade={"persist"})
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private $temas;

    function __construct($categoriaData = array()) {
        $this->hijas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->temas = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getPadre() {
        return $this->padre;
    }

    public function setPadre($padre) {
        $this->padre = $padre;
    }

    public function getHijas() {
        return $this->categorias;
    }

    public function setHijas($hijas) {
        $this->categorias = $hijas;
    }

    public function getTemas() {
        return $this->temas;
    }

    public function setTemas($temas) {
        $this->temas = $temas;
    }
    
    public function agregarTema($tema) {
        if (!$this->temas->contains($tema)) {
            $this->temas->add($tema);
        }
    }
    
    public function agregarHija($hija) {
        /**
         * 
         * ACA HAY QUE AGREGAR TODA LA LOGICA PARA VER SI LA CAT TIENE SOLO CATEGORIAS O TEMAS.
         * si tiene temas hay que preguntar si quiere correr los temas que tiene hacia la nueva cat y eso
         * 
         **/
        
        if (!$this->hijas->contains($hija)) {
            // agrego la cat hija
            $this->hijas->add($hija);
            // le setteo el padre (yo soy el padre. o sea, no yo jeremias, sino $this)
            $hija->setPadre($this);
        }
    }

}