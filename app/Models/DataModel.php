<?php

namespace App\Models;

use CodeIgniter\Model;


class DataModel extends Model
{
    function __construct()
    {
        $this->db = \Config\Database::connect();
    }


    public function ReadCallCenterTronco($schema)
    {
        try {
            $sql = "SELECT tronco_id, tronco_nombre, estado FROM $schema.tronco;";
            $query = $this->db->query($sql);
            $resQuery = $query->getResultArray();
            $res = (count($resQuery) > 0) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return $e->getMessage();
            //die();
        }
    }

    public function ReadCallCenterRama($schema)
    {
        try {
            $sql = "SELECT r.rama_id, r.rama_nombre, r.estado, r.tronco_id, t.tronco_id, t.tronco_nombre FROM $schema.ramas r INNER JOIN $schema.tronco t ON t.tronco_id = r.tronco_id ORDER BY r.rama_nombre; ";
            $query = $this->db->query($sql);
            $resQuery = $query->getResultArray();
            $res = (count($resQuery) > 0) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return 0;
            //die($e->getMessage());
        }
    }

    public function ReadCallCenterHoja($schema)
    {
        try {
            $sql = "SELECT h.hoja_id, h.hoja_nombre, h.estado, h.tronco_id, t.tronco_id, t.tronco_nombre, t.tronco_id FROM $schema.hojas h INNER JOIN $schema.tronco t ON t.tronco_id = h.tronco_id;";
            $query = $this->db->query($sql);
            $resQuery = $query->getResultArray();
            $res = (count($resQuery) > 0) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return 0;
            //die($e->getMessage());
        }
    }
    public function ReadIdCallCenterSelHoja($schema)
    {
        try {
            $sql = "SELECT * FROM $schema.hojas;";
            $query = $this->db->query($sql);
            $resQuery = $query->getResultArray();
            $res = (count($resQuery) > 0) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return 0;
            //die($e->getMessage());
        }
    }

    public function ReadIdCallCenterSelRama($schema, $idtronco)
    {
        try {
            $sql = "SELECT * FROM $schema.ramas r WHERE r.tronco_id = $idtronco ORDER BY r.rama_nombre;";
            $query = $this->db->query($sql);
            $resQuery = $query->getResultArray();
            $res = (count($resQuery) > 0) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return 0;
            //die($e->getMessage());
        }
    }

    public function CreateCallCenterGestion($schema, $usuario_id, $usuario_nombre, $id_cliente, $selTelefonoGestion, $observacion, $cod_tipificacion, $tarea_id, $cuotas, $opcion_pago, $valor_pago, $fecha_promesa)
    {
        //return $fecha_promesa;
        try {
            $sql = "$fecha_promesa";
            if ($fecha_promesa == "-") {
                $sql = "INSERT INTO $schema.auto_gestiones_deudores(usuario_id, usuario_nombre, identificacion, telefono, cod_tipificacion, observaciones, tarea_id, cuotas, opcion_pago, valor_pago, fecha_promesa) VALUES ( $usuario_id, '$usuario_nombre', '$id_cliente', $selTelefonoGestion, '$cod_tipificacion', '$observacion', $tarea_id, $cuotas, $opcion_pago, $valor_pago, null);";
            } else {
                $sql = "INSERT INTO $schema.auto_gestiones_deudores(usuario_id, usuario_nombre, identificacion, telefono, cod_tipificacion, observaciones, tarea_id, cuotas, opcion_pago, valor_pago, fecha_promesa) VALUES ( $usuario_id, '$usuario_nombre', '$id_cliente', $selTelefonoGestion, '$cod_tipificacion', '$observacion', $tarea_id, $cuotas, $opcion_pago, $valor_pago, '$fecha_promesa');";
            }
            $query = $this->db->query($sql);
            $insert_id = $this->db->insertID();
            $resQuery = $this->db->affectedRows();
            $res = ($resQuery > 0) ? $insert_id : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return 0;
            //die($e->getMessage());
        }
    }
    public function ReadIdCallCenterRelacionTipificacion($schema, $cod_tipificacion)
    {
        try {
            $sql = "SELECT cod_tipificacion, ranking, tipo_contacto, tipo_efectividad, compromiso FROM $schema.tipificaciones WHERE cod_tipificacion = '$cod_tipificacion';";
            $query = $this->db->query($sql);
            $resQuery = $query->getRowArray();
            $res = ($resQuery > 0) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return 0;
            //die($e->getMessage());
        }
    }

    public function CreateCallCenterRelacionTipificacion($schema, $cod_tipificacion, $rankingNuevoTip, $tipoContactoNuevo, $tipoEfectividadNuevo, $compromisoNuevo)
    {
        try {
            $sql = "INSERT INTO $schema.tipificaciones (cod_tipificacion, ranking, tipo_contacto, tipo_efectividad, compromiso) VALUES ('$cod_tipificacion', '$rankingNuevoTip', '$tipoContactoNuevo', '$tipoEfectividadNuevo', '$compromisoNuevo');";
            $query = $this->db->query($sql);
            $resQuery = $this->db->affectedRows();
            $res = ($resQuery > 0) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            //return 0;
            die($e->getMessage());
        }
    }

    public function GetTroncoID($schema){

        try{
            $sql = "SELECT t.tronco_id FROM $schema.tronco t WHERE t.tronco_nombre = 'CONTACTADO DIRECTO';";
            $query = $this->db->query($sql);
            $resQuery = $query->getResultArray();
            $res = (count($resQuery) > 0) ? $resQuery : 0;
            return $resQuery;
        } catch(\Exception $e){
            //throw $th;
            //return 0;
            die($e->getMessage());
        }
    }
    public function GestionNueva($schema,$obligacion, $sel, $observacion, $acuerdo_pago,$contacto,$promesa)
    {
        try{
            $sql = "INSERT INTO  $schema.gestion_clientes (obligacion, nopago, observaciones, acuerdo_pago, contacto, promesa) 
            VALUES(:obligacion:, :nopago:, :observaciones:, :acuerdo_pago:, :contacto:, :promesa:);";
            $query = $this->db->query($sql, [
                'obligacion' => $obligacion,
                'nopago' => $sel,
                'observaciones' => $observacion,
                'acuerdo_pago' => $acuerdo_pago,
                'contacto' => $contacto,
                'promesa' => $promesa
            ]);
            $resQuery = $this->db->affectedRows();
            $res = ($resQuery > 0) ? $resQuery : 0;
            return $res;
        }
        catch (\Exception $e) {
            //throw $th;
            //die($e->getMessage());
            return 0;
        }
    }
}
