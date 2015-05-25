<?php 
class Admin_Model_DbTable_Usuariovalidacion extends Zend_Db_Table_Abstract
{
    protected $_name = 'usuario_validacion';
    protected $_primary = array("semanaid","uid", "dni", "cargo");


    public function _save($data)
    {
        try{
            if ($data['semanaid']=='' || $data['uid']=='' || $data['dni']=='' || $data['cargo']==''   ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: guardando comentario  ".$e->getMessage();
        }
    }


    public function _getOne($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                //$wherestr= "dni = '".$where['dni']."'  ";
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."' and cargo = '".$where['cargo']."'  ";

                $row = $this->fetchRow($wherestr);

                //print_r($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getOnexUsuario($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                //$wherestr= "dni = '".$where['dni']."'  ";
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."'   ";

                $row = $this->fetchRow($wherestr);

                //print_r($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

        public function _getOnexUsuarioxJefe($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                //$wherestr= "dni = '".$where['dni']."'  ";
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."'  
                and dni_validacion = '".$where['dni_validacion']."' and uid_validacion = '".$where['uid_validacion']."'

                  "; 

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
            if ($pk['semanaid']=='' || $pk['uid']=='' || $pk['dni']=='' || $pk['cargo']=='') return false;
            $where = " semanaid = '".$pk['semanaid']."' and uid = '".$pk['uid']."' and dni = '".$pk['dni']."' and cargo = '".$pk['cargo']."'";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }


    public function _getUsuarioxValidacion($semanaid,$uid,$dni)
     {
        try{
            $sql=$this->_db->query("
              select  * from usuario_validacion 
              where semanaid='$semanaid' and uid='$uid'
              and dni='$dni'

            ");
            // print_r($sql);
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



    public function _getEstadoxValidarJefe($semana,$uid,$dni)
     {
        try{
            $sql=$this->_db->query("
              select  * from usuario_validacion 
              where semanaid='$semana' and uid='$uid'
              and dni='$dni' and estado_usuario='B' and etapa='1'

            ");
            // print_r($sql);
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }
    


 public function _updateXUsuario($data,$pk)
    {
        try{
            if ($pk['semanaid']=='' || $pk['uid']=='' || $pk['dni']=='' || $pk['cargo']=='') return false;
            $where = " semanaid = '".$pk['semanaid']."' and uid = '".$pk['uid']."' and dni = '".$pk['dni']."' 
            and cargo = '".$pk['cargo']."'
                       and orden = '".$pk['orden']."'

            ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }

}