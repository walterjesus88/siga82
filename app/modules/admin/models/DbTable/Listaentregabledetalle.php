<?php
class Admin_Model_DbTable_Listaentregabledetalle extends Zend_Db_Table_Abstract
{
    protected $_name = 'lista_entregable_detalle';
    protected $_primary = array("codigo_prop_proy","proyectoid","revision_entregable","edt");


    public function _save($data){
        try{
            //if ($data['codigo_prop_proy']=="" ) return false;
            return $this->insert($data);
        }catch (Exception $ex){
            print "Error: Insertando un nueva lista_entregable_detalle".$ex->getMessage();
        }
    }


         /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("lista_entregable_detalle");
                else $select->from("lista_entregable_detalle",$attrib);
                foreach ($where as $atri=>$value){
                    $select->where("$atri = ?", $value);
                }
                if ($orders<>null || $orders<>"") {
                    if (is_array($orders))
                        $select->order($orders);
                }
                $results = $select->query();
                $rows = $results->fetchAll();
                //print_r($results);
                if ($rows) return $rows;
                return false;
        }catch (Exception $e){
            print "Error: Read Filter competencia ".$e->getMessage();
        }
    }

    //obtener los entregables de un proyecto
    public function _getEntregablexProyecto($proyectoid, $condicion)
    {
      try {
        $query1 = "select led.cod_le, led.proyectoid, led.revision_entregable,
        led.edt, led.tipo_documento, led.disciplina, led.codigo_anddes, led.codigo_cliente,
        led.descripcion_entregable, led.estado as estado_revision,
        det.transmittal, det.correlativo,
        tip.emitido_para as emitido, det.fecha, det.respuesta_transmittal, det.respuesta_emitido,
        det.respuesta_fecha, det.estado, det.comentario
        from lista_entregable_detalle as led left join detalle_transmittal as det
        on (led.cod_le = det.entregableid) left join tipo_envio as tip
        on (det.emitido = tip.codigo and det.tipo_envio = tip.tipo)
        where led.proyectoid = '".$proyectoid."'";
        if ($condicion == 'Ultimo') {
          $query1 = $query1." and led.estado = 'Ultimo'";
        }
        $sql = $this->_db->query($query1);
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    //guardar el codigo anddes
    public function _setCodigoAnddes($entregableid, $codigo_anddes)
    {
      $id = (int)$entregableid;
      $row = $this->fetchRow('cod_le = ' . $id);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->codigo_anddes = $codigo_anddes;
      $row->save();
    }

    //guardar el codigo anddes
    public function _setCodigoCliente($entregableid, $codigo_cliente)
    {
      $id = (int)$entregableid;
      $row = $this->fetchRow('cod_le = ' . $id);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->codigo_cliente = $codigo_cliente;
      $row->save();
    }

}