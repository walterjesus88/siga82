<?php
class Admin_Model_DbTable_Aprobacion extends Zend_Db_Table_Abstract
{
    protected $_name = 'aprobacion';
    protected $_primary = array("idaprobacion");

    public function _getOne($where=array()){
        try {
                $wherestr= "idaprobacion = '".$where['idaprobacion']."' and estado='".$where['estado']."'
                ";
                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _updateX($data,$pk)
    {
        try{
            if ($pk['codigo_prop_proy']=='' || $pk['codigo_actividad']=='' || $pk['proyectoid']=='' || $pk['actividadid']=='' || $pk['uid']=='' || $pk['dni']=='' || $pk['cargo']=='' || $pk['areaid']=='' || $pk['categoriaid']=='' ) return false;

            $where = "codigo_prop_proy = '".$pk['codigo_prop_proy']."'  and codigo_actividad = '".$pk['codigo_actividad']."'
            		 and proyectoid = '".$pk['proyectoid']."' and actividadid = '".$pk['actividadid']."'
            		 and uid = '".$pk['uid']."' and dni = '".$pk['dni']."' and cargo = '".$pk['cargo']."' 
            		 and areaid = '".$pk['areaid']."' and categoriaid = '".$pk['categoriaid']."'   
             ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }

    
    public function _save($data)
    {
        try{
            //if ($data['areaid']=='' ||  $data['codigo_prop_proy']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }


    public function _getExistePersonaActividad($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                $wherestr= "codigo_prop_proy = '".$where['codigo_prop_proy']."' and codigo_actividad='".$where['codigo_actividad']."'
                            and proyectoid='".$where['proyectoid']."'  and actividadid='".$where['actividadid']."'
                            and uid='".$where['uid']."' and dni='".$where['dni']."'  and areaid='".$where['areaid']."'
                             ";

                //print_r($wherestr);

                $row = $this->fetchRow($wherestr);

                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{            
            //if($where['codigo_prop_proy']=='' || $where['proyectoid']=='' ) return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("activar_actividad");
                else $select->from("activar_actividad",$attrib);
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
        }catch (Exception $e){
            print "Error: Read Filter Actividad ".$e->getMessage();
        }
    }  


    public function _getOnefiltro1($where=array()){
        try {
                $wherestr= "idaprobacion = '".$where['idaprobacion']."' and estado_filtro1='".$where['estado_filtro1']."'
                ";
                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getOnefiltro2($where=array()){
        try {
                $wherestr= "idaprobacion = '".$where['idaprobacion']."' and estado_filtro2='".$where['estado_filtro2']."'
                ";
                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getCodigoAprobacionxAprobadorfiltro2($idaprobador_filtro2,$estado_filtro2)
    {
        try{
            $sql=$this->_db->query("
              select  idaprobacion from aprobacion
              where idaprobador_filtro2='$idaprobador_filtro2' and estado_filtro2='$estado_filtro2' 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


}