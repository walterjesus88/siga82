<?php 
class Admin_Model_DbTable_Gastorendicion extends Zend_Db_Table_Abstract
{
    protected $_name = 'gasto_rendicion';
    protected $_primary = array("numero", "uid", "dni");
    protected $_sequence ="s_rendicion";

     /* Lista toda los GAstos */    
    public function _getGastorendicionAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos rendicion".$e->getMessage();
        }
    }

    public function _save($data)
    {
        try{
            if ($data['fecha']=='' ||  $data['uid']=='' ||  $data['dni']=='') return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

   public function _delete($numero,$uid,$dni){
        try{
            if ($numero=='') return false;
            return $this->delete("numero='$numero' and uid='$uid' and dni='$dni' ");
        }catch (Exception $ex){
            print "Error: Eliminando una rendicion ".$ex->getMessage();
        }
    }

    public function _update($data,$str){
        try{
            if ($str['numero']=='' ||  $str['uid']=='' || $str['dni']=='') return false;
            $where = "numero = '".$str['numero']."' and uid='".$str['uid']."' and dni='".$str['dni']."'";
            return $this->update($data,$where);
        }catch (Exception $ex){
            print "Error: Actualizando un registro ".$ex->getMessage();
        }
    }

    public function _getOneXfecha($where=array()){
        try{
            if ($where['fecha']=='' || $where['uid']=='' || $where['dni']=='') return false;
            $wherestr="fecha = '".$where['fecha']."' and uid='".$where['uid']."' and dni='".$where['dni']."'";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One ".$e->getMessage();
        }
    }

    public function _getOneXnumero($where=array()){
        try{
            if ($where['numero']=='' || $where['uid']=='' || $where['dni']=='') return false;
            $wherestr="numero = '".$where['numero']."' and uid='".$where['uid']."' and dni='".$where['dni']."'";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One ".$e->getMessage();
        }
    }

    public function _getrendicionXestadoXproyecto($estado, $codigo_prop_proy, $proyectoid){
        try{
            $sql=$this->_db->query("
               select DISTINCT (r.uid), r.numero from gasto_rendicion r inner join gasto_persona p 
                    on p.uid = r.uid and p.dni = r.dni and p.numero_rendicion = r.numero
                    where p.codigo_prop_proy = '$codigo_prop_proy' 
                    and p.proyectoid = '$proyectoid' and p.estado_rendicion = '$estado' 
                    and r.estado = '$estado'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getOne($where=array()){
        try{
            if ($where['numero']=='') return false;
            $wherestr="numero = '".$where['numero']."'";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One  ".$e->getMessage();
        }
    }

    public function _getAllXuidXestado($where=array()){
        try{
            if ($where['estado']=='' || $where['uid']=='' || $where['dni']=='') return false;
            $wherestr="estado = '".$where['estado']."' and uid='".$where['uid']."' and dni='".$where['dni']."'";
            $row = $this->fetchAll($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One ".$e->getMessage();
        }
    }


        public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("gasto_rendicion");
                else $select->from("gasto_rendicion",$attrib);
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
            print "Error: Read Filter Gastos ".$e->getMessage();
        }
    }


        public function GastoxEstado($estado)
    {
        try{
            $sql=$this->_db->query("
                select numero_completo, nombre, fecha, estado
                from gasto_rendicion
                where estado='$estado'
            ");
            $row=$sql->fetchAll();
            return $row;
            }
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }
}