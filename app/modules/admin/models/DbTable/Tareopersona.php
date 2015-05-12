<?php 
class Admin_Model_DbTable_Tareopersona extends Zend_Db_Table_Abstract
{
    protected $_name = 'tareo_persona';
    protected $_primary = array("codigo_prop_proy", "codigo_actividad", "actividadid", "revision", "actividad_padre","proyectoid", "semanaid","fecha_tarea","uid","dni","cargo","fecha_planificacion","etapa","tipo_actividad");

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

    public function _getTareoxProyectoxTareaxDia($proyectoid,$codigo,$revision,$actividadid,$actividad_padre,$semanaid,$fecha_tarea,$fecha_planificacion,$uid,$dni,$cargo,$etapas)
     {
        try{
            $sql=$this->_db->query("
               select * from tareo_persona
               where proyectoid='$proyectoid'  and codigo_prop_proy='$codigo' and revision='$revision'
               and actividadid='$actividadid' and actividad_padre='$actividad_padre' and semanaid='$semanaid' and fecha_tarea='$fecha_tarea' and fecha_planificacion='$fecha_planificacion'
               and uid='$uid' and dni='$dni' and cargo='$cargo' and etapa='$etapas' and estado='A'
               
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
                select * from tareo_persona as tareo 
                inner join actividad as act
                on tareo.actividadid=act.actividadid and tareo.codigo_actividad=act.codigo_actividad 
                    and tareo.codigo_prop_proy=act.codigo_prop_proy
                    and tareo.revision=act.revision
                inner join proyecto as pro on tareo.codigo_prop_proy=pro.codigo_prop_proy
                    and tareo.revision=pro.revision and tareo.proyectoid=pro.proyectoid 
                where tareo.uid='$uid' and tareo.dni='$dni' and tareo.semanaid='$semanaid' 
                and tareo.etapa like 'INICIO%'  order by tareo.proyectoid,tareo.actividadid,tipo_actividad desc 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getTareoxPersonaxSemanaxNB($uid,$dni,$semanaid)
     {
        try{
            $sql=$this->_db->query("
                select * from tareo_persona as tareo 
                inner join actividad_general as act
                on tareo.actividadid=act.actividad_generalid 
                inner join proyecto as pro on tareo.codigo_prop_proy=pro.codigo_prop_proy
                    and tareo.revision=pro.revision and tareo.proyectoid=pro.proyectoid 
                where tareo.uid='$uid' and tareo.dni='$dni' and tareo.semanaid='$semanaid' 
                and tareo.etapa='INICIO' and tareo.tipo_actividad='G'
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

     public function _delete($pk=null)
    {
        try{
            if ($pk['codigo_prop_proy']=='' ||  $pk['codigo_actividad']=='' ) return false;

            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and codigo_actividad='".$pk['codigo_actividad']."' 
            and actividadid='".$pk['actividadid']."' 
            and revision='".$pk['revision']."' 
            and actividad_padre='".$pk['actividad_padre']."' 
            and proyectoid='".$pk['proyectoid']."' 
            and semanaid='".$pk['semanaid']."' 
            and fecha_tarea='".$pk['fecha_tarea']."' 
            and uid='".$pk['uid']."' 
            and cargo='".$pk['cargo']."' 
            and etapa='".$pk['etapa']."' 
            and fecha_planificacion='".$pk['fecha_planificacion']."' 
            and tipo_actividad='".$pk['tipo_actividad']."' 
            ";
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }


    public function _getHorasRealxDiaXWalter($data = null){
       try {
           if ($data['escid'] == '' || $data['uid'] == '' || $data['curid'] == '') return false;
           $results = $this->_db->query("
               select * from courses_pending_wjrs('".$data['escid']."', '".$data['uid']."', '".$data['curid']."')");
           $rows = $results->fetchAll();
           if ($rows) return $rows;
           return false;
       } catch (Exception $e) {
           print "Error: Read Courses per Curriculum... ".$e->getMessage();
       }
   }

   public function _getHorasRealxDia($semanaid,$fecha_tarea,$uid,$dni,$cargoid)
     {
        try{
            $sql=$this->_db->query("
              select * from tareo_persona_horas_reales('$semanaid','$fecha_tarea','$uid','$dni','$cargoid')
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


}



