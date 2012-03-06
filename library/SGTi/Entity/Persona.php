<?php

namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="persona")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"administrativo" = "Administrativo", "docente" = "Docente", "alumno" = "Alumno"})
 */
class Persona {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     * */
    protected $id;

    /** @Column(type="string", length=8, nullable=false, unique=true)
     *  @var string 
     * */
    protected $ci;

    /** @Column(type="string", length=60, nullable=false)
     *
     * @var string
     */
    protected $nombre;

    /** @Column(type="string", length=60, nullable=false)
     *
     * @var string
     */
    protected $apellido;

    /** @Column(type="date", nullable=true)
     *
     * @var Date
     */
    protected $fechaNac;
    
    /** @Column(type="string", length=15, nullable=true)
     *
     * @var string
     */
    protected $telefono;

    /** @Column(type="string", length=9, nullable=true)
     *
     * @var string
     */
    protected $celular;

    /** @Column(type="string", length=60, nullable=true)
     *
     * @var string
     */
    protected $localidad;

    /** @Column(type="string", length=60, nullable=false, unique=true)
     *
     * @var string
     */
    protected $mail;

    /** @OneToOne(targetEntity="Usuario", cascade={"persist"})
     * @JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var \SGTi\Entity\Usuario
     */
    protected $usuario;

    function __construct($personData) {
        if (!is_null($personData)) {
            $this->ci = $personData['ci'];
            $this->nombre = $personData['nombre'];
            $this->apellido = $personData['apellido'];
            //aca llamo al setter para que me convierta la fecha
            $this->setFechaNac($personData['fechaNac']);	    
            $this->telefono = $personData['telefono'];
            $this->celular = $personData['celular'];
            $this->localidad = $personData['localidad'];
            $this->mail = $personData['mail'];	    
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCi() {
        return $this->ci;
    }

    public function setCi($ci) {
        $this->ci = $ci;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getFechaNac() {
        return $this->fechaNac;
    }

    public function setFechaNac($fechaNac) {
        $this->fechaNac = \DateTime::createFromFormat('d-m-Y', $fechaNac);
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getCelular() {
        return $this->celular;
    }

    public function setCelular($celular) {
        $this->celular = $celular;
    }

    public function getLocalidad() {
        return $this->localidad;
    }

    public function setLocalidad($localidad) {
        $this->localidad = $localidad;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

}
