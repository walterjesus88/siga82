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

        public function _getActividadesxActividadid($proyectoid,$codigo,$actividadid)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad 
               where proyectoid='$proyectoid' and codigo_prop_proy='$codigo' 
               and actividadid='$actividadid' order by orden
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getRepliconActividades($proyectoid,$codigo)
     {
        try{
            $sql=$this->_db->query("
                select * from actividad 
                where proyectoid='$proyectoid' and codigo_prop_proy='$codigo' 
                order by orden
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



    public function _getActividadesPadresXproyectoXcodigo($proyectoid,$codigo)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad 
               where proyectoid='$proyectoid' and codigo_prop_proy='$codigo' 
               and actividad_padre IS NULL order by orden asc;
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getActividadesPadresXProyectoXCategoria($proyectoid,$categoriaid,$codigo)
     {
        try{
            $sql=$this->_db->query("
                select distinct(left(tar.actividad_padre,1)) as padre from tareo as tar
inner join actividad as act on
tar.codigo_prop_proy=act.codigo_prop_proy and  tar.codigo_actividad=act.codigo_actividad and 
tar.actividadid=act.actividadid and  tar.revision=act.revision
    where tar.proyectoid='$proyectoid' and tar.categoriaid='$categoriaid' 
    and tar.codigo_prop_proy='$codigo' 
    order by left(tar.actividad_padre,1)
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

     public function _getActividadesPadres_Replicon($proyectoid,$codigo,$propuestaid,$revision)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad 
               where proyectoid='$proyectoid' and codigo_prop_proy='$codigo' 
               and propuestaid='$propuestaid' and revision='$revision' and actividad_padre='0'  order by orden asc;
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

    public function _getActividadesHijasxCategoriaxActividadPadreUnica($proyectoid,$codigo,$propuestaid,$revision,$actividadid,$categoriaid)
     {
        try{
            $sql=$this->_db->query("
                select * from tareo as tar
                inner join actividad as act on
                tar.codigo_prop_proy=act.codigo_prop_proy and  tar.codigo_actividad=act.codigo_actividad and 
                tar.actividadid=act.actividadid and  tar.revision=act.revision
                where tar.proyectoid='$proyectoid' and tar.categoriaid='$categoriaid' 
                and tar.propuestaid='$propuestaid' and tar.revision='$revision' and tar.codigo_prop_proy='$codigo'
                and left(tar.actividad_padre,1)='$actividadid' order by tar.actividad_padre
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getActividadesHijasxCategoriaxActividadTareas($proyectoid,$codigo,$propuestaid,$revision,$actividadid,$categoriaid)
     {
        try{
            $sql=$this->_db->query("
                select * from tareo as tar
                inner join actividad as act on
                tar.codigo_prop_proy=act.codigo_prop_proy and  tar.codigo_actividad=act.codigo_actividad and 
                tar.actividadid=act.actividadid and  tar.revision=act.revision
                where tar.proyectoid='$proyectoid' and tar.categoriaid='$categoriaid' 
                and act.propuestaid='$propuestaid' and act.revision='$revision' and tar.codigo_prop_proy='$codigo'
                and left(tar.actividad_padre,1)='$actividadid' order by tar.actividad_padre
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


public function _getActividadesHijasxActividadesPadresXCategoria($proyectoid,$codigo,$propuestaid,$revision,$actividadid,$categoriaid)
     {
        try{
            $sql=$this->_db->query("
                select distinct tar.actividad_padre from tareo as tar
                inner join actividad as act on
                tar.codigo_prop_proy=act.codigo_prop_proy and  tar.codigo_actividad=act.codigo_actividad and 
                tar.actividadid=act.actividadid and  tar.revision=act.revision
                
                where tar.proyectoid='$proyectoid' and tar.categoriaid='$categoriaid' 
                and act.propuestaid='$propuestaid' and act.revision='$revision' and tar.codigo_prop_proy='$codigo'

                order by tar.actividad_padre
                
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

public function _getTareasxActividadPadrexCategoria($proyectoid,$codigo,$propuestaid,$revision,$actividadid,$categoriaid)
     {
        try{
            $sql=$this->_db->query("
                select * from tareo as tar
                inner join actividad as act on
                tar.codigo_prop_proy=act.codigo_prop_proy and  tar.codigo_actividad=act.codigo_actividad and 
                tar.actividadid=act.actividadid and  tar.revision=act.revision
                
                where tar.proyectoid='$proyectoid' and tar.categoriaid='$categoriaid' 
                and act.propuestaid='$propuestaid' and act.revision='$revision' and tar.codigo_prop_proy='$codigo'
                and tar.actividad_padre='$actividadid' 
                order by tar.actividadid desc
                
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getTareasxActividadPadrexCategoriaXisgasto($proyectoid,$codigo,$propuestaid,$revision,$actividadid,$categoriaid)
     {
        try{
            $sql=$this->_db->query("
                select * from tareo as tar
                inner join actividad as act on
                tar.codigo_prop_proy = act.codigo_prop_proy and tar.codigo_actividad=act.codigo_actividad and 
                tar.actividadid=act.actividadid and tar.revision=act.revision and tar.proyectoid=act.proyectoid
                
                where tar.proyectoid='$proyectoid' and tar.categoriaid='$categoriaid' 
                and act.propuestaid='$propuestaid' and act.revision='$revision' and tar.codigo_prop_proy='$codigo'
                and tar.actividad_padre='$actividadid' and act.isgasto='S' 

                
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _existeactividad($nombre,$proyectoid,$codigo,$revision,$propuestaid)
     {
        try{
            $sql=$this->_db->query("
                select * from actividad
                
                
                where proyectoid='$proyectoid' and revision='$revision' 
                and propuestaid='$propuestaid' and codigo_prop_proy='$codigo'
                and nombre like '%$nombre' 

                
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _existeactividadhija($nombre,$proyectoid,$codigo,$revision,$propuestaid)
     {
        try{
            $sql=$this->_db->query("
                select * from actividad
                
                
                where proyectoid='$proyectoid' and revision='$revision' 
                and propuestaid='$propuestaid' and codigo_prop_proy='$codigo'
                and nombre like '%$nombre' 

                
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{            
            if($where['codigo_prop_proy']=='' || $where['proyectoid']=='' ) return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("actividad");
                else $select->from("actividad",$attrib);
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
  
  public function _getActividadesPadresxReplicon($proyectoid,$codigo,$propuestaid,$revision)
     {
        try{
            $sql=$this->_db->query("
               select * from actividad 
               where proyectoid='$proyectoid' and codigo_prop_proy='$codigo' 
               and propuestaid='$propuestaid' and revision='$revision'   order by orden asc;
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getActividadesHijasxReplicon($proyectoid,$codigo,$propuestaid,$revision,$actividadid)
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

    public function _getOne($pk=null)
    {
        try{
            //codigo_prop_proy, codigo_actividad, actividadid, revision
            if ($pk['codigo_prop_proy']=='' || $pk['codigo_actividad']=='' || $pk['actividadid']=='' || $pk['revision']==''  ) return false;
            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and  codigo_actividad = '".$pk['codigo_actividad']."' and actividadid = '".$pk['actividadid']."' and revision = '".$pk['revision']."' ";
            $row = $this->fetchRow($where);
            if ($row) return $row->toArray();
            return false;
        }catch (Exception $ex){
            print "Error: Get Info curriculum ".$ex->getMessage();
        }
    }


    public function _getRepliconProyectosInternos($codigo)
     {
        try{
            $sql=$this->_db->query("
                select * from proyecto 
                where codigo_prop_proy='$codigo' 
                order by proyectoid
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }
    
}


