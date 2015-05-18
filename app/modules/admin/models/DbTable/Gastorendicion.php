<?php 
class Admin_Model_DbTable_Gastorendicion extends Zend_Db_Table_Abstract
{
    protected $_name = 'gasto_rendicion';
    protected $_primary = array("numero", "uid", "dni");
    protected $_sequence ="s_rendicion";

     /* Lista toda los GAstos */    
    public function _getGastorendicionAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos rendicion".$e->getMessage();
        }
    }

    public function _save($data)
    {
        try{
            //if ($data['fecha']=='' ||  $data['uid']=='' ||  $data['dni']=='') return false;
            print_r($data);
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

    public function _update($data,$str){
        try{
            if ($str['numero']=='' ||  $str['uid']=='' || $str['dni']=='') return false;
            $where = "numero = '".$str['numero']."' and uid='".$str['uid']."' and dni='".$str['dni']."'";
            return $this->update($data,$where);
        }catch (Exception $ex){
            print "Error: Actualizando un registro ".$ex->getMessage();
        }
    }

    public function _getOneXfecha($where=array()){
        try{
            if ($where['fecha']=='' || $where['uid']=='' || $where['dni']=='') return false;
            $wherestr="fecha = '".$where['fecha']."' and uid='".$where['uid']."' and dni='".$where['dni']."'";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One ".$e->getMessage();
        }
    }
}