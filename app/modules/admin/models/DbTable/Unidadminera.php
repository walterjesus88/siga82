<?php 
class Admin_Model_DbTable_Unidadminera extends Zend_Db_Table_Abstract
{
    protected $_name = 'unidad_minera';
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
 
  public function _getUnidadmineraxIndice($clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from unidad_minera 
               where clienteid='$clienteid'
               order by nombre asc 

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getOne($where=array()){
        try{
            if ($where['clienteid']=='' || $where['unidad_mineraid']=='') return false;
            $wherestr="clienteid = '".$where['clienteid']."' and unidad_mineraid = '".$where['unidad_mineraid']."' ";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Add_reportacad_adm ".$e->getMessage();
        }
    }
 

}