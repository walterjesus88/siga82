<?php 
class Admin_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{
    protected $_name = 'usuario';
    protected $_primary = array("uid","dni");



    public function _getOne($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                $wherestr= "dni = '".$where['dni']."'  ";

                $row = $this->fetchRow($wherestr);

                //print_r($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }



    public function _updateX($data,$pk)
    {
        try{
            if ($pk['uid']=='' || $pk['dni']=='') return false;
            $where = "dni = '".$pk['dni']."'  and uid = '".$pk['uid']."' ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }

    public function _getUsuarioAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas los usuarios".$e->getMessage();
        }
    }

    public function _save($data)
    {
        try{
            if ($data['dni']=='' ||  $data['uid']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }


}

