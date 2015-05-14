<?php 
class Admin_Model_DbTable_Equipo extends Zend_Db_Table_Abstract
{
    protected $_name = 'equipo';
    protected $_primary = array("codigo_prop_proy","proyectoid","categoriaid","uid","dni","areaid","cargo");   

     /* Lista toda las Personas */    
    public function _getEquipoAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getProyectosXuidXEstado($uid,$estado)
     {
        try{
            $sql=$this->_db->query("
               select * from equipo e inner join proyecto p 
               on e.codigo_prop_proy = p.codigo_prop_proy and e.proyectoid = p.proyectoid 
               where e.uid = '$uid' and e.estado = '$estado'");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


public function _getProyectosxUidXEstadoxCliente($uid,$estado,$clienteid,$unidad_mineraid)
     {
        try{
            $sql=$this->_db->query("
                select p.codigo_prop_proy, p.proyectoid, e.estado, e.categoriaid, e.areaid, pro.propuestaid, pro.revision, p.nombre_proyecto, 
                p.tipo_proyecto, pro.moneda, pro.descripcion  
                from equipo e inner join proyecto p
                on e.codigo_prop_proy = p.codigo_prop_proy
                inner join propuesta pro on
                p.codigo_prop_proy=pro.codigo_prop_proy
                where e.uid = '$uid' and e.estado = '$estado' and pro.clienteid='$clienteid' and pro.unidad_mineraid='$unidad_mineraid'


                ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }





    public function _getClienteXuidXEstado($uid,$estado)
     {
        try{
            $sql=$this->_db->query("
select 
               c.clienteid,c.nombre_comercial,c.nombre as nombre_cliente,u.unidad_mineraid,
               u.nombre as nombre_unidad, p.nombre_propuesta, p.tipo_servicio   
               from equipo e inner join propuesta p
               on e.codigo_prop_proy = p.codigo_prop_proy
               inner join unidad_minera u on
               p.clienteid=u.clienteid and p.unidad_mineraid=u.unidad_mineraid
               inner join cliente c on
               u.clienteid=c.clienteid
               where e.uid = '$uid' and e.estado = '$estado'");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['codigo_prop_proy']=='' || $where['proyectoid']=='' || $where['categoriaid']=='') return false;
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

     public function _buscarEquipoxProyectoxArea($codigo,$proyectoid,$areaid)
     {
        try{
            $sql=$this->_db->query("
               select * from equipo
               where codigo_prop_proy='$codigo' and proyectoid='$proyectoid' and areaid='$areaid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

     public function _buscarEquipoxProyectoxAreaxCategoria($codigo,$proyectoid,$areaid,$categoriaid)
     {
        try{
            $sql=$this->_db->query("
               select * from equipo
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

 
        public function _save($data)
    {
        try{
            if ($data['areaid']=='' ||  $data['dni']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }


}