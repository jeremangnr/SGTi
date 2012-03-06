<?php
namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\Asistencia")
 * @Table(name="asistencia")
 */
class Asistencia {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="string", length=20) 
     *  @var string 
     */    
    private $asistio;
    
    /**
    * @ManyToOne(targetEntity="Curso")
    * @JoinColumn(name="curso_id", referencedColumnName="id")
    * 
    *  @var SGTi\Entity\Curso
    */    
    private $curso;
    
    /**
     * @ManyToOne(targetEntity="Alumno", inversedBy="asistencias")
     * @JoinColumn(name="alumno_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Alumno
     */
    private $alumno;
    
    /**
     * @ManyToOne(targetEntity="Clase", inversedBy="asistencias")
     * @JoinColumn(name="clase_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Clase
     */
    private $clase;
    
    function __construct($asistencia, $alumno, $curso) {
        $this->alumno = $alumno;
	$this->asistio = $asistencia;
	$this->curso = $curso;
    }
    
    public function getId() {
	return $this->id;
    }

    public function setId($id) {
	$this->id = $id;
    }

    public function getAsistio() {
	return $this->asistio;
    }

    public function setAsistio($asistio) {
	$this->asistio = $asistio;
    }

    public function getCurso() {
	return $this->curso;
    }

    public function setCurso($curso) {
	$this->curso = $curso;
    }

    public function getAlumno() {
	return $this->alumno;
    }

    public function setAlumno($alumno) {
	$this->alumno = $alumno;
    }
    
    public function getClase() {
	return $this->clase;
    }

    public function setClase($clase) {
	$this->clase = $clase;
    }
    
}

