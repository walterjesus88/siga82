<?php
class Admin_Model_DbTable_Activaractividad extends Zend_Db_Table_Abstract
{
    protected $_name = 'activar_actividad';
    protected $_primary = array("codigo_prop_proy","codigo_actividad", "proyectoid", "actividadid", "uid", "dni","cargo", "areaid", "categoriaid");

    public function _getOne($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                $wherestr= "codigo_prop_proy = '".$where['codigo_prop_proy']."' and codigo_actividad='".$where['codigo_actividad']."'
                			and proyectoid='".$where['proyectoid']."'  and actividadid='".$where['actividadid']."'
                			and uid='".$where['uid']."' and dni='".$where['dni']."'  and cargo='".$where['cargo']."'  and areaid='".$where['areaid']."'
                			and categoriaid='".$where['categoriaid']."' ";

                //print_r($wherestr);

                $row = $this->fetchRow($wherestr);

                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }



    public function _updateX($data,$pk)
    {
        try{
            if ($pk['codigo_prop_proy']=='' || $pk['codigo_actividad']=='' || $pk['proyectoid']=='' || $pk['actividadid']=='' || $pk['uid']=='' || $pk['dni']=='' || $pk['cargo']=='' || $pk['areaid']=='' || $pk['categoriaid']=='' ) return false;

            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."'  and codigo_actividad = '".$pk['codigo_actividad']."'
            		 and proyectoid = '".$pk['proyectoid']."' and actividadid = '".$pk['actividadid']."'
            		 and uid = '".$pk['uid']."' and dni = '".$pk['dni']."' and cargo = '".$pk['cargo']."' 
            		 and areaid = '".$pk['areaid']."' and categoriaid = '".$pk['categoriaid']."'   
             ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }



    
    public function _save($data)
    {
        try{
            //if ($data['areaid']=='' ||  $data['codigo_prop_proy']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }


    public function _getExistePersonaActividad($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                $wherestr= "codigo_prop_proy = '".$where['codigo_prop_proy']."' and codigo_actividad='".$where['codigo_actividad']."'
                            and proyectoid='".$where['proyectoid']."'  and actividadid='".$where['actividadid']."'
                            and uid='".$where['uid']."' and dni='".$where['dni']."'  and areaid='".$where['areaid']."'
                             ";

                //print_r($wherestr);

                $row = $this->fetchRow($wherestr);

                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{            
            //if($where['codigo_prop_proy']=='' || $where['proyectoid']=='' ) return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("activar_actividad");
                else $select->from("activar_actividad",$attrib);
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
            print "Error: Read Filter Actividad ".$e->getMessage();
        }
    }  


    public function _getConteoactivar($codigo_prop_proy,$proyectoid,$uid,$dni,$estado,$areaid,$categoriaid)
     {

       
        try{
            $sql=$this->_db->query("
              select  count(*) from activar_actividad
              where codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' 
              and estado='$estado' and uid='$uid' and dni='$dni' 
              and areaid = '$areaid' and categoriaid='$categoriaid'

            ");
            // print_r($sql);
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getExistePersonaActividadPadre($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                $wherestr= "codigo_prop_proy = '".$where['codigo_prop_proy']."'  and proyectoid='".$where['proyectoid']."'  and actividad_padre='".$where['actividad_padre']."'
                            and uid='".$where['uid']."' and dni='".$where['dni']."'  and areaid='".$where['areaid']."'
                             ";

                //print_r($wherestr);

                $row = $this->fetchRow($wherestr);

                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getActividadesxCppxPixUid($codigo_prop_proy, $proyectoid, $uid){
        try{
            $sql=$this->_db->query("
              select acv.codigo_prop_proy, acv.codigo_actividad, acv.actividadid, act.proyectoid, acv.actividad_padre, act.nombre, 
                acv.categoriaid, act.propuestaid, acv.revision, act.h_propuesta
                from actividad as act inner join activar_actividad as acv on act.codigo_prop_proy = acv.codigo_prop_proy 
                and act.proyectoid = acv.proyectoid and act.codigo_actividad = acv.codigo_actividad and act.actividadid = acv.actividadid "
                ."where acv.codigo_prop_proy = '".$codigo_prop_proy."' and acv.proyectoid = '".$proyectoid."' and acv.uid = '".$uid."' and acv.estado='A' ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getExisteActividadPadre($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                $wherestr= "codigo_prop_proy = '".$where['codigo_prop_proy']."'  and proyectoid='".$where['proyectoid']."'  and actividad_padre='".$where['actividad_padre']."'
                            and uid='".$where['uid']."' and dni='".$where['dni']."'  and areaid='".$where['areaid']."' and actividadid='".$where['actividadid']."'
                             ";

                //print_r($wherestr);

                $row = $this->fetchRow($wherestr);

                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

     public function _getConteoActividadesxEstado($codigo_prop_proy,$proyectoid,$uid,$dni,$areaid,$estado,$actividad_padre)
     {

       
        try{
            $sql=$this->_db->query("
              select  count(*) from activar_actividad
              where codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' 
              and estado='$estado' and uid='$uid' and dni='$dni' 
              and areaid = '$areaid'  and actividad_padre='$actividad_padre'
            ");
            // print_r($sql);
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getConteoActividadesxPadre($codigo_prop_proy,$proyectoid,$uid,$dni,$areaid,$actividad_padre)
     {

       
        try{
            $sql=$this->_db->query("
              select  count(*) from activar_actividad
              where codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' 
              and  uid='$uid' and dni='$dni' 
              and areaid = '$areaid' and actividad_padre='$actividad_padre'
            ");
            // print_r($sql);
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


        public function _updateXEstado($data,$pk)
    {
        try{
            if ($pk['codigo_prop_proy']=='' || $pk['proyectoid']=='' || $pk['actividadid']=='' || $pk['uid']=='' || $pk['dni']=='' || $pk['areaid']==''  ) return false;

            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."'  and codigo_actividad = '".$pk['codigo_actividad']."'
                     and proyectoid = '".$pk['proyectoid']."' and actividadid = '".$pk['actividadid']."'
                     and uid = '".$pk['uid']."' and dni = '".$pk['dni']."' 
                     and areaid = '".$pk['areaid']."' and actividad_padre = '".$pk['actividad_padre']."'   
             ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }


       public function _updateestado($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
            print "Error: Actualizando un registro de Proyecto".$ex->getMessage();
        }
    }



                  public function _getClientesXEmpleadoXEstadoActivo($uid,$dni,$estado)
     {
        try{
            $sql=$this->_db->query("
                

                   select distinct (p.clienteid), c.nombre_comercial
                   from activar_actividad e inner join proyecto p
                ON e.codigo_prop_proy = p.codigo_prop_proy and e.proyectoid=p.proyectoid 
                inner join cliente c on
                p.clienteid=c.clienteid

                where e.uid = '$uid' and e.dni='$dni' and p.estado='$estado' and e.estado = '$estado' order by c.nombre_comercial




               ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



}