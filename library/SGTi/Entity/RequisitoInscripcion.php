<?php
namespace SGTi\Entity;

/**
 * @Entity(repositoryClass="SGTi\Repository\RequisitoInscripcion")
 * @Table(name="requisito_inscripcion")
 */
class RequisitoInscripcion {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     **/
    private $id;
    
    /** @Column(type="string", length=100) 
     * @var string
     */
    private $nombre;
    
   /**
     * @ManyToOne(targetEntity="PlanDeEstudio", inversedBy="requisitosInscripcion")
     * @JoinColumn(name="plan_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\PlanDeEstudio
     */
    private $planDeEstudio;
    
    
    
    function __construct($nombre) {
      
       $this->nombre=$nombre;
       
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

    public function setNombre($ombre) {
	$this->nombre = $nombre;
    }
    
    public function getPlanDeEstudio() {
        return $this->planDeEstudio;
    }

    public function setPlanDeEstudio($planDeEstudio) {
        $this->planDeEstudio = $planDeEstudio;
    }
   
}