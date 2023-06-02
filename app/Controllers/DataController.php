<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Nativesession;
use App\Models\ModelMaster;
use App\Models\DataModel;
use mysqli;

class DataController extends BaseController
{
    function __construct()
    {
        $this->modelMaster = new ModelMaster();
        $this->dataModel = new DataModel();
        $this->nativesession = new Nativesession();
    }
    public function ReadCallCenterConsultarObligacionesCliente()
    {
        $schema = $this->nativesession->get('schema');
        $id_cliente = $this->request->getGet('id_cliente');

        /*$columnObligacion = $this->modelMaster->ReadCallCenterAsesorColumnaOblicacion($schema);*/
        $campoObligacion = 'obligacion' /*$columnObligacion->nombre_campo*/;



        $dataCliente = $this->modelMaster->ReadCallCenterConsultarObligacionesCliente($schema, $campoObligacion, $id_cliente);
        $resdata = ($dataCliente > 0) ? array('type' => 'success', 'message' => $dataCliente) : array('type' => 'error', 'message' => 'Sin datos para listar');
        echo json_encode($resdata);

        // $link = mysqli_connect("172.16.40.29", "root", "");
        // mysqli_select_db($link, "callcenter");
  
        // if ($link === false) {
        //     die("ERROR: Could not connect. ".mysqli_connect_error());
        // }
        
        // $sql = "SELECT * FROM clientes";
        // $res = mysqli_query($link, $sql);
        // mysqli_free_result($res);
        // echo json_encode( $res);
        // mysqli_close($link);

    }
    public function ReadCallCenterConsultarObligaciones()
    {
        $schema = $this->nativesession->get('schema');
        $id_cliente = $this->request->getGet('id_cliente');

        /*$columnObligacion = $this->modelMaster->ReadCallCenterAsesorColumnaOblicacion($schema);*/
        $campoObligacion = 'obligacion' /*$columnObligacion->nombre_campo*/;



        $dataCliente = $this->modelMaster->ReadCallCenterConsultarObligaciones($schema, $campoObligacion, $id_cliente);
        $resdata = ($dataCliente > 0) ? array('type' => 'success', 'message' => $dataCliente) : array('type' => 'error', 'message' => 'Sin datos para listar');
        echo json_encode($resdata);
    }


    public function ReadCallCenterTronco()
    {

        /*if ($this->nativesession->get('RolId') == '1' || $this->nativesession->get('RolId') == '2') {
            $und_negocio = $this->nativesession->get('und_negocio');
            $schema =  $und_negocio[0]['esquema'];
        } else {
            $schema = $this->nativesession->get('schema');
        }*/
        $schema = $this->nativesession->get('schema');

        $data = $this->dataModel->ReadCallCenterTronco($schema);
        $resdata = ($data > 0) ? array('type' => 'success', 'message' => $data) : array('type' => 'error', 'message' => 'Sin datos para listar.');
        echo json_encode($resdata);
    }

    public function ReadCallCenterRama()
    {
        $schema = $this->nativesession->get('schema');


        $data = $this->dataModel->ReadCallCenterRama($schema);
        $resdata = ($data > 0) ? array('type' => 'success', 'message' => $data) : array('type' => 'error', 'message' => 'Sin datos para listar.');
        echo json_encode($resdata);
    }

    public function ReadCallCenterHoja()
    {
        /*if ($this->nativesession->get('RolId') == '1') {
            $und_negocio = $this->nativesession->get('und_negocio');
            $schema = $und_negocio[0]['esquema'];
        } else {
            $schema = $this->nativesession->get('schema');
        }*/
        $schema = $this->nativesession->get('schema');

        $data = $this->dataModel->ReadCallCenterHoja($schema);
        $resdata = ($data > 0) ? array('type' => 'success', 'message' => $data) : array('type' => 'error', 'message' => 'Sin datos para listar.');
        echo json_encode($resdata);
    }
    public function ReadIdCallCenterSelHoja()
    {
        $schema = $this->nativesession->get('schema');

        $data = $this->dataModel->ReadIdCallCenterSelHoja($schema);
        $resdata = ($data > 0) ? array('type' => 'success', 'message' => $data) : array('type' => 'error', 'message' => 'Sin datos para listar.');
        echo json_encode($resdata);
    }


    public function ReadIdCallCenterSelRama()
    {
        $idtronco = $this->request->getGet('idtronco');

        $schema = $this->nativesession->get('schema');


        $data = $this->dataModel->ReadIdCallCenterSelRama($schema, $idtronco);
        $resdata = ($data > 0) ? array('type' => 'success', 'message' => $data) : array('type' => 'error', 'message' => 'Sin datos para listar.');
        echo json_encode($resdata);
    }
    public function CreateCallCenterGestion()
    {

        $fecha = date('Y-m-d');
        $usuario_id = $this->nativesession->get('UsuarioId');
        $usuario_nombre = $this->nativesession->get('nombre_usuario');
        $id_cliente = $this->nativesession->get('Identificacion');
        $selTroncoGestion = $this->request->getPost('selTroncoGestion');
        $selRamaGestion = $this->request->getPost('selRamaGestion');
        $selHojaGestion = $this->request->getPost('selHojaGestion');
        $selTelefonoGestion = '0';
        $observacion = trim($this->request->getPost('observacion'));
        $cadenalimpia = preg_replace("[\n|\r|\n\r]", " ", $observacion);
        $cuotas = $this->request->getPost('cuotas')  ;
        $tipoContactoNuevo = 'Contacto';
        $tipoEfectividadNuevo = 'Efectivo';
        $compromisoNuevo = '1';
        $opcion_pago = $this->request->getPost('opcion_pago')  ;
        $valor_pago = $this->request->getPost('valor_pago')  ;
        $fecha_promesa = $this->request->getPost('fecha_promesa');

        $und_negocio = $this->nativesession->get('und_negocio');
        $schema = $this->nativesession->get('schema');

        if ($selHojaGestion == "") {
            $cod_tipificacion = '1' . '-' . $selRamaGestion;
        } else {
            $cod_tipificacion = '1' . '-' . $selRamaGestion . '-' . $selHojaGestion;
        }
        //echo json_encode($cadenalimpia);

        $tarea_id = '2'; //Este dato se debe cambiara despues para que quede dinamico

        $dataTipificacion = $this->dataModel->ReadIdCallCenterRelacionTipificacion($schema, $cod_tipificacion);
        //echo json_encode($dataTipificacion);
        if ($dataTipificacion == 0) {

            $rankingNuevoTip = rand(0, 1000);
            $data = $this->dataModel->CreateCallCenterRelacionTipificacion($schema, $cod_tipificacion, $rankingNuevoTip, $tipoContactoNuevo, $tipoEfectividadNuevo, $compromisoNuevo);
            $dataGestion = $this->dataModel->CreateCallCenterGestion($schema, $usuario_id, $usuario_nombre, $id_cliente, $selTelefonoGestion, $cadenalimpia, $cod_tipificacion, $tarea_id, $cuotas, $opcion_pago, $valor_pago, $fecha_promesa);
            if ($dataGestion == 0) {
                $resdata = array('type' => 'error', 'message' => 'Error al registrar gestion. count + '.$dataGestion);
                echo json_encode($resdata);
                die();
            } else {
                $dataGestion = ($dataGestion > 0) ? array('type' => 'success', 'message' => 'Registro con exito.','data0'=>$dataGestion) : array('type' => 'error', 'message' => 'Error al registrar. tepe');
            }
            //echo json_encode($data.='test');
            $resdata = ($data > 0) ? array('type' => 'success', 'message' => 'Registro con exito.','data1'=>$dataGestion) : array('type' => 'error', 'message' => 'Error al registrar.tepe2');
            echo json_encode($resdata);
        } else {
            $dataGestion = $this->dataModel->CreateCallCenterGestion($schema, $usuario_id, $usuario_nombre, $id_cliente, $selTelefonoGestion, $cadenalimpia, $cod_tipificacion, $tarea_id, $cuotas, $opcion_pago, $valor_pago, $fecha_promesa);
            if ($dataGestion == 0) {
                $resdata = array('type' => 'error', 'message' => 'Error al registrar gestion. count 0');
                echo json_encode($resdata);
                die();
            } else {
                $dataGestion = ($dataGestion > 0) ? array('type' => 'success', 'message' => 'Registro con exito.','data2'=>$dataGestion) : array('type' => 'error', 'message' => 'Error al registrar.');
            }
            echo json_encode($dataGestion);
            return; 
        }
    }

    public function GestionNueva()
    {
        $schema = $this->nativesession->get('schema');
        $obligacion = $this->request->getPost('obligacion');
        $sel = $this->request->getPost('sel');
        $observacion = $this->request->getPost('observacion');
        $acuerdo_pago = $this->request->getPost('acuerdo_pago');
        $contacto = $this->request->getPost('contacto');
        $promesa = $this->request->getPost('promesa');

        $data = $this->dataModel->GestionNueva( $schema,$obligacion,$sel,$observacion,$acuerdo_pago,$contacto,$promesa);
        $resdata = ($data > 0) ? array('type' => 'success', 'message' => 'Modificcion con exito.') : array('type' => 'error', 'message' => 'Error al Modificar.');
        echo json_encode($resdata);
    }
}
