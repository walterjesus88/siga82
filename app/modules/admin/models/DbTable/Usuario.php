<?php 
class Admin_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{
    protected $_name = 'usuario';
    protected $_primary = array("uid","dni");



    public function _getOne($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                $wherestr= "dni = '".$where['dni']."'  ";

                $row = $this->fetchRow($wherestr);

                //print_r($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }



    public function _updateX($data,$pk)
    {
        try{
            if ($pk['uid']=='' || $pk['dni']=='') return false;
            $where = "dni = '".$pk['dni']."'  and uid = '".$pk['uid']."' ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }

    public function _getUsuarioAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas los usuarios".$e->getMessage();
        }
    }

    public function _save($data)
    {
        try{
            if ($data['dni']=='' ||  $data['uid']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("usuario");
                else $select->from("usuario",$attrib);
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
            print "Error: Read Filter Usuario ".$e->getMessage();
        }
    }

    public function _update($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
            print "Error: Actualizando un registro de usuario".$ex->getMessage();
        }
    }

    public function UsuarioxEstado($estado)
    {
        try{
            $sql=$this->_db->query("
                select uid,split_part(u.uid, '.', 1) as nombre, split_part(u.uid, '.', 2) as apellido ,  initcap ( split_part(u.uid, '.', 1)  || ' '|| split_part(u.uid, '.', 2) ) as nombre_completo   
                , a.nombre as nombre_area, u.estado,u.areaid
                from usuario as u inner join area as a
                on u.areaid = a.areaid where u.estado='$estado'

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            catch (Exception $ex){
            print $ex->getMessage();
        }
    }


}

