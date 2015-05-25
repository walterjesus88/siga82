<?php 
class Admin_Model_DbTable_Sumahora extends Zend_Db_Table_Abstract
{
    protected $_name = 'suma_hora';
    protected $_primary = array("semanaid", "uid", "dni",  "fecha_tarea");   

     /* Lista toda las Personas */

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("suma_hora");
                else $select->from("suma_hora",$attrib);
                //print_r($where);
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
            //if ($data['codigo_prop_proy']=='' ||  $data['codigo_prop_proy']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }
 
public function _getSumaHorasxActividad($uid,$dni,$semanaid,$tipo_actividad)
     {
        try{
            $sql=$this->_db->query("
               select sum($tipo_actividad) from suma_hora
               where uid='$uid'  and dni='$dni' and semanaid='$semanaid'
               
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getOne($where=array()){
        try {
            //if ($where["dni"]=='') return false;
                
                
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."'  and fecha_tarea = '".$where['fecha_tarea']."'  ";


                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getSumahoraAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos persona".$e->getMessage();
        }
    }
}