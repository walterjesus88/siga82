<?php 
class Admin_Model_DbTable_Cliente extends Zend_Db_Table_Abstract
{
    protected $_name = 'cliente';
    protected $_primary = array("clienteid");

     /* Lista toda las Personas */    
    public function _getClienteAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getClientexIndice($clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from cliente 
               where clienteid='$clienteid'  

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getClienteAllOrdenado()     
    {
        try{
            $sql=$this->_db->query("
               select * from cliente 
               order by nombre_comercial asc

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


     public function _buscarCliente($cliente){
        try{
            $sql=$this->_db->query("
                select * from cliente where lower(nombre_comercial) like '%$cliente%'
                order by nombre_comercial asc");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


 


}