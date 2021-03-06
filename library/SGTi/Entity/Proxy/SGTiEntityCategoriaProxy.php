<?php

namespace SGTi\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class SGTiEntityCategoriaProxy extends \SGTi\Entity\Categoria implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }

    public function getNombre()
    {
        $this->__load();
        return parent::getNombre();
    }

    public function setNombre($nombre)
    {
        $this->__load();
        return parent::setNombre($nombre);
    }

    public function getPadre()
    {
        $this->__load();
        return parent::getPadre();
    }

    public function setPadre($padre)
    {
        $this->__load();
        return parent::setPadre($padre);
    }

    public function getHijas()
    {
        $this->__load();
        return parent::getHijas();
    }

    public function setHijas($hijas)
    {
        $this->__load();
        return parent::setHijas($hijas);
    }

    public function getTemas()
    {
        $this->__load();
        return parent::getTemas();
    }

    public function setTemas($temas)
    {
        $this->__load();
        return parent::setTemas($temas);
    }

    public function agregarHija($hija)
    {
        $this->__load();
        return parent::agregarHija($hija);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'nombre', 'padre', 'hijas', 'temas');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}