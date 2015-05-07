<?php 
class Admin_Model_DbTable_Usuariocategoria extends Zend_Db_Table_Abstract
{
    protected $_name = 'usuario_categoria';
    protected $_primary = array("ucategoriaid");

     /* Lista toda las Personas */    
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

}
