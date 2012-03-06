<?php

class Application_Service_Material extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Material();
        }

        return self::$instance;
    }

    public function uploadMaterial($upload, $materia) {
	//estos van pa definitions.php
        $materialPath = '/var/www/html/SGTi/public/material/';
        $maxUploadSize = '10MB';
        $validExtensions = "'jpeg','jpg','doc','docx','odt','ppt','xls','png','pdf','zip','rar'";
	
        $upload->addValidator('Count', true, array('min' => 1, 'max' => 1, 'messages' => array('fileCountTooMany' => 'Solo puede elegir 1 archivo', 'fileCountTooFew' => 'Debe elegir un archivo')))
               ->addValidator('NotExists', true, array('directory' => $materialPath, 'messages' => array('fileNotExistsDoesExist' => 'Ya existe un archivo con ese nombre')))
               ->addValidator('Extension', true, array('extensions' => 'jpeg','jpg','doc','docx','odt','ppt','xls','png','pdf','zip','rar', 'messages' => array('fileExtensionFalse' => 'El archivo tiene una extension invalida')))
               ->addValidator('Size', true, array('max' => $maxUploadSize, 'messages' => array('fileSizeTooBig' => "El archivo no puede superar los " . $maxUploadSize)))
               ->setDestination($materialPath);

        if (!$upload->isValid()) {
            return implode(',', $upload->getMessages());
        }

        try {
            $uploadSuccess = $upload->receive();
	    
            $fileInfo = $upload->getFileInfo();
            $fileName = $fileInfo['archivo']['name'];
            $fileType = $fileInfo['archivo']['type'];
	    
            $materia = $this->entityManager->find('SGTi\Entity\Materia', $materia);
            $materiaService = Application_Service_Materia::getInstance();
            $pudoAgregar = $materiaService->agregarMaterialMateria($upload->getFilename(), $materia, $fileName, $fileType);
	    
            return $uploadSuccess;
        } catch (Zend_File_Transfer_Exception $e) {
            echo "Hubo un error: " . $e;
	    return false;
        }
    }

}
?>






