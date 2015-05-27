<?php
class Admin_Model_DbTable_Tipogasto extends Zend_Db_Table_Abstract
{
    protected $_name = 'tipo_gasto';
    protected $_primary = array("tipo_moneda","fecha");

    public function _getOne($where=array()){
        try{
            if ($where['tipo_moneda']=='' || $where['fecha']=='') return false;
            $wherestr="tipo_moneda = '".$where['tipo_moneda']."' and fecha = '".$where['fecha']."'";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One ".$e->getMessage();
        }
    }
}