<?php

namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="horario")
 */
class Horario {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     * */
    private $id;

    /** @Column(type="time") 
     * @var DateTime
     */
    private $horaInicio;

    /** @Column(type="time") 
     * @var DateTime
     */
    private $horaFin;

    /** @Column(type="string", length=15) 
     * @var string
     */
    private $dia;

    /**
     * @ManyToOne(targetEntity="Salon")
     * @JoinColumn(name="salon_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Salon
     */
    private $salon;

    /**
     * @ManyToOne(targetEntity="Curso")
     * @JoinColumn(name="curso_id", referencedColumnName="id")
     * 
     *  @var SGTi\Entity\Curso
     */
    private $curso;

    function __construct($horarioData = array()) {
        // EL FORMATO DE HORA DE INICIO Y FIN ES H:M:S, LA HORA VA EN 24HS
        $this->setHoraInicio($horarioData['horaInicio']);
        $this->setHoraFin($horarioData['horaFin']);
        $this->dia = $horarioData['dia'];
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getHoraInicio() {
        return $this->horaInicio;
    }

    public function setHoraInicio($horaInicio) {
        $this->horaInicio = new \DateTime($horaInicio);
    }

    public function getHoraFin() {
        return $this->horaFin;
    }

    public function setHoraFin($horaFin) {
        $this->horaFin = new \DateTime($horaFin);
    }

    public function getDia() {
        return $this->dia;
    }

    public function setDia($dia) {
        $this->dia = $dia;
    }

    public function getSalon() {
        return $this->salon;
    }

    public function setSalon($salon) {
        $this->salon = $salon;
    }

    public function getCurso() {
        return $this->curso;
    }

    public function setCurso($curso) {
        $this->curso = $curso;
    }

}
