<?php

class Application_Form_Periodo extends Zend_Form {

    public function init() {       
	$tipo = new Zend_Form_Element_Text('tipo');
	$numero = new Zend_Form_Element_Text('numero');
        
        $allElements = array($tipo, $numero);                
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
