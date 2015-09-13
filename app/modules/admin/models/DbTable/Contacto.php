<?php
class Admin_Model_DbTable_Contacto extends Zend_Db_Table_Abstract
{
    protected $_name = 'contacto';
    protected $_primary = array("contactoid");
    protected $_sequence ="s_contacto";


    public function _getOne($where){
        try {
                //if ($where["dni"]=='') return false;
                $wherestr= "contactoid = '".$where['contactoid']."' ";
                print_r($wherestr);

                $row = $this->fetchRow($wherestr);

                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getContactoAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getConstactoxIndice($contactoid,$areaid,$clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from contacto
               where constactoid='$contactoid' and areaid='$areaid' and clienteid='$clienteid'
            ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getConstactoxAreaxCliente($areaid,$clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from contacto
               where areaid='$areaid' and clienteid='$clienteid'

            ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _getConstactoxTipo($tipo_cliente)
     {
        try{
            $sql=$this->_db->query("
               select * from contacto
               where tipo_cliente='$tipo_cliente' and estado='A'

            ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



     public function _getConstactoxPropuesta($clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from contacto
               where clienteid='$clienteid'

            ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


    public function _buscarContacto($nombre){
        try{
            $sql=$this->_db->query("
                select * from contacto where lower(nombre) like '%$nombre%'
                ");
            $row=$sql->fetchAll();
            return $row;
            }

           catch (Exception $ex){
            print $ex->getMessage();

        }
    }

     public function _update($data,$str){
        try{
            if ($str=="") return false;
            //print_r($str);

            $where = "contactoid = '".$str['contactoid']."' ";

            return $this->update($data,$where);
        }catch (Exception $ex){
            print "Error: Actualizando un contacto".$ex->getMessage();
        }
    }





    public function _save($data)
    {
        try{
            //if ($data['areaid']=='' ||  $data['clienteid']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }


    public function _delete($pk=null)
    {
        try{
            if ($pk['contactoid']=='' ||  $pk['clienteid']=='' ) return false;

            $where = "contactoid = '".$pk['contactoid']."' and clienteid='".$pk['clienteid']."' and areaid='".$pk['areaid']."' ";
            return $this->delete( $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Distribution".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            //if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("contacto");
                else $select->from("contacto",$attrib);
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
            print "Error: Read Filter Contactosss ".$e->getMessage();
        }
    }

    public function _getContactoxCliente($clienteid)
    {
      try {
        $sql = $this->_db->query("select contactoid, clienteid, puesto_trabajo,
        correo, concat(nombre1, ' ', ape_paterno) as nombre from contacto
        where clienteid='".$clienteid."' order by nombre");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getContactosxProyecto($proyectoid)
    {
      try {
        $sql = $this->_db->query("select con.contactoid, con.clienteid,
        con.puesto_trabajo, con.correo, concat(con.nombre1, ' ', con.ape_paterno) as nombre
        from contacto as con inner join proyecto as pro on
        (con.clienteid = pro.clienteid)
        where pro.proyectoid='".$proyectoid."' order by nombre");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _addContacto($data)
    {
      try {
        $sql = $this->_db->query("select cast(contactoid as int) from contacto
        where clienteid = '".$data['clienteid']."' order by contactoid desc
        limit 1");
        $row = $sql->fetchAll();

        if (count($row) != 0) {
          $numero = (int) $row[0]['contactoid'];
          $numero++;
        } else {
          $numero = 1;
        }
        $sql = $this->_db->query("insert into contacto (contactoid, clienteid,
        puesto_trabajo, correo, nombre1) values('".$numero."', '".$data['clienteid'].
        "', '".$data['area']."', '".$data['correo']."', '".$data['nombre']."')");
        $row = $sql->fetchAll();
        $lista = $this->_getContactoxCliente($data['clienteid']);
        return $lista;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _updateContacto($data)
    {
      try {
        $sql = $this->_db->query("update contacto set puesto_trabajo = '".
        $data['area']."', correo = '".$data['correo']."', nombre1 ='".
        $data['nombre']."' where clienteid = '".$data['clienteid'].
        "' and contactoid = '".$data['contactoid']."'");
        $row = $sql->fetchAll();
        $lista = $this->_getContactoxCliente($data['clienteid']);
        return $lista;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

    public function _deleteContacto($clienteid, $contactoid)
    {
      $sql = $this->_db->query("delete from contacto where clienteid = '".
      $clienteid."' and contactoid ='".$contactoid."'");
      $row = $sql->fetchAll();
      $lista = $this->_getContactoxCliente($clienteid);
      return $lista;
    }

    public function _getDatosContacto($clienteid, $contactoid)
    {
      $row = $this->fetchRow("clienteid = '".$clienteid."' and contactoid ='".
      $contactoid."'");
      $respuesta['codigo'] = $row['contactoid'];
      $respuesta['nombre'] = $row['nombre1'].$row['ape_paterno'];
      $respuesta['area'] = $row['puesto_trabajo'];
      $respuesta['correo'] = $row['correo'];
      return $respuesta;
    }


}
