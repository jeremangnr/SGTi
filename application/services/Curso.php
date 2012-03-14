<?php

class Application_Service_Curso extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
	parent::__initResources();
    }

    public static function getInstance() {
	if (is_null(self::$instance)) {
	    self::$instance = new Application_Service_Curso();
	}

	return self::$instance;
    }

    public function saveCurso($cursoData) {
	$curso = new SGTi\Entity\Curso($cursoData);

	try {
	    $this->entityManager->beginTransaction();

	    $this->entityManager->persist($curso);
	    $this->entityManager->flush();

	    $this->entityManager->commit();
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}

	return true;
    }

    public function removeCurso($curso) {

	try {
	    $this->entityManager->beginTransaction();

	    $this->entityManager->remove($curso);
	    $this->entityManager->flush();

	    $this->entityManager->commit();
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}

	return true;
    }

    public function updateCurso($cursoId, $cursoData) {
	$curso = $this->entityManager->find('SGTi\Entity\Curso', $cursoId);

	foreach ($cursoData as $field => $value) {
	    $setterName = 'set' . ucfirst($field);

	    $curso->$setterName($value);
	}

	try {
	    $this->entityManager->beginTransaction();

	    $updatedCurso = $this->entityManager->merge($curso);
	    $this->entityManager->flush();

	    $this->entityManager->commit();
	    return $updatedCurso;
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}

	return true;
    }

    public function agregarHorarioCurso($idCurso, $idSalon, $horarioData) {
	$curso = $this->entityManager->find('SGTi\Entity\Curso', $idCurso);
	$salon = $this->entityManager->find('SGTi\Entity\Salon', $idSalon);

	$horario = new SGTi\Entity\Horario($horarioData);

	$horario->setCurso($curso);
	$horario->setSalon($salon);
	$curso->getHorarios()->add($horario);

	try {
	    $this->entityManager->beginTransaction();

	    //$this->entityManager->persist($horario);
	    $this->entityManager->merge($curso);
	    $this->entityManager->flush();

	    $this->entityManager->commit();
	    return true;
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}

	return true;
    }

    public function inscribirAlumnosCurso($alumnos, $idCurso) {	
	$curso = $this->entityManager->find('SGTi\Entity\Curso', $idCurso);
	//variable para guardar los alumnos que se pueden inscribir al curso
	$listaAlumnos = array();
        
	foreach ($alumnos as $id) {
	    $alumnoSelected = $this->entityManager->find('SGTi\Entity\Alumno', $id);
	    // check to see if the student can be signed up for the course (it can't already have it)
	    if ($alumnoSelected->puedeInscribirEnCurso($curso)) {		
		//creo la inscripcion al curso
		$inscripcionCurso = new SGTi\Entity\InscripcionCurso();
		//le agrego el curso
		$inscripcionCurso->setCurso($curso);
		//le agrego la inscripcion curso al alumno
		$alumnoSelected->getInscripcion()->agregarInscripcionCurso($inscripcionCurso);		
		//guardo en el alumno en el array
		$listaAlumnos[] = $alumnoSelected;
	    } else {
                // if ANY of the students can't be signed up for the course, we return and throw an error
                return false;
            }
	}
	
	try {
	    $this->entityManager->beginTransaction();
            
	    //inscribo los alumno
	    foreach ($listaAlumnos as $alumno) {
		$this->entityManager->merge($alumno);
	    }
            
	    $this->entityManager->flush();
	    $this->entityManager->commit();
            
	    return true;
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}
    }

    public function agregarDocenteCurso($docente, $materias) {
	$docente = $this->entityManager->find('SGTi\Entity\Docente', $docente);

	foreach ($materias as $id) {
	    $materiaSelected = $this->entityManager->find('SGTi\Entity\Materia', $id);
	    $ultimoCurso = null;	    
	    if($materiaSelected->getCursos() != null){
		foreach ($materiaSelected->getCursos() as $curso){
		    if($curso->getAnio() == date('Y')){
			$ultimoCurso = $curso;
		    }
		}
	    } else{ return false;}
	    
	    
	    if ($ultimoCurso == null) {		
		return false;
	    }
	    
	    if($docente->getCursos() != null){
		foreach ($docente->getCursos() as $curso){
		    if($curso == $ultimoCurso){
			return false;
		    }
		}
	    }
	    
	    try {
		$ultimoCurso->agregarDocente($docente);
		$docente->agregarCurso($ultimoCurso);
		$this->entityManager->beginTransaction();

		$this->entityManager->merge($docente);
		$this->entityManager->flush();

		$this->entityManager->commit();
	    } catch (Exception $e) {
		$this->entityManager->rollback();
		$this->entityManager->close();

		echo $e->getMessage();
	    }
	    
	    return true;
	}
    }

    public function agregarClase($cursoId, $alumnos, $totalAlumnos, $fecha) {
	//traigo el curso
	$curso = $this->entityManager->find('SGTi\Entity\Curso', $cursoId);

	//creo la clase a la que luego le voy a cargar las asistencias
	//falta ver el tema de la fecha, porque en este caso es mejor seleccionarla en la vista y que no se cree sola
	$clase = new SGTi\Entity\Clase($curso, $fecha);
	//aca tengo que recorrer todos los alumnos
        
	foreach ($totalAlumnos as $idalumno) {
	    //magia jeremias
	    $asistio = (in_array($idalumno, $alumnos)) ? 'Si' : 'No';
	    //traigo el alumno 
	    $alu = $this->entityManager->find('SGTi\Entity\Alumno', $idalumno);
	    //creo la asistencia
	    $asistencia = new SGTi\Entity\Asistencia($asistio, $alu, $curso);
	    //agrego la fecha y asistencia a la clase	    
	    $clase->agregarAsistencia($asistencia);
	    $asistencia->setClase($clase);
	}
        
	//agrego la clase al curso
	$curso->agregarClase($clase);
	try {
	    $this->entityManager->beginTransaction();

	    $this->entityManager->merge($curso);
	    $this->entityManager->flush();

	    $this->entityManager->commit();
	    return true;
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}
    }
    
    public function agregarNota($alumnos, $notas, $observaciones, $idCurso, $evCalData) {
	$usuario = Zend_Auth::getInstance()->getIdentity();
	$user = $this->entityManager->find('SGTi\Entity\Usuario', $usuario->getId());
	$curso = $this->entityManager->find('SGTi\Entity\Curso', $idCurso);
	$eventoCalificacion = new SGTi\Entity\EventoCalificacion($evCalData);
	//$eventoCalificacion->setUsuario($usuario);
	$eventoCalificacion->setCurso($curso);
	$eventoCalificacion->setUsuario($user);
	$contador = 0;

	foreach ($alumnos as $idalumno) {
	    $observacion = $observaciones[$contador];
	    
	    $alu = $this->entityManager->find('SGTi\Entity\Alumno', $idalumno);
	    
	    if($notas[$contador] == ""){
		return false;
	    }
	    
	    $calificacion = new SGTi\Entity\Calificacion($notas[$contador], $observacion);
	    $calificacion->setAlumno($alu);
	    $calificacion->setCurso($curso);
	    $calificacion->setEventoCalificacion($eventoCalificacion);
	    foreach ($alu->getInscripcion()->getInscripcionesCurso() as $inscripcion){
		if($inscripcion->getCurso() == $curso){
		    $inscripcion->agregarCalificacion($calificacion);
		}
	    }
	    
	    $eventoCalificacion->agregarCalificacion($calificacion);
	    
	    
	    
	    //$usuario->agregarEventoCalificacion($eventoCalificacion);
	    
	    $contador++;
	}
	//agrego el evento calificacion al curso
	$curso->agregarEventoCalificacion($eventoCalificacion);
	try {
	    $this->entityManager->beginTransaction();

	    $this->entityManager->merge($curso);
	    $this->entityManager->flush();

	    $this->entityManager->commit();
	    return true;
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}
    } 
    
    public function actualizarInscripcionCurso($insCurso, $estado, $nota){
	//$inscripcionCurso = $insCurso;
	
	$insCurso->setEstado($estado);
	$insCurso->setNotaObtenida($nota);
	
	try {
	    $this->entityManager->beginTransaction();

	    $updatedCurso = $this->entityManager->merge($insCurso);
	    $this->entityManager->flush();

	    $this->entityManager->commit();
	    return $updatedCurso;
	} catch (Exception $e) {
	    $this->entityManager->rollback();
	    $this->entityManager->close();

	    echo $e->getMessage();
	    return false;
	}

	return true;
    }
}