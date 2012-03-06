<?php

class Application_Form_Horario extends Zend_Form {

    public function init() {
        $horaInicio = new Zend_Form_Element_Text('horaInicio');
        // EL FORMATO DE HORA DE INICIO Y FIN ES H:M:S, LA HORA VA EN 24HS
        $horaFin = new Zend_Form_Element_Text('horaFin');
        $dia = new Zend_Form_Element_Text('dia');

        $allElements = array($horaInicio, $horaFin, $dia);

        foreach ($allElements as $element) {
            $element->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addErrorMessage('Valor Requerido');

            $this->addElement($element);
        }
    }
}