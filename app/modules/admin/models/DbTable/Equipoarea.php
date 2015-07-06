<?php 
class Admin_Model_DbTable_Equipoarea extends Zend_Db_Table_Abstract
{
    protected $_name = 'equipo_area';
    protected $_primary = array("codigo_prop_proy","proyectoid","areaid");   

     /* Lista toda las Personas */    
    public function _getEquipoareaAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getOne($where=array()){
        try{
            if ($where['codigo_prop_proy']=='' || $where['proyectoid']=='' || $where['areaid']=='' ) return false;
            $wherestr=" codigo_prop_proy = '".$where['codigo_prop_proy']."' and  proyectoid='".$where['proyectoid']."' and areaid='".$where['areaid']."' ";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Add_reportacad_adm ".$e->getMessage();
        }
    }



    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            if($where['codigo_prop_proy']=='' || $where['proyectoid']=='' || $where['categoriaid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("equipo");
                else $select->from("equipo",$attrib);
                foreach ($where as $atri=>$value){
                    $select->where("$atri = ?", $value);
                }
                if ($orders<>null || $orders<>"") {
                    if (is_array($orders))
                        $select->order($orders);
                }
                $results = $select->query();
                $rows = $results->fetchAll();
                if ($rows) return $rows;
                return false;
        }catch (Exception $ex){
            print $ex->getMessage();

        }
    }

    public function _buscarEquipoxProyecto($codigo,$proyectoid,$areaid,$categoriaid)
     {
        try{
            $sql=$this->_db->query("
               select * from equipo_area
               where codigo_prop_proy='$codigo' and proyectoid='$proyectoid' and areaid='$areaid'
               and categoriaid='$categoriaid' 
               

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _buscarAreasxProyecto($codigo,$proyectoid)
     {
        try{
            $sql=$this->_db->query("        
            select distinct eq.areaid,a.orden,a.nombre from equipo_area as eq inner join area as a on eq.areaid=a.areaid
            where eq.codigo_prop_proy='$codigo' and eq.proyectoid='$proyectoid'
            order by a.orden 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }
     


    // public function _getOne($where=array()){
    //     try{
    //         if ($where['clienteid']=='' ) return false;
    //         $wherestr="clienteid = '".$where['clienteid']."' ";
    //         $row = $this->fetchRow($wherestr);
    //         if($row) return $row->toArray();
    //         return false;
    //     }catch (Exception $e){
    //         print "Error: Read One Add_reportacad_adm ".$e->getMessage();
    //     }
    // }

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
            if ($data['areaid']=='' ||  $data['proyectoid']==''  ||  $data['codigo_prop_proy']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }




}