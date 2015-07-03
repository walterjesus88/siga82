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
               and uid='$uid' and dni='$dni' and cargo='$cargo' and etapa='$etapas' 
               
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

/*

         select *,tareo.estado as estado_tareopersona   from tareo_persona as tareo 
                inner join actividad as act
                on tareo.actividadid=act.actividadid and tareo.codigo_actividad=act.codigo_actividad 
                    and tareo.codigo_prop_proy=act.codigo_prop_proy
                    and tareo.revision=act.revision
                inner join proyecto as pro on tareo.codigo_prop_proy=pro.codigo_prop_proy
                    and tareo.revision=pro.revision and tareo.proyectoid=pro.proyectoid 
                where tareo.uid='$uid' and tareo.dni='$dni' and tareo.semanaid='$semanaid' 
                and tareo.etapa like 'INICIO%'  order by tareo.proyectoid,tareo.actividadid,tipo_actividad desc 


                select * ,t.estado as estado_tareopersona from tareo_persona as t
inner join actividad a
on t.actividadid=a.actividadid and t.codigo_actividad=a.codigo_actividad 
                    and t.codigo_prop_proy=a.codigo_prop_proy
                    and t.revision=a.revision
                    
where t.uid='waldo.huallanca' and t.dni='44083167' and t.semanaid='22' and t.etapa like 'INICIO%' 
order by t.proyectoid,t.actividadid,t.tipo_actividad desc

                */

    public function _getTareoxPersonaxSemana($uid,$dni,$semanaid)
     {
        try{
            $sql=$this->_db->query("
                select *,tareo.estado as estado_tareopersona   from tareo_persona as tareo 
                inner join actividad as act
                on tareo.actividadid=act.actividadid and tareo.codigo_actividad=act.codigo_actividad 
                    and tareo.codigo_prop_proy=act.codigo_prop_proy
                    and tareo.revision=act.revision
                inner join proyecto as pro on tareo.codigo_prop_proy=pro.codigo_prop_proy
                     and tareo.proyectoid=pro.proyectoid 
                where tareo.uid='$uid' and tareo.dni='$dni' and tareo.semanaid='$semanaid' 
                and tareo.etapa like 'INICIO%'  order by act.propuestaid desc,tareo.proyectoid,tareo.actividadid,tipo_actividad desc 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getTareoxPersonaxSemanaxActividadid($uid,$dni,$semanaid,$actividad_padre,$actividadid,$codigo_actividad,$codigo_prop_proy,$proyectoid,$revision)
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
                and tareo.actividadid='$actividadid' and tareo.actividad_padre='$actividad_padre'
                and tareo.codigo_actividad='$codigo_actividad' and tareo.codigo_prop_proy='$codigo_prop_proy'
                and tareo.proyectoid='$proyectoid' and tareo.revision='$revision'
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
            //if ($data['areaid']=='' ||  $data['codigo_prop_proy']=='' ) return false;
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
            //print "Error: Update Distribution".$e->getMessage();
        }
    }

    public function _deleteTareasEtapaEjecucion($pk=null)
    {
        try{
            if ($pk['codigo_prop_proy']=='' ||  $pk['codigo_actividad']=='' ) return false;

            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and codigo_actividad='".$pk['codigo_actividad']."' 
            and actividadid='".$pk['actividadid']."' 
            and revision='".$pk['revision']."' 
            and actividad_padre='".$pk['actividad_padre']."' 
            and proyectoid='".$pk['proyectoid']."' 
            and semanaid='".$pk['semanaid']."' 
              and uid='".$pk['uid']."' 
            and cargo='".$pk['cargo']."' 
            and etapa='".$pk['etapa']."' 
                   and tipo_actividad='".$pk['tipo_actividad']."' 
            ";
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
          //  print "Error: Update Distribution".$e->getMessage();
        }
    }


    public function _getHorasRealxDiaXWalter($data = null){
       try {
           if ($data['escid'] == '' || $data['uid'] == '' || $data['curid'] == '') return false;
           $Registrationults = $this->_db->query("
               select * from courses_pending_wjrs('".$data['escid']."', '".$data['uid']."', '".$data['curid']."')");
           $rows = $results->fetchAll();
           if ($rows) return $rows;
           return false;
       } catch (Exception $e) {
           print "Error: Read Courses per Curriculum... ".$e->getMessage();
       }
   }

   public function _getHorasRealxDia($semanaid,$fecha_tarea,$uid,$dni)
     {
        try{
            $sql=$this->_db->query("
              select * from tareo_persona_horas_reales('$semanaid','$fecha_tarea','$uid','$dni')
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
           // print $ex->getMessage();
        }
    }

    public function _getDuplicarTareo($fecha,$vsemana,$vuid)    
     {
        try{
            $sql=$this->_db->query("
              select * from duplicando_tareo('$fecha','$vsemana','$vuid')
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
           // print $ex->getMessage();
        }
    }

    public function _getHorasRealxTipo($semanaid,$uid,$dni,$cargoid,$tipo)
     {
        try{
            $sql=$this->_db->query("
               select * from tareo_persona_horas_reales_tipoactividad('$semanaid','$uid','$dni','$cargoid','$tipo')
                
              
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
           // print $ex->getMessage();
        }
    }


    
 public function _deleteTareasxSemana($pk=null)
    {
        try{
            if ($pk['codigo_prop_proy']=='' ||  $pk['codigo_actividad']=='' ) return false;


            $where = "
            codigo_prop_proy = '".$pk['codigo_prop_proy']."' 
            and codigo_actividad='".$pk['codigo_actividad']."' 
            and actividadid='".$pk['actividadid']."' 
            and revision='".$pk['revision']."' 
            and actividad_padre='".$pk['actividad_padre']."' 
            and proyectoid='".$pk['proyectoid']."' 
            and semanaid='".$pk['semanaid']."' 
            and uid='".$pk['uid']."' 
            and dni='".$pk['dni']."' 
            and tipo_actividad='".$pk['tipo_actividad']."' 
            and actividad_generalid='".$pk['actividad_generalid']."' 
            ";
            //print_r($where);exit();
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }

   public function _deleteTareasxSemanaX($pk=null)
    {
        try{
            if ($pk['codigo_prop_proy']=='' ||  $pk['codigo_actividad']=='' ) return false;
            
            $where = "
            codigo_prop_proy = '".$pk['codigo_prop_proy']."' 
            and codigo_actividad='".$pk['codigo_actividad']."' 
            and actividadid='".$pk['actividadid']."' 
            and revision='".$pk['revision']."' 
            and actividad_padre='".$pk['actividad_padre']."' 
            and proyectoid='".$pk['proyectoid']."' 
            and semanaid='".$pk['semanaid']."' 
            and uid='".$pk['uid']."' 
            and dni='".$pk['dni']."' 
            and tipo_actividad='".$pk['tipo_actividad']."' 

            ";
            //print_r($where);
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }

    public function deletenb($codigo_prop_proy,$codigo_actividad,$actividadid,$revision,$actividad_padre,$proyectoid,$semanaid,$uid,$dni,$tipo_actividad,$etapa){
        try{
           
            return $this->delete("codigo_prop_proy='$codigo_prop_proy' and codigo_actividad='$codigo_actividad' and actividadid='$actividadid'
                                  and revision='$revision' and actividad_padre='$actividad_padre' and proyectoid='$proyectoid' and semanaid='$semanaid'
                                  and uid='$uid' and dni='$dni' and tipo_actividad='$tipo_actividad' and etapa in ('INICIO-NB-','EJECUCION-NB-')   ");
        }catch (Exception $ex){
            print "Error: Eliminando un registro de la Persona".$ex->getMessage();
        }
    }


    public function _getTareoxPersonaxSemanaxActividadidxReplicon($uid,$dni,$semanaid,$actividad_padre,$actividadid,$codigo_actividad,$codigo_prop_proy,$proyectoid,$revision)
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
                and tareo.actividadid='$actividadid' and tareo.actividad_padre='$actividad_padre'
                and tareo.codigo_actividad='$codigo_actividad'
                and tareo.proyectoid='$proyectoid' 
                and tareo.etapa like 'INICIO%'  order by tareo.proyectoid,tareo.actividadid,tipo_actividad desc 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getConteotareo($actividad_generalid,$semanaid,$codigo_actividad,$tipo_actividad,$codigo_prop_proy,$proyectoid,$revision,$actividadid,$uid,$dni)
     {
        try{
            $sql=$this->_db->query("
              select  count(*) from tareo_persona 
              where actividad_generalid='$actividad_generalid' and tipo_actividad='$tipo_actividad' and codigo_prop_proy='$codigo_prop_proy'
              and proyectoid='$proyectoid' and revision='$revision' and actividadid='$actividadid' and uid='$uid' and dni='$dni' 
              and codigo_actividad = '$codigo_actividad' and semanaid='$semanaid'

            ");
            // print_r($sql);
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getConteotareo2($semanaid,$codigo_actividad,$tipo_actividad,$codigo_prop_proy,$proyectoid,$revision,$actividadid,$uid,$dni)
     {
        try{
            $sql=$this->_db->query("
              select  count(*) from tareo_persona 
              where tipo_actividad='$tipo_actividad' and codigo_prop_proy='$codigo_prop_proy'
              and proyectoid='$proyectoid' and revision='$revision' and actividadid='$actividadid' and uid='$uid' and dni='$dni' 
              and codigo_actividad = '$codigo_actividad' and semanaid='$semanaid' and (etapa ='INICIO-NB-' or etapa ='INICIO' or etapa ='EJECUCION-NB-' or etapa ='EJECUCION')

            ");
            // print_r($sql);
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("tareo_persona");
                else $select->from("tareo_persona",$attrib);
                //print_r($where);
                foreach ($where as $atri=>$value){
                    $select->where("$atri = ?", $value);                    
                }
                if ($orders<>null || $orders<>"") {
                    if (is_array($orders))
                        $select->order($orders);
                }
                $results = $select->query();
                $rows = $results->fetchAll();
                //print_r($results);
                if ($rows) return $rows;
                return false;
        }catch (Exception $e){
            print "Error: Read Filter competencia ".$e->getMessage();
        }
    }




    public function _getOne($pk=null)
    {
        try{
            //if ($pk['codigo_prop_proy']=='' ||  $pk['proyectoid']=='' ) return false;
             

            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and codigo_actividad='".$pk['codigo_actividad']."' and actividadid='".$pk['actividadid']."'
            and revision='".$pk['revision']."' and actividad_padre='".$pk['actividad_padre']."' and proyectoid='".$pk['proyectoid']."' and semanaid='".$pk['semanaid']."'
            and fecha_tarea='".$pk['fecha_tarea']."' and uid='".$pk['uid']."' and dni='".$pk['dni']."' and cargo='".$pk['cargo']."' and fecha_planificacion='".$pk['fecha_planificacion']."'
            and etapa='".$pk['etapa']."'  and tipo_actividad='".$pk['tipo_actividad']."'  ";
            $row = $this->fetchRow($where);

            //print_r($row);
            if ($row) return $row->toArray();
            return false;
        }catch (Exception $ex){
            print "Error: Get Info Distribution ".$ex->getMessage();
        }
    }

    public function _updateX($data,$pk)
    {
        try{
            //if ($pk['codigo_prop_proy']=='' ||  $pk['propuestaid']=='' ||  $pk['revision']=='' ) return false;
            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and codigo_actividad='".$pk['codigo_actividad']."' and actividadid='".$pk['actividadid']."'
            and revision='".$pk['revision']."' and actividad_padre='".$pk['actividad_padre']."' and proyectoid='".$pk['proyectoid']."' and semanaid='".$pk['semanaid']."'
            and fecha_tarea='".$pk['fecha_tarea']."' and uid='".$pk['uid']."' and dni='".$pk['dni']."' and cargo='".$pk['cargo']."' and fecha_planificacion='".$pk['fecha_planificacion']."'
            and etapa='".$pk['etapa']."'  and tipo_actividad='".$pk['tipo_actividad']."'  ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }


//Funcion que devuelve a los usuarios registrados en la tabla ordenados alfabeticamente y unicos
    public function _getUsuarios(){
      try{
            $sql=$this->_db->query("
              select  distinct uid, dni from tareo_persona order by uid");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


//Funcion que devuelve los datos requeridos para reporte
    public function _getReporte($where=array()){
      try{
            $condicion = '';
            if ($where['codigo_prop_proy'] != null) {
              $condicion = " where tareo.codigo_prop_proy='".$where['codigo_prop_proy']."'";       
            }
            
            $sql=$this->_db->query("select tareo.codigo_prop_proy, tareo.codigo_actividad, tareo.dni, 
                tareo.uid, equipo.rate_proyecto, pro.proyectoid, tareo.tipo_actividad, unimin.nombre, 
                pro.nombre_proyecto, pro.estado, sum(cast((case when tareo.h_real='' then '0' else tareo.h_real end) as float)) as h_real_total
                from tareo_persona as tareo 
                inner join equipo as equipo
                on tareo.codigo_prop_proy=equipo.codigo_prop_proy and tareo.proyectoid=equipo.proyectoid and tareo.uid=equipo.uid
                inner join proyecto as pro 
                on tareo.codigo_prop_proy=pro.codigo_prop_proy and tareo.proyectoid=pro.proyectoid
                inner join unidad_minera as unimin 
                on pro.unidad_mineraid=unimin.unidad_mineraid".$condicion.
                " group by tareo.codigo_prop_proy, tareo.codigo_actividad, tareo.uid, tareo.dni, 
                equipo.rate_proyecto, pro.proyectoid, tareo.tipo_actividad, unimin.nombre, 
                pro.nombre_proyecto, pro.estado");

            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    //Funcion que devuelve las horas reales de cada persona por proyecto por actividad
    public function _getHorasxUidxCppxCa($where=array()){
      try {
        $sql = $this->_db->query("select semanaid, fecha_tarea, h_real from tareo_persona where uid='".$where['uid']."' and
          codigo_prop_proy='".$where['codigo_prop_proy']."' and codigo_actividad='".$where['codigo_actividad'].
          "';");
        $row=$sql->fetchAll();
        return $row;  
      } catch (Exception $ex) {
        print $ex->getMessage();
      }
    }



}