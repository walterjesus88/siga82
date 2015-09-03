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

    /*listar todas las areas con su gerencia central*/
    public function _getAreaGerenteCentral()
     {
        try{
            $sql=$this->_db->query("
                SELECT area.areaid, area.nombre, area.area_padre, area.isproyecto, area.ispropuesta, area.iscontacto, area.iscomercial, area.orden
                FROM area  
                LEFT JOIN persona  
                ON (area.gerente_central = persona.dni);

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
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

    public function _updatearea($data,$pk)
    {
        try{
            if ($pk=='' ) return false;
            $where = "areaid = '".$pk."' ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update area".$e->getMessage();
        }
    }


        public function _save($data)
    {
        try{
            if ($data['areaid']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
              //  print "Error: Registration ".$e->getMessage();
        }
    }

    public function _deletearea($pk=null)
    {
        try{
            if ($pk['areaid']=='') return false;

            $where = "areaid = '".$pk['areaid']."'";
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }


}