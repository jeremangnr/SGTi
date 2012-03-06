<?php
namespace SGTi\Entity;
/**
 * @Entity(repositoryClass="SGTi\Repository\Docente")
 * @Table(name="docente")
 */
class Docente extends Persona {
    
    /**
    * @ManyToMany(targetEntity="Curso", inversedBy="docentes")
    * @JoinTable(name="docente_cursos",
    *      joinColumns={@JoinColumn(name="docente_id", referencedColumnName="id")},
    *      inverseJoinColumns={@JoinColumn(name="curso_id", referencedColumnName="id")}
    *      )
    *
    *  @var \Doctrine\Common\Collections\ArrayCollection
    */
    private $cursos;
    
    function __construct($docenteData) {
        parent::__construct($docenteData);
        
        $this->cursos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getCursos() {
	return $this->cursos;
    }

    public function setCursos($cursos) {
	$this->cursos = $cursos;
    }
    
    public function agregarCurso($curso) {
        if (!$this->cursos->contains($curso)) {
            $this->cursos->add($curso);
        }
    }

}