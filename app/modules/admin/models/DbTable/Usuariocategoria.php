<?php 
class Admin_Model_DbTable_Usuariocategoria extends Zend_Db_Table_Abstract
{
    protected $_name = 'usuario_categoria';
    protected $_primary = array('uid','dni','categoriaid','areaid','cargo');
    public function _getUsuariocategoriaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos persona".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{            
            if($where['uid']=='' || $where['dni']=='' ) return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("usuario_categoria");
                else $select->from("usuario_categoria",$attrib);
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

    public function _buscarUsuarioxAreaxCategoria($areaid,$categoriaid)
    {
        try{
            $sql=$this->_db->query("
                select * from usuario_categoria as ucat inner join persona as per on ucat.dni=per.dni
                where ucat.categoriaid='$categoriaid' and ucat.areaid='$areaid' and ucat.estado_sistema='A' and ucat.estado='A'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getUsuarioxPersona($uid,$dni)
    {
        try{
            $sql=$this->_db->query("
                select * from usuario_categoria 
                where uid='$uid' and dni='$dni' and estado_sistema='A' and estado='A'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }

}
