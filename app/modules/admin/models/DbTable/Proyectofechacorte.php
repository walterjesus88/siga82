<?php
class Admin_Model_DbTable_Proyectofechacorte extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_fechacorte';
    protected $_primary = array("codigo_prop_proy", "proyectoid", "fecha","revision_cronograma");
    protected $_sequence ="s_fecha_corte";


    public function _getProyectoxFechaxCortexAllxOrden(){
        try{
            $sql=$this->_db->query("select *
                from proyecto_fechacorte order by fecha;");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getProyectoxFechaxCortexActivaxProyecto($proyectoid){
        try{
            $sql=$this->_db->query("select *
                from proyecto_fechacorte where state_performance='A'
                and proyectoid='$proyectoid';");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getProyectoxFechaxCortexAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    
    public function _save($data)
    {
        try{
  
            if ($data['codigo_prop_proy']=='' || $data['proyectoid']=='' || $data['fecha']=='' || $data['revision_cronograma']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: EDT ".$e->getMessage();
        }
    }

   

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("proyecto_fechacorte");
                else $select->from("proyecto_fechacorte",$attrib);
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

 
    public function _delete($pk=null)
    {
        try{
            if ($pk['fechacorteid']=='' ) return false;
           
            $where = "fechacorteid = '".$pk['fechacorteid']."'
                     ";

            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Eliminar EDT".$e->getMessage();
        }
    }



    public function _update($data,$pk)
    {
        try{          
            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."' 
            and proyectoid = '".$pk['proyectoid']."' and 
            fechacorteid = '".$pk['fechacorteid']."' 
            ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }

       /*Devuelve el record segun la funcion Record de Notas */
    public function _getRevisionxGenerar($data){
         try{
            
            $sql = $this->_db->query("

                select * from generar_revision('".$data['codigo_prop_proy']."','".$data['proyectoid']."') 
                ");
           
                $row=$sql->fetchAll();
                return $row;
            
        }  catch (Exception $ex){
            print "Error: Obteniendo datos de tabla 'Matricula Curso'".$ex->getMessage();
        }
    }

}
