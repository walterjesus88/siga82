<?php
class Admin_Model_DbTable_DetalleTransmittal extends Zend_Db_Table_Abstract
{
    protected $_name = 'detalle_transmittal';
    protected $_primary = array("detalleid");

    public function _getDetallexTramittal($transmittalid)
    {
      $id = (int)$transmittalid;
      $rows = $this->fetchAll('transmittalid = ' . $id);
      if (!$rows) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      return $rows->toArray();
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

}
