<?php 
class Admin_Model_DbTable_Transmittal extends Zend_Db_Table_Abstract
{
    protected $_name = 'transmittal';
    protected $_primary = array("codigo_prop_proy", "propuestaid", "revision");

     /* Lista toda las Personas */    
    public function _getTransmittalAll(){
        try{
            $f = $this->fetchAll();

            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los transmittal".$e->getMessage();
        }
    }
    
     public function _getTransmittalxIndices($codigo,$propuestaid,$revision)
     {
        try{
            $sql=$this->_db->query("
               select * from transmittal 
               where propuestaid='$propuestaid' and revision='$revision' and codigo_prop_proy='$codigo' 

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getTransmittalAllOrdenadoxEstadoPropuesta($estado_transmittal)
     {
        try{
            $sql=$this->_db->query("
               select * from transmittal where estado_transmittal='$estado_transmittal'
               order by propuestaid desc

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }
    
     public function _buscarTransmittal($transmittal){
        try{
            $sql=$this->_db->query("
                select * from transmittal as pro inner join cliente as cli on
                pro.clienteid=cli.clienteid where lower(pro.nombre_propuesta) like '%$propuesta%' 
                or lower(cli.nombre_comercial) like '%$transmittal%'
                order by pro.orden_estado asc");
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
            if ($data['codigo_prop_proy']=='' ||  $data['codigo_prop_proy']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }



}