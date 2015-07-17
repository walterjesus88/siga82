<?php 
class Admin_Model_DbTable_Hojaresumen extends Zend_Db_Table_Abstract
{
    protected $_name = 'hoja_resumen';
    protected $_primary =  array("codigo_prop_proy","proyectoid", "revision_hojaresumen", "propuestaid", "revision_propuesta");
   

     /* Lista toda las Personas */    
    public function _getOne($pk=null)
    {
        try{
            if ($pk['codigo_prop_proy']=='' ||  $pk['proyectoid']==''  ||  $pk['revision_hojaresumen']==''  ||  $pk['propuestaid']==''  ||  $pk['revision_propuesta']=='' ) return false;
            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and proyectoid='".$pk['proyectoid']."' and revision_hojaresumen='".$pk['revision_hojaresumen']."' and propuestaid='".$pk['propuestaid']."' and revision_propuesta='".$pk['revision_propuesta']."' ";
            $row = $this->fetchRow($where);
            if ($row) return $row->toArray();
            return false;
        }catch (Exception $ex){
            print "Error: Get Info Distribution ".$ex->getMessage();
        }
    }


    // public function _getProyectoAll(){
    //     try{
    //         $f = $this->fetchAll();
    //         if ($f) return $f->toArray ();
    //         return false;
    //     }catch (Exception $e){
    //         print "Error: Al momento de leer todas las personas".$e->getMessage();
    //     }
    // }



    // //Funcion para obtener un proyecto en particular para el modulo de reportes
    // public function _show($id)
    // {
    //     $where = "codigo_prop_proy = '".$id."'";
    //     $row = $this->fetchRow($where);
    //     if ($row) return $row->toArray();
    // }

    // //para obtener todos los proyectos de un cliente especifico
    // public function _getProyectoxCliente($cliente){
    //     try{
    //         $sql=$this->_db->query("select codigo_prop_proy, nombre_proyecto, clienteid 
    //             from proyecto where clienteid='".$cliente."' order by codigo_prop_proy;");
    //         $row=$sql->fetchAll();
    //         return $row;           
    //         }  
            
    //        catch (Exception $ex){
    //         print $ex->getMessage();
    //     }
    // }



    // public function _getOnexcodigoproyecto($pk=null)
    // {
    //     try{
    //         if ($pk['proyectoid']=='' ) return false;
    //         $where = "proyectoid='".$pk['proyectoid']."' ";
    //         $row = $this->fetchRow($where);
    //         if ($row) return $row->toArray();
    //         return false;
    //     }catch (Exception $ex){
    //         print "Error: Get Info Distribution ".$ex->getMessage();
    //     }
    // }


    public function _save($data)
    {
        try{
            if ($data['codigo_prop_proy']=='' ||  $data['codigo_prop_proy']=='' ||  $data['revision_hojaresumen']==''  ||  $data['propuestaid']==''  ||  $data['revision_propuesta']=='') return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }
 
    public function _update($data,$pk)
    {
        try{
            if ($pk['codigo_prop_proy']=='' ||  $pk['proyectoid']=='' ) return false;
            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and proyectoid='".$pk['proyectoid']."' ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }

    public function _delete($pk=null)
    {
        try{
            if ($pk['codigo_prop_proy']=='' ||  $pk['proyectoid']=='' ) return false;

            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and proyectoid='".$pk['proyectoid']."' ";
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }

    public function _buscarProyecto($proyecto){
        try{
            $sql=$this->_db->query("
                select pro.codigo_prop_proy,pro.proyectoid,
                       pro.nombre_proyecto,pro.gerente_proyecto 
                       from proyecto as pro 
                inner join propuesta as prop
                on pro.propuestaid=prop.propuestaid  and pro.codigo_prop_proy=prop.codigo_prop_proy and pro.revision=prop.revision
                inner join cliente as cli on
                prop.clienteid=cli.clienteid 
                where lower(pro.nombre_proyecto) like '%$proyecto%' 
                or lower(cli.nombre_comercial) like '%$proyecto%' 
                order by pro.nombre_proyecto asc");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _buscarProyectoxReplicon($proyecto){
        try{
            $sql=$this->_db->query("
                select pro.codigo_prop_proy,pro.proyectoid,
                       pro.nombre_proyecto,pro.gerente_proyecto ,pro.clienteid
                       from proyecto as pro 
                inner join cliente as cli on
                pro.clienteid=cli.clienteid 
                where lower(pro.nombre_proyecto) like '%$proyecto%' 
                or lower(cli.nombre_comercial) like '%$proyecto%' or pro.proyectoid like '%$proyecto%'
                order by pro.nombre_proyecto asc");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _buscarProyectodetalles($proyecto,$codigo_prop_proy){
        try{
            $sql=$this->_db->query("
               select pro.codigo_prop_proy,pro.descripcion,pro.gerente_proyecto,pro.control_documentario,pro.fecha_inicio,pro.fecha_cierre,pro.nombre_proyecto,pro.propuestaid,pro.proyectoid,cli.nombre_comercial,uni.nombre,
               uni.unidad_mineraid,pro.nombre_proyecto,pro.gerente_proyecto,pro.clienteid,cli.ruc,pro.contactoid
               from proyecto as pro  

               inner join cliente as cli 
               on pro.clienteid=cli.clienteid 

               inner join unidad_minera as uni
               on uni.unidad_mineraid=pro.unidad_mineraid
             
               where codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyecto'
               
                ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _listProyectoxRepliconxEstado($proyecto,$estado){
        try{
            $sql=$this->_db->query("
                select *
                       from proyecto 
                where estado='A'
                order by pro.nombre_proyecto asc");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getProyectosTodosAnddes(){
        try{
            $sql=$this->_db->query("
                select * from proyecto

                where not proyectoid in ('1','2','3','4','5','1590.10.01','1590.10.02','1590.10.03') order by proyectoid desc;

                ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getProyectosxGerente($gerente){
        try{
            $sql=$this->_db->query("
                select * from proyecto

                where not proyectoid in ('1','2','3','4','5','1590.10.01','1590.10.02','1590.10.03') and gerente_proyecto='$gerente' order by proyectoid asc;


                ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getGerentes(){
         try{
            $sql=$this->_db->query("select distinct usu.dni, pro.gerente_proyecto from proyecto as pro inner join
                usuario as usu on pro.gerente_proyecto = usu.uid order by pro.gerente_proyecto;");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

}

