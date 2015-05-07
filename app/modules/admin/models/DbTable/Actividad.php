<?php
class Admin_Model_DbTable_Actividad extends Zend_Db_Table_Abstract
{
    protected $_name = 'actividad';
    protected $_primary = array("codigo_prop_proy","codigo_actividad","actividadid","revision");

    public function _getActividad(){
        try{
            $f=$this->fetchAll();
            if($f) return $f->toArray(); 
      return false;
        }catch (Exception $ex){
            print "Error: Listando Proyectos".$ex->getMessage();
        }
     }

  public function _getProyectoxActividad()
     {
        try{
            $sql=$this->_db->query("
               select codigo_prop_proy, codigo_actividad, actividadid, revision from actividad 
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


    public function _getActividadesxIndice($proyectoid,$codigo,$propuestaid,$revision)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad 
               where proyectoid='$proyectoid' and codigo_prop_proy='$codigo' 
               and propuestaid='$propuestaid' and revision='$revision' order by orden asc;
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getActividadesPadres($proyectoid,$codigo,$propuestaid,$revision)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad 
               where proyectoid='$proyectoid' and codigo_prop_proy='$codigo' 
               and propuestaid='$propuestaid' and revision='$revision' and actividad_padre IS NULL order by orden asc;
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getActividadesHijas($proyectoid,$codigo,$propuestaid,$revision,$actividadid)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad 
               where proyectoid='$proyectoid' and codigo_prop_proy='$codigo' 
               and propuestaid='$propuestaid' and revision='$revision' and actividad_padre='$actividadid' order by orden asc;
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


  
    
}


