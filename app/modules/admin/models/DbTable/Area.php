<?php
class Admin_Model_DbTable_Area extends Zend_Db_Table_Abstract
{
    protected $_name = 'area';
    protected $_primary = 'areaid';

    public function _getName($id){
        return $this->fetchRow($this->select()
                    ->where('areaid = ?', $id))
                    ->toArray();
    }
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("area");
                else $select->from("area",$attrib);
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

    public function _getAreaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getAreaxIndice($areaid)
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

    public function _buscarCategoriaxTag($tagarea)
     {
        try{
            $sql=$this->_db->query("
               select * from area where tag like '%$tagarea%'


            ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



    public function _getAreaxPropuesta()
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where ispropuesta='S'

            ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getAreaxProyecto()
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where isproyecto='S'
               order by  nombre

            ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getAreaxContacto()
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where iscontacto='S'

            ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getAreaxContactoComercial()
     {
        try{
            $sql=$this->_db->query("
               select * from area
               where iscomercial='S'

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



    public function _update_pk($data,$pk)
    {
        try{
            //if ($pk['id_tproyecto']=='' ||  $pk['proyectoid']=='' ) return false;
            $where = "areaid = '".$pk['areaid']."'";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }

    public function _updatearea($data,$pk)
    {
        try{
            if ($pk=='' ) return false;
            $where = "areaid = '".$pk."' ";
            return $this->update($data, $where);
            // print_r($this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update area".$e->getMessage();
        }
    }

        public function _save($data)
    {
        try{
            if ($data['areaid']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

       public function _delete($pk=null)
        {
            try{


                $where = "areaid = '".$pk['areaid']."'

                         ";

                return $this->delete( $where);
                return false;
            }catch (Exception $e){
                print "Error: Eliminar EDT".$e->getMessage();
            }
        }


}