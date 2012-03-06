<?php

/**
 * 
 * Clase Helper, por ahora no tiene muchas cosas, la estoy usando para no repetir el codigo de agregar validacion
 * a los elementos en cada form
 * 
 */
class Application_Form_ValidationHelper {    
    /**
     *
     * @param Zend_Form_Element $element
     * @param String $validators Lista de validaciones que se quieren agregar
     */
    public static function setupTextValidator($element) {
        $element->addFilter('StripTags')
                ->addFilter('StringTrim')
                // validadador NotEmpty. medio obvio lo que hace
                ->addValidator('Alpha', true, array('messages' => array('notAlpha' => 'Solo puede ingresar letras'), 'allowWhiteSpace' => true));
        
        return $element;
    }
    
    public static function setupIntegerValidator($element) {
        $element->addFilter('StripTags')
                ->addFilter('StringTrim')                
                // el validador Digits controla que solo sean nros
                ->addValidator('Int', true, array('messages' => array('notInt' => 'Debe Ingresar Solo Numeros')));
        
        return $element;
    }
    
    public static function setupEmailValidator($element) {
        // 'emailAddressInvalidFormat'
        $element->addFilter('StripTags')
                ->addFilter('StringTrim')                
                // el validador Digits controla que solo sean nros
                ->addValidator('EmailAddress', true, array('messages' => array('emailAddressInvalidFormat' => 'Direccion Invalida')));
        
        return $element;
    }
    
    public static function setupRequiredValidator($element) {
        $element->setRequired(true)      
                // validadador NotEmpty. medio obvio lo que hace
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Debe Ingresar un Valor')));
        
        return $element;
    }
}
