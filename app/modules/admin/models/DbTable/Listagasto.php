<?php 
class Admin_Model_DbTable_Listagasto extends Zend_Db_Table_Abstract
{
    protected $_name = 'lista_gasto';
    protected $_primary = array("gastoid","tipo_gasto","fecha_vigencia");

     /* Lista toda los gastos */    
    public function _getGastosAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos".$e->getMessage();
        }
    }
}