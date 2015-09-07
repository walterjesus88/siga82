<?php
class Admin_Model_DbTable_Planificacion extends Zend_Db_Table_Abstract
{
    protected $_name = 'planificacion';
    protected $_primary = array("codigo_prop_proy","proyectoid","semanaid","uid","dni","categoriaid","areaid","cargo");
     /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("planificacion");
                else $select->from("planificacion",$attrib);
                //print_r($where);
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

    public function _save($data)
    {
        try{
            //if ($data['codigo_prop_proy']=='' ||  $data['codigo_prop_proy']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Planificacion ".$e->getMessage();
        }
    }

    public function _getOne($where=array()){
        try {
            //if ($where["dni"]=='') return false;
            $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."' and cargo = '".$where['cargo']."'
                 and categoriaid = '".$where['categoriaid']."' and areaid = '".$where['areaid']."' and codigo_prop_proy = '".$where['codigo_prop_proy']."'
                 and proyectoid = '".$where['proyectoid']."' ";


                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getOnexSemana($semanaid,$uid,$dni,$areaid){
        try {
            $sql=$this->_db->query("
               select * from planificacion
               where semanaid='$semanaid'  and uid='$uid' and dni='$dni' and areaid='$areaid'

            ");
            $row=$sql->fetchAll();
            return $row;

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getOnexSemanaxGerenteProyecto($semanaid,$uid,$dni,$areaid){
        try {
            $sql=$this->_db->query("
                select distinct e.uid,e.dni from planificacion as p inner join equipo as e
                on e.codigo_prop_proy=p.codigo_prop_proy and e.proyectoid=p.proyectoid and e.nivel='0'
                where p.semanaid='$semanaid'  and p.uid='$uid' and p.dni='$dni' and p.proyectoid!='1'and p.areaid='$areaid'
                and p.h_totaldia is not null


            ");
            $row=$sql->fetchAll();
            return $row;

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getPlanificacionAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos persona".$e->getMessage();
        }
    }

        /*
                select distinct e.uid,e.dni from planificacion p inner join equipo e
                on p.codigo_prop_proy=e.codigo_prop_proy and p.proyectoid=e.proyectoid
                where p.semanaid='$semanaid'  and p.uid='$uid'  and p.dni='$dni'  and e.nivel='0' and e.categoriaid='GER-PROY'*/

    public function _getSemanaxGerenteProyecto($semanaid,$uid,$dni){
        try {
            $sql=$this->_db->query("

                   select distinct e.uid,e.dni,p.proyectoid from planificacion as p inner join equipo as e
                on e.codigo_prop_proy=p.codigo_prop_proy and e.proyectoid=p.proyectoid and e.nivel='0' and e.cargo='GER-PROY'
                where p.semanaid='$semanaid'  and p.uid='$uid' and p.dni='$dni' and p.proyectoid!='1'



            ");
            $row=$sql->fetchAll();
            return $row;

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getEquipoxProyecto($codigo_proyecto,$proyectoid){
        try {
            $sql=$this->_db->query("
                select * from planificacion as p
                inner join historial_aprobaciones as h
                on p.semanaid=h.semanaid and p.uid=h.uid_empleado and p.dni=h.dni_empleado and p.areaid=h.areaid_empleado
                where p.proyectoid='$proyectoid' and p.codigo_prop_proy = '$codigo_proyecto' and h.etapa_validador='FILTRO2' and estado_historial='A'

            ");
            $row=$sql->fetchAll();
            return $row;

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getEquipoxSemanaxGerenteProyecto($uid,$dni){
        try {
            $sql=$this->_db->query("
                select distinct p.uid,p.dni,p.semanaid,sum(p.h_totaldia) as total,sum(p.billable) as facturable,sum(p.nonbillable) as nofacturable,sum(p.adm) as administrativa from planificacion as p
                inner join historial_aprobaciones as h
                on p.semanaid=h.semanaid and p.uid=h.uid_empleado and p.dni=h.dni_empleado and p.areaid=h.areaid_empleado
                where p.proyectoid in (select distinct e.proyectoid from
                equipo as e where e.uid='$uid'and e.dni='$dni'  and e.nivel='0') and p.h_totaldia is not null
                and h.etapa_validador='FILTRO2' and h.estado_historial='A'
                GROUP BY p.uid,p.dni,p.semanaid
            ");
            $row=$sql->fetchAll();
            return $row;

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getHorasxEquipoxSemanaxProyectosGerenteProyecto($uid_gerente,$dni_gerente,$uid_equipo,$dni_equipo,$semanaid){
        try {
            $sql=$this->_db->query("
                select *,t.estado as estado_tareopersona
                from planificacion as p
                inner join tareo_persona as t
                    on
                        p.semanaid=t.semanaid and p.uid=t.uid and p.dni=t.dni and p.areaid=t.areaid and
                        p.codigo_prop_proy=t.codigo_prop_proy and p.proyectoid=t.proyectoid
                inner join actividad as act
                    on
                        t.actividadid=act.actividadid and t.codigo_actividad=act.codigo_actividad
                        and t.codigo_prop_proy=act.codigo_prop_proy
                        and t.revision=act.revision
                inner join proyecto as pro
                    on
                        p.codigo_prop_proy=pro.codigo_prop_proy
                        and p.proyectoid=pro.proyectoid

                where
                    p.proyectoid in (select distinct
                        e.proyectoid from equipo as e
                        where e.uid='$uid_gerente' and e.dni='$dni_gerente' and e.nivel='0')
                    and p.h_totaldia is not null
                    and p.uid='$uid_equipo' and p.dni='$dni_equipo'and p.semanaid='$semanaid'
                    and t.etapa like 'INICIO%'
                order by act.propuestaid desc,t.proyectoid,t.actividadid,t.tipo_actividad desc
            ");
            $row=$sql->fetchAll();
            return $row;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }



    public function _update($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
           // print "Error: Actualizando un registro de Propuesta".$ex->getMessage();
        }
    }

    public function _getProyectosxSemana($semanaid,$uid,$dni){
        try {
            $sql=$this->_db->query("
               select * from planificacion
               where semanaid='$semanaid'  and uid='$uid' and dni='$dni' and h_totaldia is not null

            ");
            $row=$sql->fetchAll();
            return $row;

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getListarEquipoxAprobacionxGerenteProyecto($uid,$dni){
        try {
            $sql=$this->_db->query("
                select distinct p.uid,p.dni,p.semanaid,sum(p.h_totaldia) as total,sum(p.billable) as facturable,sum(p.nonbillable) as nofacturable,sum(p.adm) as administrativa
                from planificacion as p
                where
                    p.proyectoid in (select distinct
                        e.proyectoid from equipo as e
                        where e.uid='$uid'and e.dni='$dni'  and e.nivel='0')
                    and p.h_totaldia is not null and p.estado='A'
                    GROUP BY p.uid,p.dni,p.semanaid

            ");
            $row=$sql->fetchAll();
            return $row;

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getListarProyectosxSemanaxGerenteProyecto($uid_gerente,$dni_gerente,$uid_equipo,$dni_equipo,$semanaid){
        try {
            $sql=$this->_db->query("
                select *
                from planificacion as p
                where
                    p.proyectoid in (select distinct
                        e.proyectoid from equipo as e
                        where e.uid='$uid_gerente' and e.dni='$dni_gerente' and e.nivel='0')
                    and p.h_totaldia is not null
                    and p.uid='$uid_equipo' and p.dni='$dni_equipo' and p.semanaid='$semanaid'
            ");
            $row=$sql->fetchAll();
            return $row;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getProyectosClientesxSemana($semanaid,$uid,$dni){
        try {
            $sql=$this->_db->query("
               select * from planificacion
               where semanaid='$semanaid'  and uid='$uid' and dni='$dni' and h_totaldia is not null
               and not codigo_prop_proy in ('2015')

            ");
            $row=$sql->fetchAll();
            return $row;

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }
    public function _count_datatable($where){
        try {
            $select = $this->_db
                            ->select()
                            ->from(new Zend_Db_Expr('(' . $this->_select_datatable() . ')'), "COUNT(*) AS total");
            if ($where["sSearch"]) {
                $select->where("nombre Ilike  ? ", "%" . $where['sSearch'] . "%");
            }
            if ($where["sSearch_0"] && split("-yadcf_delim-", $where["sSearch_0"])) {
                $dates = split("-yadcf_delim-", $where["sSearch_0"]);
                if (count(array_filter($dates)) == 1 && $dates[0]) {
                    $select->where("fecha_creacion >= ?", $this->set_format_date($dates[0]));
                }else{
                    if (!$dates[0]){
                        $select->where("fecha_creacion <= ?", $this->set_format_date($dates[1]));
                    }else{
                        $select->where("fecha_creacion >= ?", $this->set_format_date($dates[0]));
                        $select->where("fecha_creacion <= ?", $this->set_format_date($dates[1]));
                    }
                }
            }
            if ($where["sSearch_1"]) {
                $select->where("estado = ?", $where['sSearch_1']);
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
            $select = $this->_select_datatable()
                        ->order($sort_column)
                        ->limit($per_page, $page);
            if ($where["sSearch"]) {
                $select->where("ar.nombre Ilike  ? ", "%" . $where['sSearch'] . "%");
            }
            if ($where["sSearch_0"] && split("-yadcf_delim-", $where["sSearch_0"])) {
                $dates = split("-yadcf_delim-", $where["sSearch_0"]);
                if (count(array_filter($dates)) == 1 && $dates[0]) {
                    $select->where("pa.fecha_creacion >= ?", $this->set_format_date($dates[0]));
                }else{
                    if (!$dates[0]){
                        $select->where("pa.fecha_creacion <= ?", $this->set_format_date($dates[1]));
                    }else{
                        $select->where("pa.fecha_creacion >= ?", $this->set_format_date($dates[0]));
                        $select->where("pa.fecha_creacion <= ?", $this->set_format_date($dates[1]));
                    }
                }
            }
            if ($where["sSearch_1"]) {
                $select->where("pa.estado = ?", $where['sSearch_1']);
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
                    ->from(array("pa" => "planificacion"),
                            array('funcion', 'fecha_creacion', 'uid', 'areaid', 'semanaid', 'proyectoid', 'estado', 'h_totaldia', 'billable', 'nonbillable', 'adm'))
                    ->join(array('ar' => 'area'),
                                'ar.areaid = pa.areaid');
    }
}