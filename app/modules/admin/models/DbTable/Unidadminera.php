<?php 
class Admin_Model_DbTable_Unidadminera extends Zend_Db_Table_Abstract
{
    protected $_name = 'unidad_minera';
    protected $_primary = array("unidad_mineraid", "clienteid");


    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("unidad_minera");
                else $select->from("unidad_minera",$attrib);
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

     /* Lista toda las Personas */    
    public function _getUnidadmineraAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las -".$e->getMessage();
        }
    }
 
  public function _getUnidadmineraxIndice($clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from unidad_minera 
               where clienteid='$clienteid'
               order by nombre asc 

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getUnidadmineraxid($clienteid,$unidad)
     {
        try{
            $sql=$this->_db->query("
               select * from unidad_minera 
               where clienteid='$clienteid' and unidad_mineraid='$unidad'
               order by nombre asc 

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getUnidadmineraxcliente($clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from unidad_minera 
               where clienteid='$clienteid' order by nombre asc;");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



    public function _getOne($where=array()){
        try{
            if ($where['clienteid']=='' || $where['unidad_mineraid']=='') return false;
            $wherestr="clienteid = '".$where['clienteid']."' and unidad_mineraid = '".$where['unidad_mineraid']."' ";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Add_reportacad_adm ".$e->getMessage();
        }
    }


    public function _updateunidadminera($data,$pk)
    {
        try{
            if ($pk=='' ) return false;
            // $where = "unidad_mineraid = '".$pk['unidad_mineraid']."'  and clienteid = '".$pk['clienteid']."' ";
            $where = "unidad_mineraid = '".$pk['unidad_mineraid']."'";
            return $this->update($data, $where);
            print_r($this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update unidada minera".$e->getMessage();
        }
    }

        public function _save($data)
    {
        try{
            if ($data['unidad_mineraid']=='' || $data['clienteid']=='') return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }


    public function _delete($pk=null)
        {
            try{


                $where = "unidad_mineraid = '".$pk['unidad_mineraid']."'

                         ";

                return $this->delete( $where);
                return false;
            }catch (Exception $e){
                print "Error: Eliminar unidad minera".$e->getMessage();
            }
        }
 


}