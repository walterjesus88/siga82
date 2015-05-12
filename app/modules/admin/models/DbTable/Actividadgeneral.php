<?php
class Admin_Model_DbTable_Actividadgeneral extends Zend_Db_Table_Abstract
{
    protected $_name = 'actividad_general';
    protected $_primary = array("actividad_generalid","areaid");

    public function _getActividadgeneral(){
        try{
            $f=$this->fetchAll();
            if($f) return $f->toArray(); 
      return false;
        }catch (Exception $ex){
            print "Error: Listando Proyectos".$ex->getMessage();
        }
     }

  public function _getActividadgeneralxArea($areaid)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad_general where 
               areaid='$areaid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }

      public function _getActividadgeneralxId($actividadgeneralid)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad_general where 
               actividad_generalid='$actividadgeneralid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }

  


     public function _save($data){
        try{
            if ($data['codigo_prop_proy']=="" ) return false;
            return $this->insert($data);
        }catch (Exception $ex){
            print "Error: Insertando un nuevo Proyecto".$ex->getMessage();
        }
    }

    public function eliminar($proyectoid,$actividadid){
        try{
            if ($proyectoid=='') return false;
            return $this->delete("proyectoid='$proyectoid' and actividadid='$actividadid'");
        }catch (Exception $ex){
            print "Error: Eliminando un registro de Proyecto".$ex->getMessage();
        }
    }


    public function _update($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
            print "Error: Actualizando un registro de Proyecto".$ex->getMessage();
        }
    }


    

  
    
}


