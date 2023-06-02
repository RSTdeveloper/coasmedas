<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAutenticacion extends Model
{
    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getDeudorData($userDoc,$username,$schema)
    {        
        try {
            $sql = "SELECT identificacion, nombres
            FROM $schema.deudores
            WHERE identificacion LIKE '$userDoc' AND codigo = /*LIKE '%" . $this->db->escapeLikeString($username) . "%' ESCAPE '!'*/ $username ;";
            $query = $this->db->query($sql);
            $resQuery = $query->getRow();
            $res = (is_object($resQuery)) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return $e->getMessage();
            //die($e->getMessage());
        }
    }
    public function getDeudorOb($userDoc)
    {        
        try {
            $sql = "SELECT obligacion
            FROM coasmedaslanding.obligaciones
            WHERE identificacion LIKE '%$userDoc%';";
            $query = $this->db->query($sql);
            $resQuery = $query->getRow();
            $res = (is_object($resQuery)) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return $e->getMessage();
            //die($e->getMessage());
        }
    }

    public function getDeudorGestion($obligacion,$schema)
    {        
        try {
            $sql = "SELECT acuerdo_pago,fecha_gestion
            FROM $schema.gestion_clientes
            WHERE obligacion = '$obligacion';";
            $query = $this->db->query($sql);
            $resQuery = $query->getRow();
            $res = (is_object($resQuery)) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return $e->getMessage();
            //die($e->getMessage());
        }
    }
    public function getDeudorID($userDoc)
    {        
        try {
            $sql = "SELECT identificacion
            FROM coasmedaslanding.deudores
            WHERE identificacion LIKE '%$userDoc%';";
            $query = $this->db->query($sql);
            $resQuery = $query->getRow();
            $res = (is_object($resQuery)) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return $e->getMessage();
            //die($e->getMessage());
        }
    }

    public function getDeudorCodigo($username)
    {        
        try {
            $sql = "SELECT codigo
            FROM coasmedaslanding.deudores
            WHERE codigo = $username;";
            $query = $this->db->query($sql);
            $resQuery = $query->getRow();
            $res = (is_object($resQuery)) ? $resQuery : 0;
            return $res;
        } catch (\Exception $e) {
            //throw $th;
            return $e->getMessage();
            //die($e->getMessage());
        }
    }

    public function bitLogin($dato_existente,$dato_erroneo,$schema)
    {        
        try {                
            $sql = "INSERT INTO  $schema.bitlogin (dato_existente, dato_erroneo) 
            VALUES(:dato_existente:, :dato_erroneo:);";
            $query = $this->db->query($sql, [
                'dato_existente' => $dato_existente,
                'dato_erroneo' => $dato_erroneo,
            ]);
            $resQuery = $this->db->affectedRows();
            $res = ($resQuery > 0) ? $resQuery : 0;
            
        }
        catch (\Exception $e) {
            //throw $th;
            //die($e->getMessage());
            
        }
    }
}
