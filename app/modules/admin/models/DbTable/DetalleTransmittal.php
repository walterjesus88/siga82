<?php
class Admin_Model_DbTable_DetalleTransmittal extends Zend_Db_Table_Abstract
{
    protected $_name = 'detalle_transmittal';
    protected $_primary = array("detalleid");

    public function _getDetallexTramittal($transmittal, $correlativo)
    {
      try {
        $sql = $this->_db->query("select det.detalleid, led.descripcion_entregable,
        led.codigo_anddes, led.tipo_documento, det.revision, det.emitido
        from detalle_transmittal as det inner join lista_entregable_detalle as
        led on (det.entregableid = led.cod_le)
        where transmittal = '".$transmittal."' and correlativo = '".$correlativo."'");
        $rows = $sql->fetchAll();
        return $rows;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _addDetalle($data)
    {
      try {
        $sql = $this->_db->query("insert into detalle_transmittal
        (entregableid, tipo_envio, revision, estado_revision, transmittal, correlativo,
        emitido, fecha, estado) values (".$data['entregableid'].
        ", '".$data['tipo_envio']."', '".$data['revision']."', '".$data['estado_revision']."', '".
        $data['transmittal']."', '".$data['correlativo']."', '".$data['emitido'].
        "', '".$data['fecha']."', '".$data['estado']."')");
        $row = $sql->fetchAll();
        $respuesta['resultado'] = 'guardado';
        return $data;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _updateDetalle($data)
    {
      $id = (int)$data['detalleid'];
      $row = $this->fetchRow('detalleid = ' . $id);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->revision = '0';
      $row->save();
    }

    public function _deleteDetalle($detalleid)
    {
      $row = $this->fetchRow('detalleid = ' . $detalleid);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->delete();
    }

    public function _getDetalleSinRespuesta($proyectoid)
    {
      try {
        $sql = $this->_db->query("select det.detalleid, led.codigo_anddes,
        led.codigo_cliente, det.revision from detalle_transmittal as det inner join
        lista_entregable_detalle as led on det.entregableid =
        led.cod_le where led.proyectoid = '".$proyectoid."' and
        (det.respuesta_transmittal is null or det.respuesta_transmittal = '')");
        $rows = $sql->fetchAll();
        return $rows;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getDetallesConRespuesta($proyectoid)
    {
      try {
        $sql = $this->_db->query("select det.detalleid, det.respuesta_transmittal
        as transmittal, led.codigo_anddes, led.codigo_cliente,
        led.descripcion_entregable as descripcion, led.revision_entregable as
        revision, det.respuesta_emitido as emitido, det.respuesta_fecha as fecha
        from detalle_transmittal as det
        inner join lista_entregable_detalle as led on det.entregableid =
        led.cod_le where det.respuesta_transmittal is not null");
        $rows = $sql->fetchAll();
        return $rows;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    //guardar las respuestas emitidas por el cliente
    public function _setRespuesta($data)
    {
      try {
        $estado = 'Aprobado';
        $sql = $this->_db->query("update detalle_transmittal set
        respuesta_transmittal = '".$data['respuesta_transmittal']."',
        respuesta_emitido = '".$data['respuesta_emitido']."',
        respuesta_fecha = '".$data['respuesta_fecha']."',
        estado = '".$estado."' where detalleid =".
        $data['detalleid']);
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    //editar respuestas
    public function _updateRespuesta($data)
    {
      try {
        $estado = 'Aprobado';
        $row = $this->fetchRow("detalleid = ".$data['detalleid']);
        $row->respuesta_transmittal = $data['respuesta_transmittal'];
        $row->respuesta_emitido = $data['respuesta_emitido'];
        $row->respuesta_fecha = $data['respuesta_fecha'];
        $row->estado = $estado;
        $row->save();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _deleteRespuesta($detalleid)
    {
      $sql = $this->_db->query("update detalle_transmittal set
      respuesta_transmittal = null, respuesta_emitido = null,
      respuesta_fecha = null, estado = 'Pendiente' where detalleid = ".$detalleid);
      $row = $sql->fetchAll();
      return $row;
    }

    public function _getDetallesConRespuestaExtendido($proyectoid)
    {
      try {
        $sql = $this->_db->query("select det.detalleid, det.respuesta_transmittal
        as transmittal, led.codigo_anddes, led.codigo_cliente,
        led.descripcion_entregable as descripcion, led.revision_entregable as
        revision, tip.emitido_para as emitido, det.respuesta_fecha as fecha
        from detalle_transmittal as det
        inner join lista_entregable_detalle as led on det.entregableid =
        led.cod_le inner join tipo_envio as tip on (det.respuesta_emitido = tip.codigo
        and tip.tipo = 'ANDDES')
        where det.respuesta_transmittal is not null");
        $rows = $sql->fetchAll();
        return $rows;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }
}
