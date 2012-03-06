<?php

class Application_Form_Materia extends Zend_Form {

    public function init() {       
        $nombre = new Zend_Form_Element_Text('nombre');
	$creditos = new Zend_Form_Element_Text('creditos');	
	$tipoAprobacion = new Zend_Form_Element_Text('tipoAprobacion');
	
        $allElements = array($nombre, $creditos, $tipoAprobacion);                
        
        Application_Form_ValidationHelper::setupIntegerValidator($creditos);
        
        foreach ($allElements as $element) {
            $element->setRequired(true)
                    ->addErrorMessage('Valor Requerido');
        }
        
        foreach ($allElements as $element) {            
            $element->addFilter('StripTags')
                    ->addFilter('StringTrim');
            
            $this->addElement($element);
        }
    }

}
