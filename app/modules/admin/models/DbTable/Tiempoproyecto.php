<?php
class Admin_Model_DbTable_Tiempoproyecto extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_curvas';
    protected $_primary = array("codigo_prop_proy","proyectoid","revision_cronograma");

    public function _save($data){
        try{
            //if ($data['codigo_prop_proy']=="" ) return false;
            return $this->insert($data);
        }catch (Exception $ex){
            print "Error: Insertando un nuevo Proyecto".$ex->getMessage();
        }
    }


         /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("proyecto_curvas");
                else $select->from("proyecto_curvas",$attrib);
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
            print "Error: Read Filter proyecto_curvas ".$e->getMessage();
        }
    }


    public function _update($data,$pk)
    {
        try{
            //if ($pk['id_tproyecto']=='' ||  $pk['proyectoid']=='' ) return false;
            $where = "codigo_curvas = '".$pk['codigo_curvas']."' ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }


    public function _delete($pk=null)
    {
        try{
            if ($pk['codigo_curvas']==''  ) return false;

            $where = "codigo_curvas = '".$pk['codigo_curvas']."' ";
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            //print "Error: Update Distribution".$e->getMessage();
        }
    }


}