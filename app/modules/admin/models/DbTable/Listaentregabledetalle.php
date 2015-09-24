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


    public function _getOne($where){
        try {
            //if ($where["codigo_prop_proy"]=='' || $where["proyectoid"]==''  ||  $where["revision_entregable"]==''  ||  $where["edt"]==''   ) return false;

                //$wherestr= "codigo_prop_proy = '".$where['codigo_prop_proy']."' and  proyectoid = '".$where['proyectoid']."' and  revision_entregable = '".$where['revision_entregable']."' and edt = '".$where['edt']."'";
                $wherestr= "cod_le = '".$where['cod_le']."' ";

                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _update($data,$pk)
    {
        try{
            //if ($pk['id_tproyecto']=='' ||  $pk['proyectoid']=='' ) return false;
            // $where = "
            //      codigo_prop_proy = '".$pk['codigo_prop_proy']."' and revision_entregable = '".$pk['revision_entregable']."' and edt = '".$pk['edt']."' and proyectoid = '".$pk['proyectoid']."'
            // ";
            $where = "cod_le = '".$pk['cod_le']."' ";

            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
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

    public function _getListaEntregables($proyectoid)
    {
      try {
        $query1 = "select led.cod_le, led.proyectoid, led.revision_documento as
        revision_entregable, led.edt, led.tipo_documento, led.disciplina,
        led.codigo_anddes, led.codigo_cliente, led.descripcion_entregable,
        led.estado as estado_revision, led.clase, led.fecha_a, led.fecha_b, led.fecha_0
        from lista_entregable_detalle as led
        where led.proyectoid = '".$proyectoid."' and led.clase = 'Tecnico'
        and led.estado = 'Ultimo'";
        $sql = $this->_db->query($query1);
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    //obtener los entregables de un proyecto
    public function _getEntregablexProyecto($proyectoid, $condicion, $clase)
    {
      try {
        $query1 = "select led.cod_le, led.proyectoid, led.revision_documento as
        revision_entregable,
        led.edt, led.tipo_documento, led.disciplina, led.codigo_anddes, led.codigo_cliente,
        led.descripcion_entregable, led.estado as estado_revision,
        det.transmittal, det.correlativo,
        tip.emitido_para as emitido, det.fecha, det.respuesta_transmittal,
        ti.emitido_para as respuesta_emitido,
        det.respuesta_fecha, det.estado, det.comentario, led.clase
        from lista_entregable_detalle as led left join detalle_transmittal as det
        on (led.cod_le = det.entregableid) left join tipo_envio as tip
        on (det.emitido = tip.codigo and det.tipo_envio = tip.tipo) left join
        tipo_envio as ti on (det.respuesta_emitido = ti.codigo and det.tipo_envio = ti.tipo)
        where led.proyectoid = '".$proyectoid."' and led.clase = '".$clase."'";
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

    //guardar el tipo de documento
    public function _setTipoEntregable($entregableid, $tipo)
    {
      $id = (int)$entregableid;
      $row = $this->fetchRow('cod_le = ' . $id);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->tipo_documento = $tipo;
      $row->save();
    }

    //guardar la disciplina
    public function _setDisciplina($entregableid, $disciplina)
    {
      $id = (int)$entregableid;
      $row = $this->fetchRow('cod_le = ' . $id);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->disciplina = $disciplina;
      $row->save();
    }

    //guardar la descripcion
    public function _setDescripcion($entregableid, $descripcion)
    {
      $id = (int)$entregableid;
      $row = $this->fetchRow('cod_le = ' . $id);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->descripcion_entregable = $descripcion;
      $row->save();
    }

    //guardar la revision
    public function _setRevision($entregableid, $revision)
    {
      $id = (int)$entregableid;
      $row = $this->fetchRow('cod_le = ' . $id);
      if (!$row) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      $row->revision_documento = $revision;
      $row->save();
    }

    //guardar los datos del detalle de entregable
    public function _setEntregable($data)
    {
      try {
        $id = (int)$data['entregableid'];
        if ($id == 0) {
          $sql = $this->_db->query("select codigo_prop_proy from proyecto where
          proyectoid = '".$data['proyectoid']."'");
          $codigo = $sql->fetch();

          $sql = $this->_db->query("insert into lista_entregable_detalle
          (codigo_prop_proy, proyectoid, revision_entregable, edt, tipo_documento,
          disciplina, codigo_anddes, codigo_cliente, descripcion_entregable, estado,
          clase, revision_documento, estado_entregable)
          values ('".$codigo['codigo_prop_proy']."', '".$data['proyectoid']."', 'A',
          '000', '".$data['tipo_documento']."', '".$data['disciplina']."',
          '".$data['codigo_anddes']."', '".$data['codigo_cliente']."',
          '".$data['descripcion']."', 'Ultimo', '".$data['clase']."', '".$data['revision']."', 1)");
          $row = $sql->fetch();
          $resp['resultado'] = 'guardado';
          return $resp;
        } else {
          $sql = $this->_db->query("update lista_entregable_detalle set
          tipo_documento= '".$data['tipo_documento']."', disciplina = '".
          $data['disciplina']."', codigo_anddes = '".$data['codigo_anddes'].
          "', codigo_cliente = '".$data['codigo_cliente'].
          "', descripcion_entregable = '".$data['descripcion']."' where cod_le = ".
          $id);
          $row = $sql->fetchAll();
          $resp['resultado'] = 'guardado';
          return $resp;
        }
        return $id;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    //eliminar los datos de un entregable
    public function _deleteEntregable($entregableid)
    {
      try {
        $sql = $this->_db->query("delete from lista_entregable_detalle where
        cod_le = '".$entregableid."'");
        $row = $sql->fetchAll();
        $resp['resultado'] = 'eliminado';
        return $resp;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }


    public function _delete($pk=null)
    {
        try{

            if ($pk['codigo_prop_proy']=='' ||  $pk['proyectoid']=='' ||  $pk['revision_entregable']=='' ||  $pk['edt']=='' ) return false;

            $where = "edt = '".$pk['edt']."'
                    and codigo_prop_proy = '".$pk['codigo_prop_proy']."'
                    and proyectoid = '".$pk['proyectoid']."'
                    and revision_entregable = '".$pk['revision_entregable']."'
                     ";

            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Eliminar EDT".$e->getMessage();
        }
    }


    //array con los reportes de todos los proyectos
    public function _getReporteAll()
    {
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $lista = $proyecto->_getAllExtendido('A');

      $respuesta = [];
      $data = [];
      $i = 0;
      foreach ($lista as $item) {
        $data['codigo'] = $item['proyectoid'];
        $data['cliente'] = $item['nombre_comercial'];
        $data['nombre'] = $item['nombre_proyecto'];
        $data['gerente'] = $item['gerente_proyecto'];
        $data['control_proyecto'] = $item['control_proyecto'];
        $data['control_documentario'] = $item['control_documentario'];
        $data['estado'] = $item['estado'];

        $respuesta[$i]['proyecto'] = $data;

        $rows = $this->_getLEReporte($data['codigo']);

        $respuesta[$i]['entregables'] = $rows;

        $i++;
      }

      $envio = [];
      $j = 0;

      for ($i=0; $i < sizeof($respuesta); $i++) {
        if (sizeof($respuesta[$i]['entregables']) != 0) {
          $envio[$j] = $respuesta[$i];
          $j++;
        }
      }

      return $envio;
    }

    public function _getReportexProyecto($proyectoid)
    {
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $data['proyectoid'] = $proyectoid;
      $pro = $proyecto->_getOnexProyectoidExtendido($data);
      $lista[0] = $pro;

      $respuesta = [];
      $data = [];
      $i = 0;
      foreach ($lista as $item) {
        $data['codigo'] = $item['proyectoid'];
        $data['cliente'] = $item['nombre_comercial'];
        $data['nombre'] = $item['nombre_proyecto'];
        $data['gerente'] = $item['gerente_proyecto'];
        $data['control_documentario'] = $item['control_documentario'];
        $data['estado'] = $item['estado'];

        $respuesta[$i]['proyecto'] = $data;

        $rows = $this->_getLEReporte($data['codigo']);

        $respuesta[$i]['entregables'] = $rows;

        $i++;
      }

      $envio = [];
      $j = 0;

      for ($i=0; $i < sizeof($respuesta); $i++) {
        if (sizeof($respuesta[$i]['entregables']) != 0) {
          $envio[$j] = $respuesta[$i];
          $j++;
        }
      }

      return $envio;
    }

    public function _createRevision($data)
    {
      try {
        $id = (int)$data['entregableid'];

        $ent = $this->fetchRow('cod_le ='.$id);
        $ent->estado = 'Old';
        $ent->save();

        $revision_documento = chr(ord($ent->revision_documento) + 1);

        $sql = $this->_db->query("insert into lista_entregable_detalle
        (codigo_prop_proy, proyectoid, revision_entregable, edt, tipo_documento,
        disciplina, codigo_anddes, codigo_cliente, descripcion_entregable,
        fecha_a, fecha_b, fecha_0, estado, clase, revision_documento,
        estado_entregable) values ('".
        $ent->codigo_prop_proy."', '".$ent->proyectoid."', '".$ent->revision_entregable.
        "', '".$ent->edt."', '".$ent->tipo_documento."', '".$ent->disciplina."',
        '".$ent->codigo_anddes."', '".$ent->codigo_cliente."', '".
        $ent->descripcion_entregable."', '".$ent->fecha_a."', '".$ent->fecha_b.
        "', '".$ent->fecha_0."', 'Ultimo', '".$ent->clase."', '".
        $revision_documento."', '".$ent->estado_entregable."')");

        $res = $sql->fetch();

        return $res;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getLEReporte($proyectoid)
    {
      $sql = $this->_db->query("select * from lista_entregable_detalle
      where proyectoid = '".$proyectoid."' and estado = 'Ultimo'");
      $rows = $sql->fetchAll();

      //////////////////////////////////////////////////////////////////////////
      /*Calculo de fechas inicial de acuerdo a la fecha de la revision actual
      Calculo de la fecha final de acuerdo a la fecha de la revision programada*/
      //////////////////////////////////////////////////////////////////////////
      for ($i=0; $i < sizeof($rows); $i++) {
        $rows[$i]['fecha_inicial'] = '07-09-2015';
        $rows[$i]['fecha_final'] = '14-09-2015';
      }

      return $rows;
    }

}
