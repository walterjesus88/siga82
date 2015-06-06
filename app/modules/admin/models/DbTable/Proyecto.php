<?php 
class Admin_Model_DbTable_Proyecto extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto';
    protected $_primary = array("codigo_prop_proy", "proyectoid");

     /* Lista toda las Personas */    
    public function _getProyectoAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }


    public function _getOne($pk=null)
    {
        try{
            if ($pk['codigo_prop_proy']=='' ||  $pk['proyectoid']=='' ) return false;
            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' and proyectoid='".$pk['proyectoid']."' ";
            $row = $this->fetchRow($where);
            if ($row) return $row->toArray();
            return false;
        }catch (Exception $ex){
            print "Error: Get Info Distribution ".$ex->getMessage();
        }
    }

    public function _getOnexcodigoproyecto($pk=null)
    {
        try{
            if ($pk['proyectoid']=='' ) return false;
            $where = "proyectoid='".$pk['proyectoid']."' ";
            $row = $this->fetchRow($where);
            if ($row) return $row->toArray();
            return false;
        }catch (Exception $ex){
            print "Error: Get Info Distribution ".$ex->getMessage();
        }
    }


    public function _save($data)
    {
        try{
            if ($data['codigo_prop_proy']=='' ||  $data['codigo_prop_proy']=='' ) return false;
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
                       pro.nombre_proyecto,pro.gerente_proyecto 
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

    public function _getProyectosTodosAnddes(){
        try{
            $sql=$this->_db->query("
                select * from proyecto
                where not proyectoid in ('1','2') order by fecha_inicio desc;
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
                where not proyectoid in ('1','2') and gerente_proyecto='$gerente' order by proyectoid asc;
                ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

}

