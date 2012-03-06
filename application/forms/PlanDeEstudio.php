<?php

class Application_Form_PlanDeEstudio extends Zend_Form {

    public function init() {
        $nombre = new Zend_Form_Element_Text('nombre');
        $anio = new Zend_Form_Element_Text('anio');
        $descripcion = new Zend_Form_Element_Text('descripcion');
	$notaExoneracion = new Zend_Form_Element_Text('notaExoneracion');
	$notaAprobacion = new Zend_Form_Element_Text('notaAprobacion');
	$notaMaxima = new Zend_Form_Element_Text('notaMaxima');

        $allElements = array($nombre, $anio, $descripcion, $notaExoneracion, $notaAprobacion, $notaMaxima);
	
	$numericos= array($notaExoneracion, $notaAprobacion, $notaMaxima, $anio);
	
	foreach($numericos as $element){
             Application_Form_ValidationHelper::setupIntegerValidator($element);
        }	
	
        foreach ($allElements as $element) {
            $element->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addErrorMessage('Valor Requerido');

            $this->addElement($element);
        }
    }
}