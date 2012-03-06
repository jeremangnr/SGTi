<?php

class Application_Form_Noticia extends Zend_Form {

    public function init() {       
        $titulo = new Zend_Form_Element_Text('titulo');
	$contenido = new Zend_Form_Element_Textarea('contenido');
       
        $allElements = array($titulo, $contenido);                
        
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
