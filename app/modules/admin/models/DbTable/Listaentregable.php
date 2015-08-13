<?php
class Admin_Model_DbTable_Listaentregable extends Zend_Db_Table_Abstract
{
    protected $_name = 'lista_entregable';
    protected $_primary = array("codigo_prop_proy","proyectoid","revision_proyecto","edt");

    public function _save($data){
        try{
            //if ($data['codigo_prop_proy']=="" ) return false;
            return $this->insert($data);
        }catch (Exception $ex){
            print "Error: Insertando un nuevo Proyecto".$ex->getMessage();
        }
    }


         /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("lista_entregable");
                else $select->from("lista_entregable",$attrib);
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
    public function _getEntregablexProyecto($proyectoid)
    {
      try {
        $sql = $this->_db->query("select ent.cod_le, ent.edt, ent.tipo_documento,
        ent.disciplina, ent.codigo_anddes, ent.codigo_cliente, ent.descripcion_entregable,
        ent.revision, ent.estado_revision, det.transmittal, det.correlativo,
        tip.emitido_para as emitido, det.fecha, det.respuesta_transmittal, det.respuesta_emitido,
        det.respuesta_fecha, det.estado, det.comentario
        from lista_entregable as ent left join detalle_transmittal as det
        on (ent.cod_le = det.entregableid) inner join tipo_envio as tip
        on (det.emitido=tip.codigo and det.tipo_envio=tip.tipo)
        where ent.proyectoid = '".$proyectoid."'");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    //obtener los entregables con estado ultimo de un proyecto
    public function _getEntregablexProyectoxUltimo($proyectoid)
    {
      try {
        $sql = $this->_db->query("select ent.cod_le, ent.edt, ent.tipo_documento,
        ent.disciplina, ent.codigo_anddes, ent.codigo_cliente, ent.descripcion_entregable,
        ent.revision, ent.estado_revision, det.transmittal, det.correlativo,
        tip.emitido_para as emitido, det.fecha, det.respuesta_transmittal, det.respuesta_emitido,
        det.respuesta_fecha, det.estado, det.comentario
        from lista_entregable as ent left join detalle_transmittal as det
        on (ent.cod_le = det.entregableid) inner join tipo_envio as tip
        on (det.emitido=tip.codigo and det.tipo_envio=tip.tipo)
        where ent.proyectoid = '".$proyectoid."' and ent.estado_revision = 'Ultimo'");
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
