<?php 
class Admin_Model_DbTable_Contacto extends Zend_Db_Table_Abstract
{
    protected $_name = 'contacto';
    protected $_primary = array("contactoid", "areaid", "clienteid");


    public function _getContactoAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getConstactoxIndice($contactoid,$areaid,$clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from contacto
               where constactoid='$contactoid' and areaid='$areaid' and clienteid='$clienteid'  

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getConstactoxAreaxCliente($areaid,$clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from contacto
               where areaid='$areaid' and clienteid='$clienteid'  

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


     public function _getConstactoxPropuesta($clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from contacto
               where clienteid='$clienteid'  

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _buscarContacto($nombre){
        try{
            $sql=$this->_db->query("
                select * from contacto where lower(nombre) like '%$nombre%'
                ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();

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

   

        public function _save($data)
    {
        try{
            if ($data['areaid']=='' ||  $data['clienteid']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }


    public function _delete($pk=null)
    {
        try{
            if ($pk['contactoid']=='' ||  $pk['clienteid']=='' ) return false;

            $where = "contactoid = '".$pk['contactoid']."' and clienteid='".$pk['clienteid']."' and areaid='".$pk['areaid']."' ";
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }
    


}