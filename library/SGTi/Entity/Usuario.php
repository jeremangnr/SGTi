<?php

namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\Usuario")
 * @Table(name="usuario")
 */
class Usuario {

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

    /** @Column(type="string", length=30) 
     * @var string
     */
    private $password;

    /** @OneToOne(targetEntity="Persona", cascade={"persist"})
     * @JoinColumn(name="persona_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     *  @var SGTi\Entity\Persona
     */
    private $persona;

    /** @Column(type="string", length=30) 
     * @var string
     */
    private $rol;
    
    /**
    *  @OneToMany(targetEntity="Noticia", mappedBy="usuario", cascade={"persist"})
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private $noticias;
    
    function __construct($nombre, $rol) {
	$this->noticias = new \Doctrine\Common\Collections\ArrayCollection();

	if (!is_null($nombre) && !is_null($rol)) {
	    $this->nombre = $nombre;
	    $this->password = $nombre;
	    $this->rol = $rol;
	}
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

    public function getPassword() {
	return $this->password;
    }

    public function setPassword($password) {
	$this->password = $password;
    }

    public function getPersona() {
	return $this->persona;
    }

    public function setPersona($persona) {
	$this->persona = $persona;
    }

    public function getRol() {
	return $this->rol;
    }

    public function setRol($rol) {
	$this->rol = $rol;
    }

    public function getNoticias() {
	return $this->noticias;
    }

    public function setNoticias($noticias) {
	$this->noticias = $noticias;
    }

    public function agregarNoticia($noticia) {
	if (!$this->noticias->contains($noticia)) {
	    $this->noticias->add($noticia);
	    $noticia->setUsuario($this);
	}
    }

}
