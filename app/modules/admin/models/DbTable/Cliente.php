<?php 
class Admin_Model_DbTable_Cliente extends Zend_Db_Table_Abstract
{
    protected $_name = 'cliente';
    protected $_primary = array("clienteid");

     /* Lista toda las Personas */    
    public function _getClienteAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }
 


}