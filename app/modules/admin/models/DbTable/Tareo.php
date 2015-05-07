<?php 
class Admin_Model_DbTable_Tareo extends Zend_Db_Table_Abstract
{
    protected $_name = 'tareo';
    protected $_primary = array("codigo_prop_proy", "codigo_actividad", "actividadid", "revision", "actividad_padre", "categoriaid");

     /* Lista toda los Tareos */    
    public function _getTareoXUid($where=array()){
        try{
            if ($where['uid'] == '') return false;
            $wherestr="uid = '".$where['uid']."' ";
            $row = $this->fetchAll($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas los tareos".$e->getMessage();
        }
    }


}

