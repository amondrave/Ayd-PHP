<?php

require_once 'models/tipodocumentomodel.php';
require_once 'models/personamodel.php';
require_once 'models/estudiantemodel.php';
require_once 'models/profesormodel.php';
require_once 'entities/Persona.php';


class IndexController extends Controller{

    protected $tipoDocumentoModel;
    protected $personaModel;
    private $profesorModel;
    private $estudianteModel;

    public function __construct(){
        $this->personaModel = $this->model('persona');
        $this->profesorModel = new ProfesorModel();
        $this->estudianteModel = new EstudianteModel();
    }

    public function actionIndex(){
        $datos = ["titulo"=>"Inicio de sesión"];
        $this->view('index',$datos);
    }

    public function actionRegistro(){
        $documentos = $this->obtenerDocumentos();
        $datos = ["titulo" => "Registrese","documentos"=>$documentos ];
        $this->view('registro',$datos);
    }

    public function actionError(){
        $datos = ["titlo" => 'error'];
        $this->view('error',$datos);
    }

    public function actionAdmin(){
        $datos = ['titulo' => 'Administrador'];
        $this->view('login',$datos);
    }

    public function obtenerDocumentos(){
        $this->tipoDocumentoModel = new TipoDocumentoModel();
        return $this->tipoDocumentoModel->obtenerTodos();
    }

    public function actionInsertar(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            if(isset($_POST['tipo'],$_POST['documento'],$_POST['nombres'],$_POST['apellido1'],$_POST['correo'],$_POST['fecha_nacimiento'],$_POST['tipo_documento'],$_POST['contra'],$_POST['codigo'])){
                
                    $documento = $_POST['documento'];
                    $nombres = $_POST['nombres'];
                    $apellido1 = $_POST['apellido1'];
                    $correo = $_POST['correo'];
                    $fecha = $_POST['fecha_nacimiento'];
                    $contrasena = $_POST['contra'];
                    $tipoDocumento = $_POST['tipo_documento'];
                    $tipo = $_POST['tipo'];
                    $persona = new Persona($documento,$nombres,$apellido1,null,$correo,$fecha,0000,$tipoDocumento,$contrasena);
                    if($this->personaModel->insertar($persona)){
                        if($tipo="profesor"){
                             require_once 'entities/Profesor.php';
                             $codigo = $_POST['codigo'];
                             $profesor = new Profesor($codigo,$documento);
                             $this->profesorModel->insertar($profesor);
                        }else{
                            require_once 'entities/Estudiante.php';
                            $codigo = $_POST['codigo'];
                            $carrera = 115;
                            $estudiante = new Estudiante($documento,$codigo,$carrera);
                            $this->estudianteModel->insertar($estudiante);
                        }
                        $this->actionIndex();
                    }else{
                          echo("<p>No se pudo insertar chamo</p>");
                          $datos = ["mensaje" => "Error a ingresar"];
                          $this->view('error',$datos);
                    }
            }else{
                echo("<p>No sirve</p>");
            }
        }else{
            echo("<p>No esta entrando</p>");
        }
    }


}

?>