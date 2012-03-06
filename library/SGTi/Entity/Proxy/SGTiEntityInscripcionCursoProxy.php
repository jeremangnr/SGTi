<?php

namespace SGTi\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class SGTiEntityInscripcionCursoProxy extends \SGTi\Entity\InscripcionCurso implements \Doctrine\ORM\Proxy\Proxy
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

    public function getNotaObtenida()
    {
        $this->__load();
        return parent::getNotaObtenida();
    }

    public function setNotaObtenida($notaObtenida)
    {
        $this->__load();
        return parent::setNotaObtenida($notaObtenida);
    }

    public function getEstado()
    {
        $this->__load();
        return parent::getEstado();
    }

    public function setEstado($estado)
    {
        $this->__load();
        return parent::setEstado($estado);
    }

    public function getEventosAdministrativos()
    {
        $this->__load();
        return parent::getEventosAdministrativos();
    }

    public function setEventosAdministrativos($eventosAdministrativos)
    {
        $this->__load();
        return parent::setEventosAdministrativos($eventosAdministrativos);
    }

    public function getCurso()
    {
        $this->__load();
        return parent::getCurso();
    }

    public function setCurso($curso)
    {
        $this->__load();
        return parent::setCurso($curso);
    }

    public function getCalificaciones()
    {
        $this->__load();
        return parent::getCalificaciones();
    }

    public function setCalificaciones($calificaciones)
    {
        $this->__load();
        return parent::setCalificaciones($calificaciones);
    }

    public function agregarEventoAdministrativo($eventoAdministrativo)
    {
        $this->__load();
        return parent::agregarEventoAdministrativo($eventoAdministrativo);
    }

    public function agregarCalificacion($calificacion)
    {
        $this->__load();
        return parent::agregarCalificacion($calificacion);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'estado', 'notaObtenida', 'eventosAdministrativos', 'curso', 'calificaciones');
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