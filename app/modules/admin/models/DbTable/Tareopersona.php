<?php 
class Admin_Model_DbTable_Tareopersona extends Zend_Db_Table_Abstract
{
    protected $_name = 'tareo_persona';
    protected $_primary = array("codigo_prop_proy", "codigo_actividad", "actividadid", "revision", "proyectoid", "semanaid","fecha_tarea","uid","dni");

    public function _getTareopersonaXUid($where=array()){
        try{
            if ($where['uid'] == '') return false;
            $wherestr="uid = '".$where['uid']."' and dni = '".$where['dni']."'";
            $row = $this->fetchAll($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas los tareos".$e->getMessage();
        }
    }
    
    public function _getTareopersonall(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
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

    public function _getTareoxPersonaxSemana($uid,$dni,$semanaid)
     {
        try{
            $sql=$this->_db->query("
               

                 select * from tareo_persona as tareo inner join actividad as act
  on tareo.actividadid=act.actividadid and tareo.codigo_actividad=act.codigo_actividad and tareo.codigo_prop_proy=act.codigo_prop_proy
  and tareo.revision=act.revision
  inner join proyecto as pro on tareo.codigo_prop_proy=pro.codigo_prop_proy
  and tareo.revision=pro.revision and tareo.proyectoid=pro.proyectoid 
  
where uid='$uid' and dni='$dni' and semanaid='$semanaid'


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
