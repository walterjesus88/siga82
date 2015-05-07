<?php 
class Admin_Model_DbTable_Areacategoria extends Zend_Db_Table_Abstract
{
    protected $_name = 'area_categoria';
    protected $_primary = array("categoriaid","areaid");


    public function _getAreacategoriaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getAreacategoriaxIndice($categoriaid,$areaid)
     {
        try{
            $sql=$this->_db->query("
               select * from area_categoria
               where areaid='$areaid' and categoriaid='$categoriaid'

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _buscarCategoriaxAreaxProyecto($areaid)
     {
        try{
            $sql=$this->_db->query("
               select * from area_categoria where areaid = '$areaid' and estado='P' 
               

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
            if ($data['areaid']==''  ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }




}