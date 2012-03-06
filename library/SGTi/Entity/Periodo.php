<?php
namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\Periodo")
 * @Table(name="periodo")
 */
class Periodo {
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
    
    /** @Column(type="integer", length=3) 
     * @var integer
     */
    private $numero;
    
    /**
     * @ManyToOne(targetEntity="PlanDeEstudio", inversedBy="periodos")
     * @JoinColumn(name="plan_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\PlanDeEstudio
     */
    private $planDeEstudio;
    
    /**
    *  @OneToMany(targetEntity="Materia", mappedBy="periodo", cascade={"persist"})
    *  @var \Doctrine\Common\Collections\ArrayCollection()
    */
    private $materias;
    
    function __construct($periodoData = array()) {
        $this->materias = new \Doctrine\Common\Collections\ArrayCollection();
        
       $this->tipo=$periodoData['tipo'];
       $this->numero=$periodoData['numero'];
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

    public function getNumero() {
	return $this->numero;
    }

    public function setNumero($numero) {
	$this->numero = $numero;
    }
    
    public function getPlanDeEstudio() {
        return $this->planDeEstudio;
    }

    public function setPlanDeEstudio($planDeEstudio) {
        $this->planDeEstudio = $planDeEstudio;
    }
    
    public function getMaterias() {
	return $this->materias;
    }

    public function setMaterias($materias) {
	$this->materias = $materias;
    }
    
    public function agregarMateria($materia) {
        // setteo a true por defecto
        $addMateria = true;

        if (!$this->materias->isEmpty()) {
            foreach ($this->materias as $perMateria) {
                if ($materia->getNombre() == $perMateria->getNombre()) {
                    // si encuentro una igual no voy a agregar
                    $addMateria = false;
                    break;
                }
            }
        }
        // si no agrego devuelvo vacio (null)
        if ($addMateria) {
            $this->materias->add($materia);
            $materia->setPeriodo($this);
            
            return $this;
        } else {
            return;
        }
    }

}