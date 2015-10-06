<?php
class Admin_Model_DbTable_Gastopersona extends Zend_Db_Table_Abstract
{
    protected $_name = 'gasto_persona';
    protected $_primary = array("codigo_prop_proy", "proyectoid", "revision", "categoriaid", "gastoid", "uid", "dni", "gasto_persona_id");

     /* Lista toda las Personas */
    public function _getGastopersonaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos persona".$e->getMessage();
        }
    }

    public function _save($data)
    {
        try{
            if ($data['proyectoid']=='' ||  $data['codigo_prop_proy']=='' ||  $data['uid']=='' ||  $data['dni']=='' ||  $data['categoriaid']=='') return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

        public function _save2($data)
    {
        try{
            if ($data['uid']=='' ||  $data['dni']=='') return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

    public function _update($data,$str){
        try{
            if ($str['codigo_prop_proy']=='' ||  $str['proyectoid']=='' || $str['gasto_persona_id']=='') return false;
            $where = "codigo_prop_proy = '".$str['codigo_prop_proy']."' and proyectoid='".$str['proyectoid']."' and gasto_persona_id='".$str['gasto_persona_id']."'";
            return $this->update($data,$where);
        }catch (Exception $ex){
            print "Error: Actualizando un registro ".$ex->getMessage();
        }
    }

    public function _updateX($data,$pk)
    {
        try{


            //if ($pk['codigo_prop_proy']=='' ||  $pk['propuestaid']=='' ||  $pk['revision']=='' ) return false;
            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and proyectoid='".$pk['proyectoid']."' and revision='".$pk['revision']."'
            and uid='".$pk['uid']."' and dni='".$pk['dni']."' and numero_rendicion='".$pk['numero_rendicion']."' ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }

    public function _delete($gasto_persona_id){
        try{
            if ($gasto_persona_id=='') return false;
            return $this->delete("gasto_persona_id='$gasto_persona_id'");
        }catch (Exception $ex){
            print "Error: Eliminando un registro ".$ex->getMessage();
        }
    }

    public function _deleteX($numero,$uid,$dni){
        try{
            if ($numero=='' || $uid=='' ||  $dni=='' ) return false;
            return $this->delete("numero_rendicion='$numero' and uid='$uid' and dni='$dni'   ");
        }catch (Exception $ex){
            print "Error: Eliminando un registro ".$ex->getMessage();
        }
    }


    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
                $select = $this->_db->select();
                if ($attrib=='') $select->from("gasto_persona");
                else $select->from("gasto_persona",$attrib);
                foreach ($where as $atri=>$value){
                    $select->where("$atri = ?", $value);
                }
                if ($orders<>null || $orders<>"") {
                    if (is_array($orders))
                        $select->order($orders);
                }
                $results = $select->query();
                $rows = $results->fetchAll();
                if ($rows) return $rows;
                return false;
        }catch (Exception $e){
            print "Error: Read Filter Course ".$e->getMessage();
        }
    }


    public function _getOne($where=array()){
        try{
            if ($where['codigo_prop_proy']=='' || $where['proyectoid']=='' || $where['revision']=='' || $where['ucategoriaid']=='' || $where['gastoid']=='' || $where['asignado']=='' || $where['fecha_gasto']=='' ) return false;
            $wherestr="clienteid = '".$where['clienteid']."' ";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Add_reportacad_adm ".$e->getMessage();
        }
    }

    public function _getgastoProyectosXfecha($fecha_gasto, $uid, $dni){
        try{
            if ($fecha_gasto=='' || $uid=='' || $dni=='') return false;
            $sql=$this->_db->query("
               select proyectoid from gasto_persona
               where fecha_gasto='$fecha_gasto' and uid='$uid' and dni='$dni'
               group by proyectoid order by proyectoid;
            ");
            $row=$sql->fetchAll();
            return $row;
            return false;
        }catch (Exception $e){
            print "Error: Read One Add ".$e->getMessage();
        }
    }

    public function _getgastoProyectosXnumero($numero, $uid, $dni){
         try{
             if ($numero=='' || $uid=='' || $dni=='') return false;
             $sql=$this->_db->query("
               select proyectoid from gasto_persona
               where numero_rendicion='$numero' and uid='$uid' and dni='$dni'
               group by proyectoid order by proyectoid;
             ");
             $row=$sql->fetchAll();
             return $row;
            return false;
        }catch (Exception $e){
            print "Error: Read One Add ".$e->getMessage();
        }
    }

        public function _getOneXnumero($where=array()){
        try{
            if ($where['numero']=='') return false;
            $wherestr="numero_rendicion = '".$where['numero']."'";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One ".$e->getMessage();
        }
    }


    public function _getgastoProyectoXfechaXactividad($where=array()){
        try{
            if ($where['fecha_gasto']=='' || $where['uid']=='' || $where['dni']=='' || $where['proyectoid']=='') return false;
            $wherestr = "fecha_gasto = '".$where['fecha_gasto']."' and uid = '".$where['uid'].
                        "' and dni = '".$where['dni']."' and proyectoid = '".$where['proyectoid'].
                        "' and actividadid IS NOT NULL";
            $row = $this->fetchAll($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Add ".$e->getMessage();
        }
    }

    public function _count_datatable($where){
        try {
            $select = $this->_db
                            ->select()
                            ->from(new Zend_Db_Expr('(' . $this->_select_datatable() . ')'), "COUNT(*) AS total");
            if ($where["sSearch_0"]) {
                $select->where("pg.bill_cliente = ?", $where["[sSearch_0"]);
            }
            if ($where["sSearch_1"]) {
                $select->where("pg.reembolsable = ?", $where["[sSearch_0"]);
            }
            if ($where["sSearch_2"] && split("-yadcf_delim-", $where["sSearch_2"])) {
                $dates = split("-yadcf_delim-", $where["sSearch_2"]);
                if (count(array_filter($dates) > 0 )) {                    
                    if (count(array_filter($dates)) == 1 && $dates[0]) {
                        $select->where("gp.gasto_rendicion >= ?", $this->set_format_date($dates[0]));
                    }else{
                        if (!$dates[0]){
                            $select->where("gp.gasto_rendicion <= ?", $this->set_format_date($dates[1]));
                        }else{
                            $select->where("gp.gasto_rendicion >= ?", $this->set_format_date($dates[0]));
                            $select->where("gp.gasto_rendicion <= ?", $this->set_format_date($dates[1]));
                        }
                    }
                }
            }
            $results = $select->query();
            $rows = $results->fetchAll();
            if ($rows) return $rows;
            return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }
    
    public function _dataTable($page, $per_page, $sort_column, $sort_direction, $where){
        try {
            $select = $this->_select_datatable();
            $select->order($sort_column)->limit($per_page, $page);
            // if (!array_key_exists("format", $where)) {
            // }
            if ($where["sSearch"]) {
                $select->where("pg.nombre Ilike  ? ", "%" . $where['sSearch'] . "%");
            }
            if ($where["sSearch_0"]) {
                $select->where("pg.bill_cliente = ?", $where["[sSearch_0"]);
            }
            if ($where["sSearch_1"]) {
                $select->where("pg.reembolsable = ?", $where["[sSearch_0"]);
            }
            if ($where["sSearch_2"] && split("-yadcf_delim-", $where["sSearch_2"])) {
                $dates = split("-yadcf_delim-", $where["sSearch_2"]);
                if (count(array_filter($dates) > 0 )) {                    
                    if (count(array_filter($dates)) == 1 && $dates[0]) {
                        $select->where("gp.gasto_rendicion >= ?", $this->set_format_date($dates[0]));
                    }else{
                        if (!$dates[0]){
                            $select->where("gp.gasto_rendicion <= ?", $this->set_format_date($dates[1]));
                        }else{
                            $select->where("gp.gasto_rendicion >= ?", $this->set_format_date($dates[0]));
                            $select->where("gp.gasto_rendicion <= ?", $this->set_format_date($dates[1]));
                        }
                    }
                }
            }
            $results = $select->query();
            $rows = $results->fetchAll();
            if ($rows) return $rows;
            return false;
        } catch (Exception $e) {
            print "Error: Read fetch datatable ".$e->getMessage();
        }
    }

    public function _select_datatable(){
        return $this->_db
                    ->select()
                    ->from(array("gp" => "gasto_persona"),
                            array("gp.fecha_gasto AS fecha_rendicion", "gp.*",
                                    "gr.nombre AS nombre_rendicion", "gr.monto_total AS monto_total_rendicion", "gr.*",
                                    "p.nombre_proyecto") )
                    ->join(array("gr" => "gasto_rendicion"),
                            "gp.numero_rendicion = gr.numero AND gp.uid = gr.uid AND gp.dni = gr.dni")
                    ->join(array('p' => 'proyecto'),
                                'gp.proyectoid = p.proyectoid');
                    // ->join(array('u' => 'usuario'),
                    //             'gp.uid = u.uid');
    }

    private function set_format_date($date){
        $date_t = new Zend_Date($date);
        return $date_t->get("YYYY-MM-dd");
    }


    public function _getlistagastosxNumero($numero){
        try{
            $sql=$this->_db->query("
               select * from
                gasto_persona where 
                    numero_rendicion = '$numero'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }


}

//$gastos_rendicion = $gp -> _getlistagastosxNumero($numero_rendicion);