<?php
class Admin_Model_DbTable_Transmittal extends Zend_Db_Table_Abstract
{
    protected $_name = 'transmittal';
    protected $_primary = array("codificacion", "correlativo");

     //Lista todos los transmittals procesados
    public function _getAll(){
      try{
        $f = $this->fetchAll();
        if ($f) return $f->toArray ();
        return false;
      }catch (Exception $e){
        print "Error: Al momento de leer todos los transmittal".$e->getMessage();
      }
    }

    //Devuelve el numero correlativo a asignar al nuevo transmittal
    public function _getCorrelativo($proyectoid)
    {
      try {
        $sql = $this->_db->query("select cast(correlativo as int) from transmittal
        where proyectoid='".$proyectoid."' order by correlativo desc limit 1");
        $row = $sql->fetchAll();
        if (count($row) != 0) {
          $numero = (int) $row[0]['correlativo'];
          $cadena = (string) $numero + 1;
          if (strlen($cadena) == 1) {
            $respuesta['correlativo'] = '00'.$cadena;
          } elseif (strlen($cadena) == 2) {
            $respuesta['correlativo'] = '0'.$cadena;
          } elseif (strlen($cadena) == 3) {
            $respuesta['correlativo'] = $cadena;
          }

          return $respuesta;

        } else {
          $respuesta['correlativo'] = '001';
          return $respuesta;
        }

      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    //Almacena en la base de datos los valores de la configuracion del transmittal
    public function _saveConfiguracion($data)
    {
      /*$newRow = $this->createRow();
      $newRow->codificacion = $data['codificacion'];
      $newRow->correlativo = $data['correlativo'];
      $newRow->clienteid = $data['clienteid'];
      $newRow->proyectoid = $data['proyectoid'];
      $newRow->formato = $data['formato'];
      $newRow->tipo_envio = $data['tipo_envio'];
      $newRow->control_documentario = $data['control_documentario'];
      $newRow->dias_alerta = $data['dias_alerta'];
      $newRow->tipo_proyecto = $data['tipo_proyecto'];
      $newRow->atencion = $data['atencion'];
      $newRow->save();
      return $respuesta['resultado'] = 'guardado';*/

      try {
        $sql = $this->_db->query("insert into transmittal values ('".
        $data['codificacion']."', '".$data['correlativo']."', '".
        $data['clienteid']."', '".$data['proyectoid']."', '".$data['formato'].
        "', '".$data['tipo_envio']."', '".$data['control_documentario'].
        "', '".$data['dias_alerta']."', '".$data['tipo_proyecto']."', '".
        $data['atencion']."', '".$data['modo_envio']."')");
        $row = $sql->fetchAll();
        return $row;
        //$this->insert($data);
        //return $respuesta['resultado'] = 'guardado';
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    //Cambia el estado de elaboracion de un transmittal a cerrado
    public function _cambiarEstadoElaboracion($transmittalid)
    {
      $id = (int)$transmittalid;
      $row = $this->fetchRow('transmittalid = ' . $id);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->estado_elaboracion = 'emitido';
      $row->save();
    }

    //obtener los datos de un transmittal
    public function _getTransmittal($transmittalid, $correlativo)
    {
      $sql = $this->_db->query("select tra.codificacion, tra.correlativo,
      tra.clienteid, cli.nombre_comercial, tra.proyectoid, tra.formato,
      tra.tipo_envio, tra.control_documentario, tra.atencion,
      concat(con.nombre1, ' ', con.ape_paterno) as nombre_atencion,
      con.puesto_trabajo, tra.modo_envio
      from transmittal as tra inner join cliente as cli on
      tra.clienteid = cli.clienteid
      inner join contacto as con on (tra.atencion = con.contactoid and
      tra.clienteid = con.clienteid)
      where codificacion = '".$transmittalid."' and correlativo ='".$correlativo."'");
      $row = $sql->fetch();
      return $row;
    }

    //guardar el modo de envio
    public function _setModoEnvio($transmittal, $correlativo, $modo)
    {
      try {
        $row = $this->fetchRow("codificacion = '".$transmittal."' and correlativo = '".
        $correlativo."'");
        $row->modo_envio = $modo;
        $row->save();
        $respuesta['resultado'] = 'Guardado';
        return $respuesta;
      } catch (Exception $e) {
        print $e->getMessage();
      }


    }

}
