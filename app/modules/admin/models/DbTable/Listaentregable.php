<?php
class Admin_Model_DbTable_Listaentregable extends Zend_Db_Table_Abstract
{
    protected $_name = 'lista_entregable';
    protected $_primary = array("codigo_prop_proy","proyectoid","revision_entregable");


    public function _save($data){
        try{
            //if ($data['codigo_prop_proy']=="" ) return false;
            return $this->insert($data);
        }catch (Exception $ex){
            print "Error: Insertando un nuevo Entregable".$ex->getMessage();
        }
    }
    
    public function _update_state($data,$pk)
    {
        try{           
            $where = "
                codigo_prop_proy = '".$pk['codigo_prop_proy']."' and                        
                proyectoid = '".$pk['proyectoid']."' ";
            
            return $this->update($data, $where);
            return false;

        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    } 

    public function _update($data,$pk)
    {
        try{            
            $where = "
                codigo_prop_proy = '".$pk['codigo_prop_proy']."' and         
                revision_entregable = '".$pk['revision_entregable']."' and
                proyectoid = '".$pk['proyectoid']."'
            ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }


         /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("lista_entregable");
                else $select->from("lista_entregable",$attrib);
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
            print "Error: Read Filter competencia ".$e->getMessage();
        }
    }

    public function _getentregablexActivo($proyectoid){
        try{
            $sql=$this->_db->query("select *
                from lista_entregable where proyectoid='".$proyectoid."' and state='A'");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

 
}
