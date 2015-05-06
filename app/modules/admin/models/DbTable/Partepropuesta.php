<?php 
class Admin_Model_DbTable_Partepropuesta extends Zend_Db_Table_Abstract
{
    protected $_name = 'parte_propuesta';
    protected $_primary = array("propuestaid", "revision","clienteid","nro_propuesta");

     /* Lista toda las Personas */    
    public function _getPartePropuestaAll(){
        try{
            $f = $this->fetchAll();

            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }
    
     public function _getPartePropuestaxIndices($propuestaid,$revision,$clienteid,$unidad_minera)
     {
        try{
            $sql=$this->_db->query("
               select * from parte_propuesta 
               where propuestaid='$propuestaid' and revision='$revision' and clienteid='$clienteid'
               and unidad_mineraid='$unidad_minera' 

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getPartePropuestaxEstadoganado($propuestaid,$revision,$clienteid,$nro_propuesta)
     {
        try{
            $sql=$this->_db->query("
               select * from parte_propuesta 
               where propuestaid='$propuestaid' and revision='$revision' and clienteid='$clienteid'
               and nro_propuesta='$nro_propuesta' and estado='G' 

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
            if ($data['propuestaid']=='' ||  $data['revision']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }



}