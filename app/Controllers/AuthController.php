<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

use App\Models\ModelMaster;
use App\Models\ModelAutenticacion;
use App\Libraries\Nativesession;
use App\Models\DataModel;
use CodeIgniter\HTTP\Message;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\UpperCaseConstantSniff;
use PhpParser\Node\Stmt\Echo_;

class AuthController extends BaseController
{
    function __construct()
    {
        $this->modelMaster = new ModelMaster();
        $this->modelAutenticacion = new ModelAutenticacion();
        $this->dataModel = new DataModel();
        $this->nativesession = new Nativesession();
    }

    public function index()
    {
        //return view('Login');

        if ($this->nativesession->get('logged_in')) {
            echo "index master";
            return redirect()->to(base_url('/master'));
        } else {
            return view('Login');
        }
    }

    public function ValidarSesion()
    {
        if ($this->nativesession->get('logged_in')) {
            echo json_encode('1');
        } else {
            echo json_encode('0');
        }
    }



    public function RecoveryAdmin()
    {
        return view('RecoveryAdmin');
    }

    public function Recovery()
    {
        return view('Recovery');
    }

    //--------------------------------------------------------------------

    public function Login()
    {


        /**
         * validar la existencia del usuario en la base de datos antes
         * de continuar con la ejecución de la consulta
         */
        $identificacion = $this->request->getPost('identificacion');
        $username = $this->request->getPost('username');
        //$captcha = $this->request->getPost('validation');

        //$username = strtoupper($username);
       
        $schema = 'coasmedaslanding';
        $data1 = $this->modelAutenticacion->getDeudorID($identificacion);
        $data2 = $this->modelAutenticacion->getDeudorCodigo($username);
        //$data3 = $this->modelAutenticacion->getDeudorOb($identificacion);        
        //$data3=current($data3);        
        //$data4 = $this->modelAutenticacion->getDeudorGestion($data3,$schema);

        
        if ($data1 == '0' && $data2=='0') {
            $resdata = array('type' => 'error', 'message' => 'No existe registro en base de datos');
            $datas = $this->modelAutenticacion->bitLogin('Cedula Erronea','Codigo Erroneo',$schema);
            echo json_encode($resdata);
            die();
        }
        if ($data1 == '0' && $data2!='0') {
            $resdata = array('type' => 'error', 'message' => 'Valide la cedula digitada');
            $datas = $this->modelAutenticacion->bitLogin($username,'Cedula Erronea',$schema);
            echo json_encode($resdata);
            die();
        }
            
        if ($data2 == '0' && $data1 != '0') {
            $resdata = array('type' => 'error', 'message' => 'Valide el codigo digitado');
            $datas = $this->modelAutenticacion->bitLogin($identificacion,'Codigo Erroneo',$schema);
            echo json_encode($resdata);
            die();
        } 
        
        // if($data4 != 0){  
        //     $fecha = date('Y-m-d');
        //     $fecha = $fecha . ' 00:00:00';          
        //     $fecha_gest = $data4->fecha_gestion;
        //     $data5 = $data4->acuerdo_pago;
        //     if ($data5 == 'Si' && $fecha_gest == $fecha)
        //     {
        //         $resdata = array('type' => 'error', 'message' => 'Ya tiene un acuerdo registrado por favor vuelva mañana');            
        //         echo json_encode($resdata); 
        //         die();   
        //     } 
        //     if( $data5=='No' && $fecha_gest == $fecha) 
        //     {            
        //         $resdata = array('type' => 'error', 'message' => 'Esta programada su llamada con un asesor');            
        //         echo json_encode($resdata); 
        //         die();     
        //     } 
        //     die();   
        // }
        
        // // if ($captcha == 'true') {


            $data = $this->modelAutenticacion->getDeudorData($identificacion, $username, $schema);
            //echo json_encode($data);
            if ($data == '0') {
                $resdata = array('type' => 'error', 'message' => 'Datos Incorrectos');
                echo json_encode($resdata);
                die();
            }
               
             
    
            if (strlen($identificacion) < 6 || strlen($username) < 3) {
                $resdata = array('type' => 'error', 'message' => 'Valide el codigo enviado en el SMS');
                echo json_encode($resdata);
                die();
            }
            $resdata = array('type' => 'success', 'message' => $username);

            $this->nativesession->set('schema', $schema);
            $this->nativesession->set('UsuarioId', 1);
            $this->nativesession->set('Identificacion', $data->identificacion);
            $this->nativesession->set('nombre_usuario', 'Auto-Gestion');
            $this->nativesession->set('Nombres', $data->nombres);
            $this->nativesession->set('logged_in', TRUE);
            echo json_encode($resdata);
        // } else {
        //     $resdata = array('type' => 'error', 'message' => 'no has marcado todo', 'captcha' => $captcha);
        //     echo json_encode($resdata);
        //     die();
        // }
    }

    public function Logout()
    {
        //$fecha = date('Y-m-d H:i:s');
        //$sesionAccion = 2;
        //$identificacion = $this->nativesession->get('Identificacion');
        //$this->modelMaster->LogUsuario($identificacion, $fecha, $sesionAccion);

        $this->nativesession->destroy();

        return redirect()->to(base_url('/'));
    }

    public function ChangePasswordAdmin()
    {
        $fecha = date('Y-m-d H:i:s');
        $idUser = $this->request->getPost('idUser');
        $password = trim($this->request->getPost('password'));
        $repassword = trim($this->request->getPost('repassword'));

        if (empty($password) || empty($repassword)) {
            $resdata = array('type' => 'error', 'message' => 'Debe de diligenciar todos los campos.');
            echo json_encode($resdata);
            die();
        }

        if ($password != $repassword) {
            $resdata = array('type' => 'error', 'message' => 'Contraseñas no son iguales, vuelva a intentar.');
            echo json_encode($resdata);
            die();
        }

        $opciones = [
            'cost' => 12,
        ];

        $password = password_hash($password, PASSWORD_BCRYPT, $opciones);

        $data = $this->modelAutenticacion->ChangePasswordUserAdmin($idUser, $password, $fecha);

        $resdata = ($data > 0) ? array('type' => 'success', 'message' => 'Se cambio contraseña con exito.') : array('type' => 'error', 'message' => 'Error al cambiar contraseña.');
        echo json_encode($resdata);
    }

    public function RecoverPasswordAdmin() // Recuperar password basado en correo electronico
    {
        $identificacion = $this->request->getPost('identificacion');

        $data = $this->modelAutenticacion->ValidateUserAdmin($identificacion);

        if ($data == '0') {
            $resdata = array('type' => 'error', 'message' => 'Error al enviar correo de recuperación, valide y vuelva a intentar.');
            echo json_encode($resdata);
            die();
        }

        $correo_corporativo = $data->email_corporativo;
        $correo_corporativo_coordinador = $data->email_corporativo_coordinador;

        if ($correo_corporativo == '' || $correo_corporativo == NULL) {
            $to = $correo_corporativo_coordinador;
        } else {
            $to = $correo_corporativo;
        }

        //$iduser = $data->usuario_id;
        $iduser = $data->identificacion;
        $timeActual = new Time('now');
        $timeActual = $timeActual->getTimestamp();

        $tokenString = "$iduser&$timeActual";
        $token = base64_encode($tokenString);

        $subject = 'Recuperar contraseña';

        $message = '<h2>Solicitaste una recuperación de contraseña!</h2>
        <p>Acontinuaciónn encontrara un link para restablecer tú contraseña.</p>
        <p>Recuerde que solo tu debes de hacer uso de esta información.</p>
        <a href="' . base_url() . '/recovery?t=' . $token . '">Reiniciar Contraseña</a>';

        $email = \Config\Services::email();

        $email->setTo($to);
        $email->setFrom('technology@rstasociados.com.co', 'no-reply');

        $email->setSubject($subject);
        $email->setMessage($message);

        if ($email->send()) {
            $resdata = array('type' => 'success', 'message' => 'Se envio correo con exito.');
            echo json_encode($resdata);
        } else {
            $data = $email->printDebugger(['headers']);
            $resdata = array('type' => 'error', 'message' => $data);
            echo json_encode($resdata);
        }
    }

    public function ChangePasswordRecovery()
    {
        $fecha = date('Y-m-d H:i:s');
        $token = $this->request->getPost('token');
        $password = trim($this->request->getPost('nuevo_password'));
        $repassword = trim($this->request->getPost('confirmar_password'));

        $tokenDecode = base64_decode($token);

        $textExplode = explode('&', $tokenDecode);
        $idUser = $textExplode[0];
        $timeStamp = $textExplode[1];

        $dateFormat = date('y-m-d H:i:s', $timeStamp);
        $dateToken = new Time($dateFormat);
        $dateActual = new Time('now');

        $dateDiff = $dateToken->diff($dateActual);

        if (empty($password) || empty($repassword)) {
            $resdata = array('type' => 'error', 'message' => 'Debe de diligenciar todos los campos.');
            echo json_encode($resdata);
            die();
        }

        if ($dateDiff->i > 10) {
            $resdata = array('type' => 'error', 'message' => 'Se ha agotado el tiempo para reestabler la contraseña, vuelva a intentar.');
            echo json_encode($resdata);
            die();
        }

        if ($password != $repassword) {
            $resdata = array('type' => 'error', 'message' => 'Contraseñas no son iguales, vuelva a intentar.');
            echo json_encode($resdata);
            die();
        }

        $opciones = [
            'cost' => 12,
        ];

        $password = password_hash($password, PASSWORD_BCRYPT, $opciones);

        $data = $this->modelAutenticacion->ChangePasswordUserAdmin($idUser, $password, $fecha);

        $resdata = ($data > 0) ? array('type' => 'success', 'message' => 'Se cambio contraseña con exito, ahora puedes ingresar sin problemas.') : array('type' => 'error', 'message' => 'Error al cambiar contraseña.');
        echo json_encode($resdata);
    }
}
