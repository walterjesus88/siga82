<?php 
class Admin_Model_DbTable_Area extends Zend_Db_Table_Abstract
{
    protected $_name = 'area';
    protected $_primary = array("areaid");


    public function _getAreaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getAreaxIndice($areaid)
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where areaid='$areaid' 

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _buscarCategoriaxTag($tagarea)
     {
        try{
            $sql=$this->_db->query("
               select * from area where tag like '%$tagarea%' 
               

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



    public function _getAreaxPropuesta()
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where ispropuesta='S'  

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getAreaxProyecto()
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where isproyecto='S' 
               order by  nombre

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getAreaxContacto()
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where iscontacto='S'  

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getAreaxContactoComercial()
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where iscomercial='S'  

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




}