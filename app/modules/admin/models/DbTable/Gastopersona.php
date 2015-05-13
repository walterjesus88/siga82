<?php 
class Admin_Model_DbTable_Gastopersona extends Zend_Db_Table_Abstract
{
    protected $_name = 'gasto_persona';
    protected $_primary = array("codigo_prop_proy", "proyectoid", "revision", "ucategoriaid", "gastoid", "asignado", "fecha_gasto");

     /* Lista toda las Personas */    
    public function _getGastopersonaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos persona".$e->getMessage();
        }
    }

    public function _save($data)
    {
        try{
            if ($data['proyectoid']=='' ||  $data['codigo_prop_proy']=='' ||  $data['uid']=='' ||  $data['dni']=='' ||  $data['categoriaid']=='') return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{            
            if($where['proyectoid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("gasto_persona");
                else $select->from("gasto_persona",$attrib);
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
            print "Error: Read Filter Course ".$e->getMessage();
        }
    }


    public function _getOne($where=array()){
        try{
            if ($where['codigo_prop_proy']=='' || $where['proyectoid']=='' || $where['revision']=='' || $where['ucategoriaid']=='' || $where['gastoid']=='' || $where['asignado']=='' || $where['fecha_gasto']=='' ) return false;
            $wherestr="clienteid = '".$where['clienteid']."' ";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Add_reportacad_adm ".$e->getMessage();
        }
    }

    public function _getgastoProyectosXfecha($fecha_gasto, $uid, $dni){
        try{
            if ($fecha_gasto=='' || $uid=='' || $dni=='') return false;
            $sql=$this->_db->query("
               select proyectoid from gasto_persona 
               where fecha_gasto='$fecha_gasto' and uid='$uid' and dni='$dni' 
               group by proyectoid;
            ");
            $row=$sql->fetchAll();
            return $row;
            return false;
        }catch (Exception $e){
            print "Error: Read One Add ".$e->getMessage();
        }
    }

    public function _getgastoProyectoXfechaXactividad($where=array()){
        try{
            if ($where['fecha_gasto']=='' || $where['uid']=='' || $where['dni']=='' || $where['proyectoid']=='') return false;
            $wherestr = "fecha_gasto = '".$where['fecha_gasto']."' and uid = '".$where['uid'].
                        "' and dni = '".$where['dni']."' and proyectoid = '".$where['proyectoid'].
                        "' and actividadid IS NOT NULL";
            $row = $this->fetchAll($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Add ".$e->getMessage();
        }
    }
}