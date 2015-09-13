<?php
class Admin_Model_DbTable_DetalleTransmittal extends Zend_Db_Table_Abstract
{
    protected $_name = 'detalle_transmittal';
    protected $_primary = array("detalleid");

    public function _getAll()
    {
      try {
        $rows = $this->fetchAll();
        return $rows->toArray();
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _getDetallexTramittal($transmittal, $correlativo)
    {
      try {
        $sql = $this->_db->query("select det.detalleid, led.descripcion_entregable,
        led.codigo_anddes, led.tipo_documento, det.revision, det.emitido, det.cantidad
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
        "', '".$data['fecha']."', 'estado')");
        $row = $sql->fetchAll();
        $lista = $this->_getAll();
        $resp = [];
        $i = 0;
        foreach ($lista as $det) {
          $resp[$i] = $this->_setEstado($det['detalleid']);
          $i++;
        }
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
      $row->emitido = $data['emitido'];
      $row->fecha = $data['fecha'];
      $row->cantidad = $data['cantidad'];
      $row->save();
      $row = $this->_setEstado($data['detalleid']);
      return $row;
    }

    public function _deleteDetalle($detalleid)
    {
      $row = $this->fetchRow('detalleid = ' . $detalleid);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->delete();
      return $row;
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
        $row = $this->_setEstado($data['detalleid']);
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
        $row = $this->_setEstado($data['detalleid']);
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
      $row = $this->_setEstado($detalleid);
      return $row;
    }

    public function _getDetalleSinRespuesta($proyectoid)
    {
      try {
        $sql = $this->_db->query("select det.detalleid, led.codigo_anddes,
        led.codigo_cliente, det.revision, led.descripcion_entregable
        from detalle_transmittal as det inner join
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
        led.descripcion_entregable as descripcion, det.revision,
        det.respuesta_emitido as emitido, det.respuesta_fecha as fecha
        from detalle_transmittal as det
        inner join lista_entregable_detalle as led on det.entregableid =
        led.cod_le where det.respuesta_transmittal is not null");
        $rows = $sql->fetchAll();
        return $rows;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _getDetallesConRespuestaExtendido($proyectoid)
    {
      try {
        $sql = $this->_db->query("select det.detalleid, det.respuesta_transmittal
        as transmittal, led.codigo_anddes, led.codigo_cliente,
        led.descripcion_entregable as descripcion, det.revision,
        tip.emitido_para as emitido, det.respuesta_fecha as fecha
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

    public function _getDetallesxProyecto($proyectoid)
    {
      try {
        $sql = $this->_db->query("select det.detalleid, det.transmittal,
        det.correlativo, led.codigo_anddes, led.codigo_cliente, led.descripcion_entregable,
        det.revision as revision_entregable, led.estado as estado_revision, det.emitido,
        det.fecha, tra.estado_elaboracion, det.cantidad from detalle_transmittal as det inner join
        lista_entregable_detalle as led on (led.cod_le = det.entregableid)
        inner join transmittal as tra on (tra.codificacion = det.transmittal and
        tra.correlativo = det.correlativo)
        where tra.proyectoid = '".$proyectoid."' order by det.transmittal, det.correlativo");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getDatosContactoxDetalle($detalleid)
    {
      try {
        $sql = $this->_db->query("select tra.atencion as codigo,
        concat(con.nombre1, ' ', con.ape_paterno) as nombre,
        con.puesto_trabajo as area, con.correo from detalle_transmittal as det
        inner join transmittal as tra on (det.transmittal = tra.codificacion and
        det.correlativo = tra.correlativo) inner join contacto as con
        on (tra.clienteid = con.clienteid and tra.atencion = con.contactoid)
        where det.detalleid = ".$detalleid);
        $row = $sql->fetch();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _getModoEnvio($detalleid)
    {
      try {
        $sql = $this->_db->query("select tra.modo_envio as modo from transmittal
        as tra inner join detalle_transmittal as det
        on (tra.codificacion = det.transmittal and tra.correlativo = det.correlativo)
        where det.detalleid = ".$detalleid);
        $row = $sql->fetch();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _setEstado($detalleid)
    {
      try {
        $row = $this->fetchRow('detalleid = ' . $detalleid);
        $data['formato'] = 'ANDDES';
        $data['emitido'] = $row->emitido;
        $data['revision'] = $row->revision;
        $data['respuesta_emitido'] = $row->respuesta_emitido;
        $estado = $this->_calcularEstado($data);
        $row->temporal1 = $estado['temporal1'];
        $row->temporal2 = $estado['temporal2'];
        $row->estado = $estado['estado'];
        $row->comentario = $estado['temporal2'];
        $row->save();
        return $row;
      } catch (Exception $e) {
        print $e-> getMessage();
      }

    }

    protected function _calcularEstado($data)
    {
      if ($data['formato'] == 'ANDDES') {
        if ($data['emitido'] == 'A' || $data['emitido'] == 'B' || $data['emitido'] == 'C') {
          if ($data['revision'] == 'SR' && $data['respuesta_emitido'] == 'B') {
            $respuesta['temporal1'] = 'Sin respuesta';
            $respuesta['estado'] = 'Cerrado';
            $respuesta['temporal2'] = 'Informativo';
          } elseif ($data['revision'] == 'A') {
              $respuesta['temporal1'] = 'Revisión Interna';
              $respuesta['estado'] = 'Revisión Interna';
              $respuesta['temporal2'] = 'Revisión Interna';
          } elseif (ord($data['revision']) >= ord('B') && ord($data['revision']) <= ord('Z')) {
            if ($data['respuesta_emitido'] == '') {
              $respuesta['temporal1'] = 'Sin respuesta';
              $respuesta['estado'] = 'Pendiente x CLI';
              $respuesta['temporal2'] = 'Cálculo de N° días';
            } elseif ($data['respuesta_emitido'] == 'AP' || $data['respuesta_emitido'] == 'AC') {
              $respuesta['temporal1'] = 'Aprobado';
              $respuesta['estado'] = 'Pendiente x AND';
              $respuesta['temporal2'] = 'Emitir en 0';
            } elseif ($data['respuesta_emitido'] == 'NA') {
              $respuesta['temporal1'] = 'No aprobado';
              $respuesta['estado'] = 'Pendiente x AND';
              $respuesta['temporal2'] = 'Emitir en '.chr(ord($data['revision']) + 1);
            }
          } elseif (ord($data['revision']) >= ord('0') && ord($data['revision']) <= ord('9')) {
            if ($data['respuesta_emitido'] == '') {
              if ($data['emitido'] == 'A') {
                $respuesta['temporal1'] = 'Sin respuesta';
                $respuesta['estado'] = 'Pendiente x CLI';
                $respuesta['temporal2'] = 'Cálculo de N° días';
              } elseif ($data['emitido'] == 'B' || $data['emitido'] == 'C') {
                $respuesta['temporal1'] = 'Sin respuesta';
                $respuesta['estado'] = 'Cerrado';
                $respuesta['temporal2'] = '';
              }
            } elseif ($data['respuesta_emitido'] == 'AP') {
              $respuesta['temporal1'] = 'Aprobado';
              $respuesta['estado'] = 'Cerrado';
              $respuesta['temporal2'] = '';
            } elseif ($data['respuesta_emitido'] == 'AC') {
              $respuesta['temporal1'] = 'Aprobado';
              $respuesta['estado'] = 'Pendiente x AND';
              $respuesta['temporal2'] = 'Emitir en '.chr(ord($data['revision']) + 1);
            } elseif ($data['respuesta_emitido'] == 'NA') {
              $respuesta['temporal1'] = 'No Aprobado';
              $respuesta['estado'] = 'Pendiente x AND';
              $respuesta['temporal2'] = 'Emitir en '.chr(ord($data['revision']) + 1);
            }
          }
        } else {
          $respuesta['temporal1'] = '';
          $respuesta['estado'] = '';
          $respuesta['temporal2'] = '';
        }
      } else {
        $respuesta['temporal1'] = '';
        $respuesta['estado'] = '';
        $respuesta['temporal2'] = '';
      }
      return $respuesta;
    }
}
