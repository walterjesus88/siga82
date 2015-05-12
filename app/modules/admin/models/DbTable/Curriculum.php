<?php 
class Admin_Model_DbTable_Curriculum extends Zend_Db_Table_Abstract
{
    protected $_name = 'curriculum';
    protected $_primary = array("curriculumid","dni");   

     /* Lista toda las Personas */

    public function _getOne($pk=null)
    {
        try{
            if ($pk['dni']=='' ) return false;
            $where = "dni = '".$pk['dni']."' ";
            $row = $this->fetchRow($where);
            if ($row) return $row->toArray();
            return false;
        }catch (Exception $ex){
            print "Error: Get Info curriculum ".$ex->getMessage();
        }
    }

}