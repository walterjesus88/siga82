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



    //Funcion para obtener un proyecto en particular para el modulo de reportes
    public function _show($id)
    {
        $where = "codigo_prop_proy = '".$id."'";
        $row = $this->fetchRow($where);
        if ($row) return $row->toArray();
    }

    //para obtener todos los proyectos de un cliente especifico
    public function _getProyectoxCliente($cliente){
        try{
            $sql=$this->_db->query("select codigo_prop_proy, nombre_proyecto, clienteid
                from proyecto where clienteid='".$cliente."' order by codigo_prop_proy;");
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
                select *,p.estado as estado_proyecto from proyecto as p
                inner join cliente as c on
                p.clienteid=c.clienteid
                where not p.proyectoid in ('1','2','3','4','5','1590.10.01','1590.10.02','1590.10.03') order by p.proyectoid desc;

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
                select *, p.estado as estado_proyecto from proyecto as p
                inner join cliente as c on
                p.clienteid=c.clienteid
                where p.gerente_proyecto='$gerente' order by p.proyectoid desc;
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

    public function _getCD()
    {
      try {
        $sql = $this->_db->query("select distinct control_documentario
          from proyecto order by control_documentario");
        $row = $sql->fetchAll();
        return $row;
      }
      catch (Exception $ex) {
        print $ex->getMessage();
      }
    }

    public function _getCargabyCD($cd)
    {
      try {
        $sql = $this->_db->query("select estado, count(estado) from proyecto
        where control_documentario = '".$cd."' group by estado");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getTipoProyecto()
    {
      try {
        $sql = $this->_db->query("select distinct tipo_proyecto from proyecto
        order by tipo_proyecto");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getAllExtendido($estado)
    {
      try {
        $sql = $this->_db->query("select pro.codigo_prop_proy, pro.proyectoid, cli.nombre_comercial,
        pro.nombre_proyecto, pro.gerente_proyecto, pro.control_proyecto,
        pro.control_documentario, pro.estado
        from proyecto as pro inner join cliente as cli
        on pro.clienteid = cli.clienteid where pro.estado='".$estado."'");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getOnexProyectoidExtendido($data=null)
    {
      try {
        $sql = $this->_db->query("select pro.codigo_prop_proy, pro.proyectoid, cli.clienteid,
        cli.nombre_comercial, pro.nombre_proyecto, pro.estado, uni.nombre,
        pro.fecha_inicio, pro.fecha_cierre, pro.control_documentario, pro.gerente_proyecto,
        pro.tipo_proyecto, pro.descripcion
        from proyecto as pro inner join cliente as cli
        on pro.clienteid = cli.clienteid
        inner join unidad_minera as uni
        on pro.unidad_mineraid = uni.unidad_mineraid
        where proyectoid = '".$data['proyectoid']."'");
        $row = $sql->fetchAll();
        return $row[0];
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _updateControlDocumentario($proyectoid, $cd)
    {
      try {
        $sql = $this->_db->query("update proyecto
        set control_documentario='".$cd."'
        where proyectoid='".$proyectoid."'");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getUbicacionesxCarpeta($carpetaid)
    {
      try {
        if ($carpetaid == 8) {
          $sql = $this->_db->query("select estado, count(estado) from proyecto
          where unidad_red is null group by estado");
        } else {
          $sql = $this->_db->query("select estado, count(estado) from proyecto
          where unidad_red = ".$carpetaid." group by estado");
        }
        $rows = $sql->fetchAll();
        return $rows;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }
}
