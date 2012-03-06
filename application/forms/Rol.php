<?php

class Application_Form_Rol extends Zend_Form {

    public function init() {       
        $nombre = new Zend_Form_Element_Text('nombre');
	$descripcion = new Zend_Form_Element_Textarea('descripcion');
       
        $allElements = array($nombre, $descripcion);                
        
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
