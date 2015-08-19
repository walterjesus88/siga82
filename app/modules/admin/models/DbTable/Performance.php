<?php 
class Admin_Model_DbTable_Performance extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_performance';
    protected $_primary = array("codigo_prop_proy", "codigo_actividad", "actividadid", "cronogramaid", "codigo_cronograma", "revision_cronograma", "proyectoid", "codigo_performance");   

     /* Lista toda las Personas */

     /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("proyecto_performance");
                else $select->from("proyecto_performance",$attrib);
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


// codigo_prop_proy, codigo_actividad, actividadid, revision

// codigo_prop_proy, codigo_actividad, actividadid, cronogramaid, codigo_cronograma,
// revision_cronograma, proyectoid, codigo_performance

    public function _getBuscarActividadxPerformance($proyectoid,$revision)
    {
        try{
            $sql=$this->_db->query("
              
                select p.codigo_prop_proy,p.codigo_actividad,p.proyectoid,p.cronogramaid,p.codigo_cronograma,
                p.revision_cronograma,p.actividadid,p.codigo_performance,a.nombre,p.revision_propuesta
                ,p.fecha_ingreso_performance,p.fecha_calculo_performance,p.costo_real,p.horas_real,
                p.fecha_comienzo_real,p.fecha_fin_real  from proyecto_performance as p

                 inner join actividad as a
                on a.codigo_prop_proy=p.codigo_prop_proy 
                and a.codigo_actividad=p.codigo_actividad 
                and a.actividadid=p.actividadid and a.revision=p.revision_propuesta 
                where p.proyectoid='$proyectoid' and p.revision_cronograma='$revision'
             
                order by p.cronogramaid asc
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
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
                codigo_performance = '".$pk['codigo_performance']."' 
          
            ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }

 
}