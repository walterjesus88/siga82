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

  public function _getProyectoxAreaxActivo($proyecto,$area)
     {
        try{
            $sql=$this->_db->query("
               select * from proyecto_actividad where proyectoid='$proyecto' and areaid='$area' order by actividadid
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


  public function _getProyectoxId($proyectoid)
     {
        try{
            $sql=$this->_db->query("
               select actividadid, proyectoid, duracion, nombre from proyecto_actividad 

               where proyectoid='$proyectoid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

  public function _getProyectoxIndex($proyectoid,$id)
     {
        try{
            $sql=$this->_db->query("
               select actividadid, proyectoid, duracion, nombre from proyecto_actividad 

               where proyectoid='$proyectoid' and actividadid='$id'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getProyectoxResponsable($responsable,$semana,$categoria)
     {
        try{
            $sql=$this->_db->query("
               select 
               a.actividadid, a.proyectoid, a.duracion, a.nombre 
               from proyecto_actividad as a 
                inner join proyecto_planificacion as p
               on a.proyectoid=p.proyectoid 
               where p.responsable='$responsable' and  p.areaid='$categoria' order by a.proyectoid, a.actividadid
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _eliminaractividad($proyectoid,$actividadid)
     {
        try{
            $sql=$this->_db->query("
               delete from
               proyecto_actividad
               where actividadid='$actividadid' and proyectoid='$proyectoid' 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
      //      print $ex->getMessage();
        }
    }
  
    public function _buscaractividad($proyectoid,$actividadid)
     {
        try{
            $sql=$this->_db->query("
               select * from proyecto_actividad
               where actividadid='$actividadid' and proyectoid='$proyectoid' 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

      public function _getProyectoxAreaxSemanaxActivo($proyecto,$area,$semanid)
     {
        try{
            $sql=$this->_db->query("
               select * from proyecto_actividad where proyectoid='$proyecto' and areaid='$area' and semanaid='$semanid' and estado='A'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

          public function _getProyectoxAreaxSemanaxActivoxInicio($proyecto,$area,$semanid)
     {
        try{
            $sql=$this->_db->query("
               select * from proyecto_actividad where proyectoid='$proyecto' and areaid='$area' and semanaid='$semanid' order by actividadid

               
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


     //public function _getActividadxProyectoxAreaxSemana($proyecto,$area,$semanid)
     public function _getActividadxProyectoxAreaxSemana($semanaid,$cat,$responsable,$proyectoid,$actividadid)
     {
        try{
            $sql=$this->_db->query("
                select *
                from proyecto_planificacion as pro  
                inner join proyecto_actividad as act
                on pro.proyectoid=act.proyectoid and pro.semanaid=act.semanaid and pro.areaid=act.areaid
                where pro.areaid='$cat' and pro.semanaid='$semanaid' 
                and pro.responsable='$responsable' 
                and pro.proyectoid='$proyectoid' 
                and act.actividadid='$actividadid' 
                order by  pro.proyectoid
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    
     public function _getActividadxProyectoxAreaxInicio($cat,$proyectoid,$actividadid)
     {
        try{
            $sql=$this->_db->query("
                select 
                nombre,actividadid,duracion,
                areaid,estado,consumidas,
                planificadas  
                from proyecto_actividad 
                where estado='I'and 
                proyectoid='$proyectoid' 
                and actividadid='$actividadid' 
                and areaid='$cat'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

     public function _getVerificarxActividad($areaid,$proyectoid)
     {
        try{
            $sql=$this->_db->query("
                select * from 
                proyecto_actividad 
                where areaid='$areaid' 
                and estado='I' 
                and semanaid='inicio' 
                and proyectoid='$proyectoid'

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

     public function _getVerificarxActividadSemanal($actividadid,$proyectoid,$areaid,$semanaid)
     {
        try{
            $sql=$this->_db->query("
                select * from 
                proyecto_actividad 
                where areaid='$areaid' 
                and estado='R' 
                and semanaid='$semanaid' 
                and proyectoid='$proyectoid'
                and actividadid='$actividadid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getVerificarActividadNBSemanal($actividadid,$proyectoid,$areaid,$semanaid)
     {
        try{
            $sql=$this->_db->query("
                select * from 
                proyecto_actividad 
                where areaid='$areaid' 
                and estado='NB' 
                and semanaid='$semanaid' 
                and proyectoid='$proyectoid'
                and actividadid='$actividadid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

public function _eliminaractividadsemanal($responsable,$semana,$fecha,$actividadid,$proyectoid,$categoria)
     {
        try{
            $sql=$this->_db->query("
               delete from
               proyecto_tarea
               where asignado='$responsable' and semana='$semana' and fecha_tarea='$fecha' and actividadid='$actividadid' and proyectoid='$proyectoid' and areaid='$categoria' 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
      //      print $ex->getMessage();
        }
    }

   
    public function _eliminarActividadPlanificaxSemana($proyectoid,$actividadid,$areaid,$semanaid)
     {
        try{
            $sql=$this->_db->query("
               delete from
               proyecto_actividad
               where actividadid='$actividadid' and proyectoid='$proyectoid' and areaid='$areaid' and semanaid='$semanaid' and estado='A'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
      //      print $ex->getMessage();
        }
    }

     public function _getNombreActividad($semanaid,$cat,$responsable,$proyectoid,$actividadid)
     {
        try{
            $sql=$this->_db->query("
                select *
                from proyecto_planificacion as pro  
                inner join proyecto_actividad as act
                on pro.proyectoid=act.proyectoid and pro.semanaid=act.semanaid and pro.areaid=act.areaid
                where pro.areaid='$cat' and pro.semanaid='$semanaid' 
                
                and pro.proyectoid='$proyectoid' 
                and act.actividadid='$actividadid' 
                order by  pro.proyectoid
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }
   
    public function _getVerificarActividadesProyecto($areaid,$proyectoid,$actividadid)
     {
        try{
            $sql=$this->_db->query("
                select * from 
                proyecto_actividad 
                where areaid='$areaid' 
                and proyectoid='$proyectoid'
                and actividadid='$actividadid'

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _eliminarActividadPlanificaxSemanaxInicio($proyectoid,$actividadid,$areaid,$semanaid)
     {
        try{
            $sql=$this->_db->query("
               delete from
               proyecto_actividad
               where actividadid='$actividadid' and proyectoid='$proyectoid' and areaid='$areaid' and semanaid='$semanaid' and estado='I'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
      //      print $ex->getMessage();
        }
    }

    public function _DatosActividadesInicialesxProyectoxArea($areaid,$proyectoid,$actividadid)
    {
        try{
            $sql=$this->_db->query("
                select 
                nombre, actividadid, duracion,
                areaid, estado, consumidas,
                planificadas, senior, ing_a, ing_b, ing_c,
                ing_d, ing_e, cad, administracion
                from proyecto_actividad 
                where estado='I'and 
                proyectoid='$proyectoid' 
                and actividadid='$actividadid' 
                and areaid='$areaid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _DatosActividadesSemanaxProyectoxArea($areaid,$proyectoid,$actividadid,$semanaid)
    {
        try{
            $sql=$this->_db->query("
                select 
                    actividadid,
                    proyectoid,
                    duracion_nivel2,
                    duracion_nivel3,
                    duracion,
                    consumidas_nivel2,
                    consumidas_nivel3,
                    consumidas
                from proyecto_actividad
                where estado='A'and 
                    proyectoid='$proyectoid' 
                    and actividadid='$actividadid' 
                    and areaid='$areaid'
                    and semanaid='$semanaid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _SemanasProgramadasxProyectoxArea($areaid,$proyectoid,$actividadid,$semana)
    {
        try{
            $sql=$this->_db->query("
                select distinct 
                    semanaid, proyectoid  
                from proyecto_actividad 
                where
                    estado='A'and 
                    proyectoid='$proyectoid' 
                    and actividadid='$actividadid' 
                    and areaid='$areaid'
                    and semanaid::INT<='$semana'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

     public function _ListarDatosInicialesActividadesxProyecto($actividadid,$proyectoid,$areaid)
     {
        try{
            $sql=$this->_db->query("
                select * from 
                proyecto_actividad 
                where areaid='$areaid' 
                and estado='I' 
                and proyectoid='$proyectoid'
                and actividadid='$actividadid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _ListarDatosActividadesxProyecto($actividadid,$proyectoid,$areaid,$semanaid)
     {
        try{
            $sql=$this->_db->query("
                select * from 
                proyecto_actividad 
                where areaid='$areaid' 
                and estado='A' 
                and proyectoid='$proyectoid'
                and actividadid='$actividadid'
                and semanaid='$semanaid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


public function _ListCategoriaInicialesxProyecto($proyectoid)
     {
        try{
            $sql=$this->_db->query("
              select distinct (pro.categoriaid),cat.orden from proyecto_tarea as pro 
              inner join 
              proyecto_categoria as cat
              on pro.categoriaid=cat.categoriaid
              where pro.proyectoid='$proyectoid' and pro.estado='I' and pro.etapa='INICIO' order by cat.orden asc


              
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

public function _ListActividadesInicialesxProyecto($proyectoid)
     {
        try{
            $sql=$this->_db->query("
            select 
            distinct act.actividadid
            from proyecto_tarea as pro 
            inner join 
            proyecto_actividad as act
            on pro.actividadid=act.actividadid and pro.proyectoid=act.proyectoid
            where pro.proyectoid='$proyectoid' order by act.actividadid  asc
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function SPC_getActividades($actividadid,$proyectoid)

     {
        try{
            $sql=$this->_db->query("
            select *
            from proyecto_actividad
            where proyectoid='$proyectoid' and actividadid='$actividadid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


public function SPC_ListCategoriaInicialesxProyectoxArea($proyectoid,$areaid)
     {
        try{
            $sql=$this->_db->query("
            select 
            distinct (pro.categoriaid),cat.orden 
            from proyecto_tarea as pro 
            inner join 
            proyecto_categoria as cat
            on pro.categoriaid=cat.categoriaid
            where pro.proyectoid='$proyectoid' and pro.estado='I' 
            and pro.etapa='INICIO' and cat.areaid='$areaid' order by cat.orden asc

           
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

public function SPC_ListActividadesInicialesxProyectoxArea($proyectoid,$areaid)
     {
        try{
            $sql=$this->_db->query("
            select 
            distinct act.actividadid
            from proyecto_tarea as pro 
            inner join 
            proyecto_actividad as act
            on pro.actividadid=act.actividadid and pro.proyectoid=act.proyectoid
            inner join 
            proyecto_categoria as cat on pro.categoriaid=cat.categoriaid
            
            where pro.proyectoid='$proyectoid' and pro.estado='I' 
            and pro.etapa='INICIO' and cat.areaid='$areaid' 
             order by act.actividadid  asc
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function SPC_getActividadProyecto($proyectoid)
     {
        try{
            $sql=$this->_db->query("
                select * from 
                proyecto_actividad 
                where  estado='P' 
        
                and proyectoid='$proyectoid'

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    

public function SPC_listCategoriaxAreaxSemana($proyectoid,$actividadid,$etapa,$estado,$semana,$areaid)
     {
        try{
            $sql=$this->_db->query("
            select 
              distinct (cat.categoriaid),cat.orden,cat.rate,cat.tarifa
            from proyecto_tarea as pro 
            inner join 
              proyecto_actividad as act
              on pro.actividadid=act.actividadid and pro.proyectoid=act.proyectoid
            inner join 
              proyecto_categoria as cat on pro.categoriaid=cat.categoriaid
            where pro.proyectoid='$proyectoid' and pro.estado='$estado' 
              and pro.etapa='$etapa' and cat.areaid='$areaid' and pro.actividadid='$actividadid'
            order by cat.orden asc
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

  
    
}


