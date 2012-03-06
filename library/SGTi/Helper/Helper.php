<?php

namespace SGTi\Helper;

class Helper {

    public static function arrayToJson($array, $isObjectArray = false) {
        // array_walk_recursive() ejecuta una funcion nuestra sobre cada elemento del array.
        // la firma de la funcion nuestra debe recibir en como param el value primero, y la clave despues
        if (!$isObjectArray) {
            array_walk_recursive($array, array('SGTi\Helper\Helper' ,'encode_items'));            
        }
        
        $json = json_encode($array);

        return $json;
    }

    // IMPORTANTISIMO: aca usamos el '&' para pasar por referencia al value. que se logra con esto?
    // que se modifique directamente el value en el array mismo, sin tener que settearlo de vuelta.
    private static function encode_items(&$value, $key) {
        $value = utf8_encode($value);
    }

}
