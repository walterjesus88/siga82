<?php 
class Admin_Model_DbTable_Performancedetalle extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_performance_detalle';
    protected $_primary = array("codigo_prop_proy", "codigo_actividad", "actividadid", "cronogramaid", "codigo_cronograma", "revision_cronograma", "proyectoid", "codigo_performance", "fecha_performance");   

     /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("proyecto_performance_detalle");
                else $select->from("proyecto_performance_detalle",$attrib);
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
 
    public function _save($data)
    {
        try{
            if ($data['codigo_prop_proy']=='' ||  $data['codigo_actividad']=='' ||  $data['actividadid']==''
            ||  $data['cronogramaid']=='' ||  $data['codigo_cronograma']=='' ||  $data['revision_cronograma']==''
            ||  $data['proyectoid']=='' ||  $data['codigo_performance']=='' ||  $data['fecha_performance']==''

            ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                //print "Error: Registration ".$e->getMessage();
        }
    }

    public function _update($data,$pk)
    {
        try{
            //if ($pk['id_tproyecto']=='' ||  $pk['proyectoid']=='' ) return false;
            $where = "
                codigo_prop_proy = '".$pk['codigo_prop_proy']."' and 
                codigo_actividad = '".$pk['codigo_actividad']."' and 
                actividadid = '".$pk['actividadid']."' and 
                cronogramaid = '".$pk['cronogramaid']."' and 
                codigo_cronograma = '".$pk['codigo_cronograma']."' and 
                revision_cronograma = '".$pk['revision_cronograma']."' and 
                proyectoid = '".$pk['proyectoid']."' and 
                codigo_performance = '".$pk['codigo_performance']."' and 
                fecha_performance = '".$pk['fecha_performance']."'
            ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }

}