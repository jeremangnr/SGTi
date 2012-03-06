<?php

class Application_Service_Persona extends Application_Service_AbstractService {

    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Persona();
        }

        return self::$instance;
    }

    public function saveAlumno($alumnoData,$cumpleReq) {
        // creo el alumno
        $alumno = new SGTi\Entity\Alumno($alumnoData,$cumpleReq);
        
        // meto todo en un try-catch por las dudas que tire un error al persistirlo
        try {
            // empiezo la transaccion
            $this->entityManager->beginTransaction();
            // persisto el alumno y hago FLUSH para guardar los cambios a la BD
            $this->entityManager->persist($alumno);
            $this->entityManager->flush();
            
            // commiteo transaccion
            $this->entityManager->commit();
        } catch (Exception $e) {
            // si hay algun error hago rollback y "cierro" el entity manager para que no pueda ser usado
            $this->entityManager->rollback();
            $this->entityManager->close();

            //devuelvo false asi se que hubo un error al agregar
            echo $e->getMessage();

            return false;
        }

        // si todo salio bien devuelvo true
        return $alumno->getId();
    }

    public function saveDocente($docenteData) {
        $docente = new SGTi\Entity\Docente($docenteData);

        //METODO QUE HACE QUE SI ES UN ALUMNO QUE ENTRA COMO DOCENTE SU ROL SEA ALUMNO-DOCENTE
        /*
        $existente = null;

        //trae todos los usuarios y busca si ya existe uno con esa cedula
        $usuarios = $this->entityManager->getRepository('SGTi\Entity\Usuario')->findAll();
        foreach ($usuarios as $usuario) {
            if ($usuario->getPersona()->getCi() == $docente->getCi()) {
                $existente = $usuario;
            }
        }
        if ($existente) {
            //Si existe le cambio el rol a ese usuario para que sea un alumno-docente
            $usuario = $this->changeRol($existente, SGTi\Security\Roles::ALU_DOC);
            //le asigno el docente
            $usuario->setPersona($docente);
            //asigno el usuario al docente
            $docente->setUsuario($usuario);
        } else {*/
            //creo el usuario, el nombre y password por defecto van a ser la cedula
            $usuario = new SGTi\Entity\Usuario($docente->getCi(), SGTi\Security\Roles::DOC);
            //le asigno el docente
            $usuario->setPersona($docente);
            //asigno el usuario al docente
            $docente->setUsuario($usuario);
        /*}*/


        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($docente);
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

    public function saveAdministrativo($administrativoData) {
        $administrativo = new SGTi\Entity\Administrativo($administrativoData);

        //creo el usuario, el nombre y password por defecto van a ser la cedula
        $usuario = new SGTi\Entity\Usuario($administrativo->getCi(), SGTi\Security\Roles::ADMIN);
        //le asigno el administrativo
        $usuario->setPersona($administrativo);
        //asigno el usuario al administrativo
        $administrativo->setUsuario($usuario);
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($administrativo);
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

    public function removeAdministrativo($administrativo) {

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($administrativo);
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

    public function removeDocente($docente) {

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($docente);
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

    public function removeAlumno($alumno) {

        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->remove($alumno);
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

    public function updateAlumno($alumnoId, $alumnoData) {

        $alumno = $this->entityManager->find('SGTi\Entity\Alumno', $alumnoId);

        // cargo los datos nuevos (en realidad cargo todos, no me fijo que hayan cambiado pero no jode en nada)
        foreach ($alumnoData as $field => $value) {
            $setterName = 'set' . ucfirst($field);

            $alumno->$setterName($value);
        }

        try {
            $this->entityManager->beginTransaction();

            $updatedAlumno = $this->entityManager->merge($alumno);
            $this->entityManager->flush();

            $this->entityManager->commit();
            return $updatedAlumno;
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }

        return true;
    }

    public function updateAdministrativo($administrativoId, $administrativoData) {

        $administrativo = $this->entityManager->find('SGTi\Entity\Administrativo', $administrativoId);

        foreach ($administrativoData as $field => $value) {
            $setterName = 'set' . ucfirst($field);

            $administrativo->$setterName($value);
        }

        try {
            $this->entityManager->beginTransaction();

            $updatedAdministrativo = $this->entityManager->merge($administrativo);
            $this->entityManager->flush();

            $this->entityManager->commit();
            return $updatedAdministrativo;
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }

        return true;
    }

    public function updateDocente($docenteId, $docenteData) {

        $docente = $this->entityManager->find('SGTi\Entity\Docente', $docenteId);

        foreach ($docenteData as $field => $value) {
            $setterName = 'set' . ucfirst($field);

            $docente->$setterName($value);
        }

        try {
            $this->entityManager->beginTransaction();

            $updatedDocente = $this->entityManager->merge($docente);
            $this->entityManager->flush();

            $this->entityManager->commit();
            return $updatedDocente;
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }

        return true;
    }

    public function changeRol($usuario, $rol) {

        $usuario->setRol($rol);
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->merge($usuario);
            $this->entityManager->flush();

            $this->entityManager->commit();
            return $usuario;
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            echo $e->getMessage();
            return false;
        }
    }

    public function updatePassword($personaId, $usuarioData) {

        $persona = $this->entityManager->find('SGTi\Entity\Persona', $personaId);

        if ($persona->getUsuario()->getPassword() == $usuarioData['oldpass']) {

            $persona->getUsuario()->setPassword($usuarioData['newpass']);

            try {
                $this->entityManager->beginTransaction();

                $this->entityManager->merge($persona);
                $this->entityManager->flush();

                $this->entityManager->commit();
                return true;
            } catch (Exception $e) {
                $this->entityManager->rollback();
                $this->entityManager->close();

                echo $e->getMessage();
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function generarescolaridad($idAlumno,$descargar){
        $alumno = $this->entityManager->find('SGTi\Entity\Alumno',$idAlumno);
        // echo $alumno->getInscripcion()->getFechaInscripcion();
        //echo $alumno->getInscripcion()->getFechaInscripcion()->format('d-m-Y');
        $htmlEsc = '';
        $cabecera =
                '<html>
            <head>
		<title>Escolaridad Tecnologo en Informatica</title>
            </head>
            <body>
                <p><img style="width: 71px; height: 107px; alt="" src="http://www.marlund.com/wordpress/wp-content/uploads/2010/02/logo_udelar.jpg" /></p>
                <p style="font-size: 14px;"><strong><u>Universidad de la Republica</u></strong></p>
		<p><span style="font-size: 14px;"><strong><u>Facultad de ingenieria</u></strong></span></p>
		<p><span style="font-size: 14px;"><u><strong>Alumno:' . $alumno->getNombre() . ' ' . $alumno->getApellido() . '</strong></u></span></p>
		<p style="text-align: center;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>Certificado De Escolaridad</u></p>';

        $htmlEsc = $htmlEsc . $cabecera;

        $datosCarrera =
                '<center><table border="1" cellpadding="1" cellspacing="1" dir="ltr" style="width: 500px;">
			<thead>
                            <tr>
                                <th scope="col">Carrera</th>
				<th scope="col">Fecha Insc.</th>
                                <th scope="col">Plan</th>
                                <th scope="col">Calidad</th>
                            </tr>
			</thead>
			<tbody>
				<tr>
                                    <td>Tecnologo en Informatica</td>
                                    <td>' . $alumno->getInscripcion()->getFechaInscripcion()->format('d-m-Y') . '</td>
                                    <td>' . $alumno->getInscripcion()->getPlanDeEstudio()->getNombre() . '</td>
                                    <td>En curso</td>
				</tr>
			</tbody>
		</table></center>';
        $htmlEsc = $htmlEsc . $datosCarrera;
        // echo $htmlEsc;


        $materiasT =
                '<p>&nbsp;</p>
             <p style="text-align: center;"><u>Materias: </u></p>
            <center> 
            <table border="1" cellpadding="1" cellspacing="1" style="width: 500px;">
                <tbody>
                    <tr>
                        <td style="text-align: center;"><strong>Asignatura</strong></td>
			<td style="text-align: center;"><strong>Creditos</strong></td>
			<td style="text-align: center;"><strong>Tipo Aprobaci&oacute;n</strong></td>
			<td style="text-align: center;"><strong>Aprobo</strong></td>
			<td style="text-align: center;"><strong>Nota</strong></td>
                    </tr>';
        $materias = $alumno->getInscripcion()->getInscripcionesCurso();
        foreach ($materias as $InscripcionCurso) {
            $materiasT = $materiasT . '<tr>
                                    <td>' . $InscripcionCurso->getCurso()->getMateria()->getNombre() . '</td>
                                    <td>' . $InscripcionCurso->getCurso()->getMateria()->getCreditos() . '</td>
                                    <td>' . $InscripcionCurso->getCurso()->getMateria()->getTipoAprobacion() . '</td>';
            $estado = "";
            if ($InscripcionCurso->getEstado() == 0) {
                $estado = "Pendiente";
            } else {
                if ($InscripcionCurso->getEstado() == 1) {
                    $estado = "Inscripto";
                } else {
                    if ($InscripcionCurso->getEstado() == 2) {
                        $estado = "Cursando";
                    } else {
                        if ($InscripcionCurso->getEstado() == 3) {
                            $estado = "Aprobado";
                        } else {
                            if ($InscripcionCurso->getEstado() == 4) {
                                $estado = "Examen";
                            } else {
                                $estado = "Rescursa";
                            }
                        }
                    }
                }
            }
            $materias2 = '<td>' . $estado . '</td>
                                    <td>' . $InscripcionCurso->getNotaObtenida() . '</td>
                                    </tr>';
            //$materiasT=$materias.$materias2;
        }
        $htmlEsc = $htmlEsc . $materiasT;



        $footer = '</tbody>
		</table
                </center>
                </body></html>';
        $htmlEsc = $htmlEsc . $footer;
        if($descargar==true){
            require_once('dompdf/dompdf_config.inc.php');
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->pushAutoloader('DOMPDF_autoload', '');  
            $dompdf = new DOMPDF();
            $dompdf->load_html($htmlEsc);
            $dompdf->render();
            $dompdf->stream("Escolaridad.pdf");   
        }
        else{
        
            echo $htmlEsc;
        }
        }
    
        public function generarenotainscripcion($idAlumno,$requisitos){
            $alumno = $this->entityManager->find('SGTi\Entity\Alumno',$idAlumno);
            $html='<html>
	<head>
		<title>Inscripcion</title>
	</head>
	<body>
                 <p><img style="width: 71px; height: 107px; alt="" src="http://www.marlund.com/wordpress/wp-content/uploads/2010/02/logo_udelar.jpg" /></p>
		<h3>
			<strong>Control de Inscripciones: Tecnologo en Inform&aacute;tica</strong></h3>
		<p>
			Cedula:'.$alumno->getCi().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <p>
			Nombre:'.$alumno->getNombre().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Apellido:'.$alumno->getApellido().'</p>
		<p>
			Telefono:'.$alumno->getTelefono().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Celular:'.$alumno->getCelular().'</p>
		<p>
			Localidad:'.$alumno->getLocalidad().'</p>
                           
			<h4>Requisitos:</h4>
                        ';
                if($requisitos[0]==1){$op1='<p>Fotocopia Ci: <img src="../public/img/check.jpeg"/></p>';} else{ $op1='<p>Fotocopia Ci: <img src="../public/img/checkV.jpeg"/></p>';}	
		if($requisitos[1]==1){$op2='<p> Carnet salud vigente:  <img src="../public/img/check.jpeg"/></p>';}else{$op2='<p> Carnet salud vigente:  <img src="../public/img/checkV.jpeg"/></p>';}
                if($requisitos[2]==1){$op3='<p>Foto tipo carn&eacute;:  <img src="../public/img/check.jpeg"/></p>';}else{$op3='<p>Foto tipo carn&eacute;:  <img src="../public/img/checkV.jpeg"/></p>';}
                if($requisitos[3]==1){$op4='<p><span>Pase Secundaria (Formula 69-A) o pase de UTU con Bachillerato Tecnologico aprobado (verificado por R.E. UTU): <img src="../public/img/check.jpeg"/></span></p>';}else{$op4='<p><span>Pase Secundaria (Formula 69-A) o pase de UTU con Bachillerato Tecnologico aprobado (verificado por R.E. UTU): <img src="../public/img/checkV.jpeg"/></span></p>';}
	$footer='	
	</body>
</html>
';           $html=$html.$op1.$op2.$op3.$op4.$footer;
            return  $html;
                    
        }
}
