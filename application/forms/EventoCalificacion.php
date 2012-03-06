<?php

class Application_Form_EventoCalificacion extends Zend_Form {

    public function init() {                
        $notaMax = new Zend_Form_Element_Text('notaMax');
        $notaAprobacion = new Zend_Form_Element_Text('notaAprobacion');
        $descripcion = new Zend_Form_Element_Textarea('descripcion');
        //
        // LA FECHA NO LA VOY A PONER EN EL FORMULARIO PORQUE SE VA A GENERAR AUTOMATICAMENTE
        // va, la podriamos poner, pero SOLO para mostrarla. se actualiza y se genera sola, ver la clase
        //        
                
        //$fecha = new Zend_Form_Element_Text('fecha');
        
        $allElements = array($descripcion, $notaMax, $notaAprobacion);

        foreach ($allElements as $element) {
            $element->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addErrorMessage('Valor Requerido');

            $this->addElement($element);
        }
    }
}