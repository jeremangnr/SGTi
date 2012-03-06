<?php

class Application_Form_Usuario extends Zend_Form {

    public function init() {       
        $nombre = new Zend_Form_Element_Text('nombre');
	$oldpass = new Zend_Form_Element_Password('oldpass');
        $newpass = new Zend_Form_Element_Password('newpass');
        $allElements = array($nombre, $oldpass, $newpass);                
        
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