<?php 
class Admin_Model_DbTable_Categoria extends Zend_Db_Table_Abstract
{
    protected $_name = 'categoria';
    protected $_primary = array("categoriaid");


    public function _getCategoriaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getCategoriaxIndice($categoriaid)
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

    public function _getCategoriaxCategoriaid($categoriaid)
     {
        try{
            $sql=$this->_db->query("
               select * from categoria
               where categoriaid='$categoriaid' 

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



    public function _buscarCategoriaxTag($categoria)
     {
        try{
            $sql=$this->_db->query("
               select * from categoria where tag_excel like '%$categoria%' 
               

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
            if ($data['categoriaid']==''  ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

    public function _getCategoriaOrdenado()
     {
        try{
            $sql=$this->_db->query("
               select * from categoria
               order by nombre_categoria

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


}