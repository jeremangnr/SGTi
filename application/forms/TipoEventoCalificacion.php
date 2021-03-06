<?php

class Application_Form_Periodo extends Zend_Form {

    public function init() {       
	$nombre = new Zend_Form_Element_Text('nombre');
	
        
        $allElements = array($nombre);                
        Application_Form_ValidationHelper::setupIntegerValidator($numero);
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
