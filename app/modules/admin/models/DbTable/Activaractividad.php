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



}