<?php
class Admin_Model_DbTable_Proyectocronograma extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_cronograma';
    protected $_primary = array("codigo_prop_proy", "codigo_cronograma", "revision_cronograma", "proyectoid");

     /* Lista toda las Personas */
    public function _getProyectoxcronogramaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("proyecto_cronograma");
                else $select->from("proyecto_cronograma",$attrib);
                foreach ($where as $atri=>$value){
                    $select->where("$atri = ?", $value);                    
                }
                if ($orders<>null || $orders<>"") {
                    if (is_array($orders))
                        $select->order($orders);
                }
                $results = $select->query();
                $rows = $results->fetchAll();
                //print_r($results);
                if ($rows) return $rows;
                return false;
        }catch (Exception $e){
            print "Error: Read Filter Course ".$e->getMessage();
        }
    }

    public function _getCronogramaxActivo($proyectoid){
        try{
            $sql=$this->_db->query("select *
                from proyecto_cronograma where proyectoid='".$proyectoid."' and state='A'");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _save($data)
    {
        try{
  
            if ($data['revision_cronograma']=='' ||  $data['codigo_prop_proy']=='' ||  $data['proyectoid']==''  ||  $data['codigo_cronograma']==''  ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: EDT ".$e->getMessage();
        }
    }


    public function _update($data,$pk)
    {
        try{            
            $where = "
                codigo_prop_proy = '".$pk['codigo_prop_proy']."' and         
                 
                codigo_cronograma = '".$pk['codigo_cronograma']."' and 
                revision_cronograma = '".$pk['revision_cronograma']."' and 
                proyectoid = '".$pk['proyectoid']."'
            ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }

    public function _update_state($data,$pk)
    {
        try{            
            
            return $this->update($data, $pk);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }
 
    public function _delete($pk=null)
    {
        try{
            if ($pk['cronogramaid']=='' ||  $pk['codigo_prop_proy']=='' ||  $pk['proyectoid']=='' ) return false;
           
            $where = "cronogramaid = '".$pk['cronogramaid']."'
                    and codigo_prop_proy = '".$pk['codigo_prop_proy']."'
                    and proyectoid = '".$pk['proyectoid']."' ";

            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Eliminar EDT".$e->getMessage();
        }
    }


}
