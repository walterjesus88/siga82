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
 


}