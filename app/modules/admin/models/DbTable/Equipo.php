<?php 
class Admin_Model_DbTable_Equipo extends Zend_Db_Table_Abstract
{
    protected $_name = 'equipo';
    protected $_primary = array("codigo_prop_proy","proyectoid","categoriaid");   

     /* Lista toda las Personas */    
    public function _getEquipoAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['codigo_prop_proy']=='' || $where['proyectoid']=='' || $where['categoriaid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("equipo");
                else $select->from("equipo",$attrib);
                foreach ($where as $atri=>$value){
                    $select->where("$atri = ?", $value);
                }
                if ($orders<>null || $orders<>"") {
                    if (is_array($orders))
                        $select->order($orders);
                }
                $results = $select->query();
                $rows = $results->fetchAll();
                if ($rows) return $rows;
                return false;
        }catch (Exception $ex){
            print $ex->getMessage();

        }
    }

    // public function _getOne($where=array()){
    //     try{
    //         if ($where['clienteid']=='' ) return false;
    //         $wherestr="clienteid = '".$where['clienteid']."' ";
    //         $row = $this->fetchRow($wherestr);
    //         if($row) return $row->toArray();
    //         return false;
    //     }catch (Exception $e){
    //         print "Error: Read One Add_reportacad_adm ".$e->getMessage();
    //     }
    // }

 


}