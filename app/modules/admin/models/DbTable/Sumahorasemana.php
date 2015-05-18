<?php 
class Admin_Model_DbTable_Sumahorasemana extends Zend_Db_Table_Abstract
{
    protected $_name = 'suma_controlsemana';
    protected $_primary = array("semanaid", "uid", "dni", "cargo");   

     /* Lista toda las Personas */
 

    public function _getOne($where=array()){
        try {
            //if ($where["dni"]=='') return false;
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."' and cargo = '".$where['cargo']."'  ";
                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _save($data)
    {
        try{
            //if ($data['codigo_prop_proy']=='' ||  $data['codigo_prop_proy']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }


}