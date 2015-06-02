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
                			and categoriaid='".$where['categoriaid']."'

                ";

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



}