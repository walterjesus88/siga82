<?php 
class Admin_Model_DbTable_Persona extends Zend_Db_Table_Abstract
{
    protected $_name = 'persona';
    protected $_primary = array("dni");


     /* Lista toda las Personas */    
    public function _getTodasPersonas($eid="",$oid=""){
        try{
            if ($eid=='' || $oid=='') return false;
            $f = $this->fetchAll("eid='$eid' and oid=$oid");
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }


     /* Insertando un nuevo registro de Persona */
    public function _guardar($data){
	   	try{
            if ($data['pid']=="" || $data['ape_pat']=="" || $data['ape_mat']=="") return false;
            return $this->insert($data);
        }catch (Exception $ex){
            print "Error: Insertando un nuevo registro de Persona".$ex->getMessage();
        }
	}


	   /* Obteniendo todos los datos de la Persona por el Nro de DNI */
    public function _getPersona($dni=""){
        try{
            if ($dni=="" )return false;
            $r = $this->fetchRow("dni='$dni'  ");
            if ($r) return $r->toArray();
            return false;
        }catch (Exception $ex){
            print "Error: Lecturando datos de la Persona por Nro DNI".$ex->getMessage();
        }

    }


    /* Actualizando un registro de Persona deacuerdo a una condicion $str*/
	public function _update($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
            print "Error: Actualizando un registro de Persona".$ex->getMessage();
        }
    }



    /* Eliminando registros de la Persona por su primary key (eid,oid,pid) */
    public function eliminar($eid='',$oid='',$pid=''){
        try{
            if ($eid=='' ||  $oid=='' || $pid=='') return false;
            return $this->delete("eid='$eid' and oid='$oid' and pid='$pid'");
        }catch (Exception $ex){
            print "Error: Eliminando un registro de la Persona".$ex->getMessage();
        }
    }
 

    public function _getUsuarioXNombre($nom='',$eid=''){
        try{
            $sql=$this->_db->query("
               select ape_pat || ' ' || ape_mat || ', ' || nombres as nombrecompleto
               ,p.pid,p.eid,p.oid,p.correo_per,p.sexo from persona as p  
               where p.eid='$eid' and upper(ape_pat) || ' ' || upper(ape_mat) || ', ' || upper(nombres) like '%$nom%'
               order by p.ape_pat,p.ape_mat,p.nombres
            ");
            $row=$sql->fetchAll();
           return $row;  
        }catch (Exception $ex) {
            print "Error: Retornando los datos del alumno deacuerdo a una palabra ingresada".$ex->getMessage();
        }
    }


    public function _getUsuarios($eid=''){
        try{
            $sql=$this->_db->query("
               select ape_pat || ' ' || ape_mat || ', ' || nombres as nombrecompleto
               ,p.pid,p.eid,p.oid,p.correo_per,p.sexo from persona as p  
               where p.eid='$eid' 
               order by p.ape_pat,p.ape_mat,p.nombres
            ");
            $row=$sql->fetchAll();
           return $row;  
        }catch (Exception $ex) {
            print "Error: Retornando los datos del alumno deacuerdo a una palabra ingresada".$ex->getMessage();
        }
    }

    /* Retorna los datos de una persona deacuerdo al nro de dni($dni) */
    public function _getPersonaXDNI($dni='',$eid='',$oid=''){
        try{
            $sql=$this->_db->query("
            select ape_pat || ' ' || ape_mat || ', ' || nombres as nombrecompleto
                ,sexo,pid
            from persona 
               
               where eid='$eid' and oid ='$oid' and pid='$dni'
               
            ");
            $row=$sql->fetchAll();
           return $row;  
        }catch (Exception $ex) {
            print "Error: Retornando los datos del alumno deacuerdo a una palabra ingresada".$ex->getMessage();
        }
    }

     public function _getPropuestaxResponsablePropuesta($cargo=''){
        try{
            $sql=$this->_db->query("
                select * from usuario_categoria as usu
                inner join persona as per
                on usu.dni=per.dni
                where usu.cargo='$cargo' and usu.estado_sistema='A' and usu.estado='A'
            ");
            $row=$sql->fetchAll();
           return $row;  
        }catch (Exception $ex) {
            print "Error: Retornando los datos del alumno deacuerdo a una palabra ingresada".$ex->getMessage();
        }
    }
}