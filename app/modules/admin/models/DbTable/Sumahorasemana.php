<?php 
class Admin_Model_DbTable_Sumahorasemana extends Zend_Db_Table_Abstract
{
    protected $_name = 'suma_controlsemana';
    protected $_primary = array("semanaid", "uid", "dni");   

     /* Lista toda las Personas */
 

    public function _getOne($where=array()){
        try {
            //if ($where["dni"]=='') return false;
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."'   ";
                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }



    public function _getOnexMes($where=array()){
        try {
            //if ($where["dni"]=='') return false;
                $wherestr="uid = '".$where['uid']."' and dni = '".$where['dni']."'   ";
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

    public function _getSumahorasemanaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos persona".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("suma_controlsemana");
                else $select->from("suma_controlsemana",$attrib);
                //print_r($where);
                foreach ($where as $atri=>$value){
                    $select->where("$atri = ?", $value);                    
                }
                if ($orders<>null || $orders<>"") {
                    if (is_array($orders))
                        $select->order($orders);
                }
                $results = $select->query();
                $rows = $results->fetchAll();
                //print_r($results);
                if ($rows) return $rows;
                return false;
        }catch (Exception $e){
            print "Error: Read Filter competencia ".$e->getMessage();
        }
    }


    public function _update($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
           print "Error: Actualizando un registro de Propuesta".$ex->getMessage();
        }
    }

    public function _getListarHojasdeTiempoxEquipoArea($uid,$dni)
     {
        try{
            $sql=$this->_db->query("
               select  * from suma_controlsemana
               where uid='$uid' and dni='$dni'


            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

 
}