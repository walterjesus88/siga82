<?php 
class Admin_Model_DbTable_Planificacion extends Zend_Db_Table_Abstract
{
    protected $_name = 'planificacion';
    protected $_primary = array("codigo_prop_proy","proyectoid","semanaid","uid","dni","categoriaid","areaid","cargo");   
     /* Lista toda las Personas */
    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("planificacion");
                else $select->from("planificacion",$attrib);
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
                print "Error: Planificacion ".$e->getMessage();
        }
    }

    public function _getOne($where=array()){
        try {
            //if ($where["dni"]=='') return false;
            $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."' and cargo = '".$where['cargo']."' 
                 and categoriaid = '".$where['categoriaid']."' and areaid = '".$where['areaid']."' and codigo_prop_proy = '".$where['codigo_prop_proy']."'
                 and proyectoid = '".$where['proyectoid']."' ";


                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getOnexSemana($semanaid,$uid,$dni,$areaid){
        try {
            $sql=$this->_db->query("
               select * from planificacion
               where semanaid='$semanaid'  and uid='$uid' and dni='$dni' and areaid='$areaid'
               
            ");
            $row=$sql->fetchAll();
            return $row;     

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getOnexSemanaxGerenteProyecto($semanaid,$uid,$dni,$areaid){
        try {
            $sql=$this->_db->query("
                select distinct e.uid,e.dni from planificacion as p inner join equipo as e  
                on e.codigo_prop_proy=p.codigo_prop_proy and e.proyectoid=p.proyectoid and e.nivel='0' and e.cargo='GER-PROY'
                where p.semanaid='$semanaid'  and p.uid='$uid' and p.dni='$dni' and p.proyectoid!='1'and p.areaid='$areaid' 
                and p.h_totaldia is not null

             
            ");
            $row=$sql->fetchAll();
            return $row;     

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getPlanificacionAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todos los gastos persona".$e->getMessage();
        }
    }

        /*
                select distinct e.uid,e.dni from planificacion p inner join equipo e
                on p.codigo_prop_proy=e.codigo_prop_proy and p.proyectoid=e.proyectoid
                where p.semanaid='$semanaid'  and p.uid='$uid'  and p.dni='$dni'  and e.nivel='0' and e.categoriaid='GER-PROY'*/

    public function _getSemanaxGerenteProyecto($semanaid,$uid,$dni){
        try {
            $sql=$this->_db->query("

                   select distinct e.uid,e.dni,p.proyectoid from planificacion as p inner join equipo as e  
                on e.codigo_prop_proy=p.codigo_prop_proy and e.proyectoid=p.proyectoid and e.nivel='0' and e.cargo='GER-PROY'
                where p.semanaid='$semanaid'  and p.uid='$uid' and p.dni='$dni' and p.proyectoid!='1'


             
            ");
            $row=$sql->fetchAll();
            return $row;     

        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }
}