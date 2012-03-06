<?php

class Application_Service_Alumno extends Application_Service_AbstractService {
    private static $instance;

    private function __construct() {
        parent::__initResources();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Application_Service_Alumno();
        }

        return self::$instance;
    }
    
    public function updateRequisitos($alumnoId, $estado){
        $alumno = $this->entityManager->find('SGTi\Entity\Alumno', $alumnoId);
        $alumno->setReq($estado);
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
        public function listadoAsistencias($cursosAsistencia,$cursosTotalClases,$datosAlumno){
            $fecha = time (); 
            $html= '<html>
                        <head>
                            <title>Control de Asistencias</title>
                        </head>
                        <body>
                            <p><img style="width: 71px; height: 107px; alt="" src="http://www.marlund.com/wordpress/wp-content/uploads/2010/02/logo_udelar.jpg" /></p>
                            <p>
                                <h3><span style="font-weight: bold;">Control de Asistencias:</span></p></h3>
                            <p>
                                Nombre:'.$datosAlumno['nombre'].'</p>
                            <p>
                                Apellido:'.$datosAlumno['apellido'].'</p>
                            <p>
                                Fecha:'.date("j/n/Y",$fecha ).'</p>
                            <p>
                                Plan:'.$datosAlumno['plan'].'</p>
                            <p>
                                &nbsp;</p>
                            <table border="1" cellpadding="1" cellspacing="1" style="width: 500px;">
                                <tbody>
                                    <tr>
					<td>
						Curso&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>
						Cantidad de Faltas</td>
					<td>
						Cantidad de clases</td>
                                    </tr>';
                                    $tabla="";
                                    foreach($cursosAsistencia as $materia=>$falta){
                                        $tabla=$tabla.'<tr>
                                                    <td>'.$materia.'</td>
                                                    <td>'.$falta.'</td>
                                                    <td>'.$cursosTotalClases[$materia].'</td>
                                                </tr>';
                                        
                                    }     
                            
                               $footer='</tbody>
                        </table>
                </body>
        </html>
';
            $html=$html.$tabla.$footer;
            return $html;
        }
    }

?>
