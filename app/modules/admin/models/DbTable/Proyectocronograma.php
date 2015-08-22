<?php
class Admin_Model_DbTable_Proyectocronograma extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_cronograma';
    protected $_primary = array("codigo_prop_proy", "codigo_cronograma", "revision_cronograma", "proyectoid");

     /* Lista toda las Personas */
    public function _getProyectoxcronogramaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("proyecto_cronograma");
                else $select->from("proyecto_cronograma",$attrib);
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
            print "Error: Read Filter Course ".$e->getMessage();
        }
    }

    public function _getCronogramaxActivo($proyectoid){
        try{
            $sql=$this->_db->query("select *
                from proyecto_cronograma where proyectoid='".$proyectoid."' and state='A'");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


 
}
