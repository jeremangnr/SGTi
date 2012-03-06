<?php
namespace SGTi\Entity;

/**
 * @Entity
 * @Table(name="administrativo")
 */
class Administrativo extends Persona {    
    function __construct($administrativoData = array()) {
        parent::__construct($administrativoData);
    }
}