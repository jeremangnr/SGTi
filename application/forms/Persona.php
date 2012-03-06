<?php

class Application_Form_Persona extends Zend_Form {

    public function init() {
        $ci = new Zend_Form_Element_Text('ci');
        $nombre = new Zend_Form_Element_Text('nombre');
        $apellido = new Zend_Form_Element_Text('apellido');
        $fechaNac = new Zend_Form_Element_Text('fechaNac');
        $telefono = new Zend_Form_Element_Text('telefono');
        $celular = new Zend_Form_Element_Text('celular');
        $localidad = new Zend_Form_Element_Text('localidad');
        $mail = new Zend_Form_Element_Text('mail');
        
        $allElements = array($ci, $nombre, $apellido,  $telefono, $celular, $localidad, $mail,$fechaNac);
        $requiredElements = array($ci, $nombre, $apellido,$fechaNac, $mail);
        
        foreach ($requiredElements as $requiredElement) {
            Application_Form_ValidationHelper::setupRequiredValidator($requiredElement);
        }
        
        Application_Form_ValidationHelper::setupIntegerValidator($ci);
        Application_Form_ValidationHelper::setupTextValidator($nombre);
        Application_Form_ValidationHelper::setupTextValidator($apellido);
        //Application_Form_ValidationHelper::setupTextValidator($fechaNac);   
        Application_Form_ValidationHelper::setupIntegerValidator($telefono);
        Application_Form_ValidationHelper::setupIntegerValidator($celular);
        Application_Form_ValidationHelper::setupTextValidator($localidad);
        Application_Form_ValidationHelper::setupEmailValidator($mail);
        
        foreach ($allElements as $element) {
            // lo agrego al form
            $this->addElement($element);
        }
    }

}
