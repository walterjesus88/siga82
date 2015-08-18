<?php 
class Admin_Model_DbTable_Performancedetalle extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_performance_detalle';
    protected $_primary = array("codigo_prop_proy", "codigo_actividad", "actividadid", "cronogramaid", "codigo_cronograma", "revision_cronograma", "proyectoid", "codigo_performance", "fecha_performance");   

     /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("proyecto_performance_detalle");
                else $select->from("proyecto_performance_detalle",$attrib);
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
 

}