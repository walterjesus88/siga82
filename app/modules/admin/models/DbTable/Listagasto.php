<?php 
class Admin_Model_DbTable_Listagasto extends Zend_Db_Table_Abstract
{
    protected $_name = 'lista_gasto';
    protected $_primary = array("gastoid","tipo_gasto","fecha_vigencia");

     /* Lista toda los gastos */    
    public function _getGastosAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos".$e->getMessage();
        }
    }

    public function _getGastosPadres(){
        try{
            $sql=$this->_db->query("
               select * from lista_gasto where gasto_padre='' and estado='A' 
               order by tipo_gasto, cast(gastoid as integer)
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getGastosHijos(){
        try{
            $sql=$this->_db->query("
                select * from lista_gasto where length(gasto_padre) = 1 and estado = 'A' 
                order by tipo_gasto desc, cast(gastoid as float)
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getGastosXgastopadre($gastoid, $tipo_gasto){
        try{
            $sql=$this->_db->query("
               select * from lista_gasto where gasto_padre = '$gastoid' and tipo_gasto = '$tipo_gasto' and estado='A' 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }
}