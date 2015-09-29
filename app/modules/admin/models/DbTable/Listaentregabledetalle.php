<?php
class Admin_Model_DbTable_Listaentregabledetalle extends Zend_Db_Table_Abstract
{
    protected $_name = 'lista_entregable_detalle';
    protected $_primary = array("cod_le");
    protected $_sequence ="s_lista_entregable_detalle";

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

    public function _update_state($data,$pk)
    {
        try{

            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and proyectoid = '".$pk['proyectoid']."' and revision_entregable = '".$pk['revision_entregable']."' ";

            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }


    public function _getFilteristaentregable($proyectoid,$revision)
    {
      try {
        $query1 = "select led.estado_entregable,led.codigo_prop_proy,led.cod_le, led.proyectoid, led.revision_documento as
        revision_entregable, led.edt, led.tipo_documento, led.disciplina,
        led.codigo_anddes, led.codigo_cliente, led.descripcion_entregable,
        led.estado as estado_revision, led.clase, led.fecha_a, led.fecha_b, led.fecha_0,led.estado_entregable
        from lista_entregable_detalle as led
        where led.proyectoid = '".$proyectoid."' and led.clase = 'Tecnico'
        and led.estado = 'Ultimo'  and  led.estado_entregable not in (10,11)" ;
        $sql = $this->_db->query($query1);
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
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
        $query1 = "select led.cod_le, led.proyectoid, led.revision_documento,
        led.edt, led.tipo_documento, led.disciplina,
        led.codigo_anddes, led.codigo_cliente, led.descripcion_entregable,
        led.estado as estado_revision, led.clase, led.fecha_a, led.fecha_b, led.fecha_0,led.estado_entregable
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
        $query1 = "select led.cod_le, led.proyectoid, led.revision_documento,
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
        $ent = $this->fetchRow('cod_le = '.$data['entregableid']);
        $ent->estado = 'Old';
        $ent->save();

        $revs = ['A', 'B', 'C', 'D', 'E', '0', '1', '2', '3', '4', '5'];

        for ($i=0; $i < sizeof($revs); $i++) {
          if ($revs[$i] == $ent->revision_documento) {
            $a = $i;
          }
          if ($revs[$i] == $data['revision']) {
            $b = $i;
          }
        }

        if ($a <= $b) {
          $nuevo = $this->createRow();
          $nuevo->codigo_prop_proy = $ent->codigo_prop_proy;
          $nuevo->proyectoid = $ent->proyectoid;
          $nuevo->revision_entregable = $ent->revision_entregable;
          $nuevo->edt = $ent->edt;
          $nuevo->tipo_documento = $ent->tipo_documento;
          $nuevo->disciplina = $ent->disciplina;
          $nuevo->codigo_anddes = $ent->codigo_anddes;
          $nuevo->codigo_cliente = $ent->codigo_cliente;
          $nuevo->descripcion_entregable = $ent->descripcion_entregable;
          $nuevo->fecha_a = $ent->fecha_a;
          $nuevo->fecha_b = $ent->fecha_b;
          $nuevo->fecha_0 = $ent->fecha_0;
          $nuevo->estado = 'Ultimo';
          $nuevo->clase = $ent->clase;
          $nuevo->revision_documento = $data['revision'];
          $nuevo->estado_entregable = $ent->estado_entregable;
          $nuevo->save();
          return $ent->cod_le;
        } else {
          throw new Exception('No se puede crear una revision menor');
        }




      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getLEReporte($proyectoid)
    {
      $revs = ['A', 'B', 'C', 'D', 'E', '0', '1', '2', '3', '4', '5'];

      $sql = $this->_db->query("select * from lista_entregable_detalle
      where proyectoid = '".$proyectoid."' and estado = 'Ultimo'");
      $rows = $sql->fetchAll();

      //////////////////////////////////////////////////////////////////////////
      /*Calculo de fechas inicial de acuerdo a la fecha de la revision actual
      Calculo de la fecha final de acuerdo a la fecha de la revision programada*/
      //////////////////////////////////////////////////////////////////////////
      for ($i=0; $i < sizeof($rows); $i++) {
        $clave1 = 'fecha_'.strtolower($rows[$i]['revision_documento']);

        for ($j=0; $j < sizeof($revs); $j++) {
          if ($revs[$j] == $rows[$i]['revision_documento']) {
            if ($j == 10) {
              $sig = $revs[j];
            } else {
              $sig = $revs[$j + 1];
            }
          }
        }

        $clave2 = 'fecha_'.strtolower($sig);
        $rows[$i]['fecha_inicial'] = $rows[$i][$clave1];
        $rows[$i]['fecha_final'] = $rows[$i][$clave2];

        if ($rows[$i]['fecha_inicial'] == '' || $rows[$i]['fecha_inicial'] == null) {
          $rows[$i]['fecha_inicial'] = 'sf';
        }

        if ($rows[$i]['fecha_final'] == '' || $rows[$i]['fecha_final'] == null) {
          $rows[$i]['fecha_final'] = 'sf';
        }
      }

      return $rows;
    }

}
