<?php

namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\Materia")
 * @Table(name="materia")
 */
class Materia {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     * */
    private $id;

    /** @Column(type="string", length=50) 
     * @var string
     */
    private $nombre;

    /** @Column(type="integer", length=3) 
     * @var integer
     */
    private $creditos;

    /** @Column(type="string", length=30) 
     * @var string
     */
    private $tipoAprobacion;

    /**
     * @ManyToOne(targetEntity="Periodo", inversedBy="materias")
     * @JoinColumn(name="periodo_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Periodo
     */
    private $periodo;

    /**
     * @OneToMany(targetEntity="Curso", mappedBy="materia", cascade={"persist"})
     * 
     *  @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $cursos;

    /**
     * @ManyToMany(targetEntity="Material", cascade={"persist"})
     * @JoinTable(name="materia_materiales",
     *      joinColumns={@JoinColumn(name="materia_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="material_id", referencedColumnName="id", unique=true)}
     *      )
     * 
     *  @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $materiales;

    /**
     * @ManyToMany(targetEntity="Materia", mappedBy="previaturas", cascade={"persist"})      
     *  @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $materiaPadre;

    /**
     * @ManyToMany (targetEntity="Materia", inversedBy="materiaPadre", cascade={"persist"})
     * @JoinTable (name="materia_padre_previaturas",
     *      joinColumns={@JoinColumn(name="materia_padre_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="previatura_id", referencedColumnName="id")}
     *      )
     *  @var SGTi\Entity\Materia
     */
    private $previaturas;

    function __construct($materiaData = array()) {
        $this->cursos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->materiales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->previaturas = new \Doctrine\Common\Collections\ArrayCollection();

        $this->nombre = $materiaData['nombre'];
        $this->creditos = $materiaData['creditos'];
        $this->tipoAprobacion = $materiaData['tipoAprobacion'];
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

    public function getCreditos() {
        return $this->creditos;
    }

    public function setCreditos($creditos) {
        $this->creditos = $creditos;
    }

    public function getTipoAprobacion() {
        return $this->tipoAprobacion;
    }

    public function setTipoAprobacion($tipoAprobacion) {
        $this->tipoAprobacion = $tipoAprobacion;
    }

    public function getPeriodo() {
        return $this->periodo;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    public function getCursos() {
        return $this->cursos;
    }

    public function setCursos($cursos) {
        $this->cursos = $cursos;
    }

    public function getMateriales() {
        return $this->materiales;
    }

    public function setMateriales($materiales) {
        $this->materiales = $materiales;
    }

    public function getPreviaturas() {
        return $this->previaturas;
    }

    public function setPreviaturas($previaturas) {
        $this->previaturas = $previaturas;
    }

    public function getMateriaPadre() {
        return $this->materiaPadre;
    }

    public function setMateriaPadre($materiaPadre) {
        $this->materiaPadre = $materiaPadre;
    }

    public function agregarCurso($curso) {
        // setteo a true por defecto
        $addCurso = true;

        if (!$this->cursos->isEmpty()) {
            foreach ($this->cursos as $matCurso) {
                if ($curso->getAnio() == $matCurso->getAnio()) {
                    // si encuentro uno igual no voy a agregar
                    $addCurso = false;
                    break;
                }
            }
        }
        // si no agrego devuelvo vacio (null)
        if ($addCurso) {
            $this->cursos->add($curso);
            $curso->setMateria($this);
            
            return $this;
        } else {
            return;
        }
    }

    public function agregarMaterial($material) {
        if (!$this->materiales->contains($material)) {
            $this->materiales->add($material);
        }
    }

    public function agregarPreviatura($previatura) {
        if (!$this->previaturas->contains($previatura)) {
            $this->previaturas->add($previatura);
        }
    }

}
