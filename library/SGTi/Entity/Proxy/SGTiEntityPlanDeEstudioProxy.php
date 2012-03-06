<?php

namespace SGTi\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class SGTiEntityPlanDeEstudioProxy extends \SGTi\Entity\PlanDeEstudio implements \Doctrine\ORM\Proxy\Proxy
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

    public function getAnio()
    {
        $this->__load();
        return parent::getAnio();
    }

    public function setAnio($anio)
    {
        $this->__load();
        return parent::setAnio($anio);
    }

    public function getDescripcion()
    {
        $this->__load();
        return parent::getDescripcion();
    }

    public function setDescripcion($descripcion)
    {
        $this->__load();
        return parent::setDescripcion($descripcion);
    }

    public function getPeriodos()
    {
        $this->__load();
        return parent::getPeriodos();
    }

    public function setPeriodos($periodos)
    {
        $this->__load();
        return parent::setPeriodos($periodos);
    }

    public function getNotaExoneracion()
    {
        $this->__load();
        return parent::getNotaExoneracion();
    }

    public function setNotaExoneracion($notaExoneracion)
    {
        $this->__load();
        return parent::setNotaExoneracion($notaExoneracion);
    }

    public function getNotaAprobacion()
    {
        $this->__load();
        return parent::getNotaAprobacion();
    }

    public function setNotaAprobacion($notaAprobacion)
    {
        $this->__load();
        return parent::setNotaAprobacion($notaAprobacion);
    }

    public function getNotaMaxima()
    {
        $this->__load();
        return parent::getNotaMaxima();
    }

    public function setNotaMaxima($notaMaxima)
    {
        $this->__load();
        return parent::setNotaMaxima($notaMaxima);
    }

    public function agregarPeriodo($periodo)
    {
        $this->__load();
        return parent::agregarPeriodo($periodo);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'nombre', 'anio', 'descripcion', 'notaExoneracion', 'notaAprobacion', 'notaMaxima', 'periodos');
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