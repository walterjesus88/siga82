<?php 
class Admin_Model_DbTable_Tareo extends Zend_Db_Table_Abstract
{
    protected $_name = 'tareo';
    protected $_primary = array("codigo_prop_proy", "codigo_actividad", "actividadid", "revision", "actividad_padre", "categoriaid");

     /* Lista toda los Tareos */    
    public function _getTareoXUid($where=array()){
        try{
            if ($where['uid'] == '') return false;
            $wherestr="uid = '".$where['uid']."' ";
            $row = $this->fetchAll($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas los tareos".$e->getMessage();
        }
    }
    
    public function _getAreaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getAreaxIndice($areaid)
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where areaid='$areaid' 

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getTareoxProyecto($proyectoid,$codigo,$revision)
     {
        try{
            $sql=$this->_db->query("
               select * from tareo
               where proyectoid='$proyectoid'  and codigo_prop_proy='$codigo' and revision='$revision'
               and isproyecto='S'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getTareoxProyectoxActividadPadre($proyectoid,$codigo,$revision,$actividad_padre)
     {
        try{
            $sql=$this->_db->query("
               select * from tareo
               where proyectoid='$proyectoid'  and codigo_prop_proy='$codigo' and revision='$revision'
               and actividad_padre='$actividad_padre' and isproyecto='S'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getTareoxProyectoxActividadHija($proyectoid,$codigo,$revision,$actividad_padre,$actividadid,$codigo_actividad)
     {
        try{
            $sql=$this->_db->query("
               select * from tareo
               where proyectoid='$proyectoid'  and codigo_prop_proy='$codigo' and revision='$revision'
               and actividad_padre='$actividad_padre' and actividadid='$actividadid' and codigo_actividad='$codigo_actividad'
               and isproyecto='S'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getTareoxProyectoxActividadHijaxArea($proyectoid,$codigo,$revision,$actividad_padre,$actividadid,$codigo_actividad,$areaid)
     {
        try{
            $sql=$this->_db->query("
               select * from tareo
               where proyectoid='$proyectoid'  and codigo_prop_proy='$codigo' and revision='$revision'
               and actividad_padre='$actividad_padre' and actividadid='$actividadid' and codigo_actividad='$codigo_actividad'
               and areaid='$areaid' and isproyecto='S'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getTareoxProyectoxActividadHijaxAreaxCategoria($proyectoid,$codigo,$revision,$actividad_padre,$actividadid,$codigo_actividad,$areaid,$categoriaid)
     {
        try{
            $sql=$this->_db->query("
               select * from tareo
               where proyectoid='$proyectoid'  and codigo_prop_proy='$codigo' and revision='$revision'
               and actividad_padre='$actividad_padre' and actividadid='$actividadid' and codigo_actividad='$codigo_actividad'
               and areaid='$areaid' and isproyecto='S' and categoriaid='$categoriaid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }




    public function _update($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
            print "Error: Actualizando un registro de Propuesta".$ex->getMessage();
        }
    }

   

        public function _save($data)
    {
        try{
            if ($data['areaid']=='' ||  $data['codigo_prop_proy']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

}
