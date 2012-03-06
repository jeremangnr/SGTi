<?php

class Application_Form_Curso extends Zend_Form {

    public function init() {
        $anio = new Zend_Form_Element_Text('anio');

        $anio->setRequired(true)
                ->addErrorMessage('Valor Requerido')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $this->addElement($anio);
    }

}
