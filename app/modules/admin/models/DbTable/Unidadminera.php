<?php 
class Admin_Model_DbTable_Unidadminera extends Zend_Db_Table_Abstract
{
    protected $_name = 'unidadminera';
    protected $_primary = array("unidad_mineraid", "clienteid");

     /* Lista toda las Personas */    
    public function _getUnidadmineraAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las -".$e->getMessage();
        }
    }
 


}