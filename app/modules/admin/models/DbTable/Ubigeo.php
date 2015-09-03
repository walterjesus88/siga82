<?php
class Admin_Model_DbTable_Ubigeo extends Zend_Db_Table_Abstract
{
    protected $_name = 'ubigeo';
    protected $_primary = array("item");

    /* Lista tabla Ubigeo */    
    public function _getUbigeoAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de listar".$e->getMessage();
        }
    }

    /*Listar Continentes*/
    public function _getUbigeoContinente()
     {
        try{
            $sql=$this->_db->query("
               SELECT DISTINCT continente FROM ubigeo 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

        /*Listar Pais*/
    public function _getUbigeoPais()
     {
        try{
            $sql=$this->_db->query("
               SELECT DISTINCT pais FROM ubigeo 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

        /*Listar Departamento*/
    public function _getUbigeoDepartamento()
     {
        try{
            $sql=$this->_db->query("
               SELECT DISTINCT departamento FROM ubigeo 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

        /*Listar Provincia*/
    public function _getUbigeoProvincia()
     {
        try{
            $sql=$this->_db->query("
               SELECT DISTINCT provincia FROM ubigeo 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

        /*Listar Distrito*/
    public function _getUbigeoDistrito()
     {
        try{
            $sql=$this->_db->query("
               SELECT DISTINCT distrito FROM ubigeo 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    /* GUardar Ubigeo */    
    public function _saveUbigeo($data)
    {
        try{
            if ($data['item']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
            print "Error: Registration ".$e->getMessage();
        }
    }

    /*Actualizar Ubigeo*/
    public function _updateUbigeo($data,$pk)
    {
        try{
            if ($pk=='' ) return false;
            $where = "item = '".$pk."' ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Ubigeo".$e->getMessage();
        }
    }

    /*Eliminar Ubigeo*/
    public function _deleteUbigeo($pk=null)
    {
        try{
            if ($pk['item']=='') return false;

            $where = "item = '".$pk['item']."'";
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Delete Ubigeo".$e->getMessage();
        }
    }

    
}


