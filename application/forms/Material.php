<?php

class Application_Form_Material extends Zend_Form {
    
    public function init() {
        $this->setAttrib('enctype', 'multipart/form-data');

        $archivo = new Zend_Form_Element_File('archivo');

        $archivo->setLabel('archivo')
                ->setDestination('/var/www/html/SGTi/public/material/')
                ->setRequired(true)
                ->addErrorMessage('Valor Requerido');
        
        $this->addElements(array($archivo));
    }

    public function setPath($p) {
        $this->path = $p;
    }

}

