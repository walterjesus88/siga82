<?php 
class Admin_Model_DbTable_Persona extends Zend_Db_Table_Abstract
{
    protected $_name = 'persona';
    protected $_primary = array("dni");


     /* Lista toda las Personas */    
    public function _getTodasPersonas(){
        try{
            
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getFullNamePersona($dni){
        try{
            $name['dni']=$dni;
            $f = $this->fetchAll();
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


    public function _updateX($data,$pk)
    {
        try{
            if ($pk['dni']=='' ) return false;
            $where = "dni = '".$pk['dni']."' ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
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
    // public function _getPersonaXDNI($dni=''){
    //     try{
    //         $sql=$this->_db->query("
    //         select ape_paterno || ' ' || ape_materno || ', ' || nombres as nombrecompleto,ape_paterno,ape_materno,sexo,estado_civil
    //         from persona 
    //         where dni='$dni'
               
    //         ");
    //         $row=$sql->fetchAll();
    //        return $row;  
    //     }catch (Exception $ex) {
    //         print "Error: Retornando los datos del alumno deacuerdo a una palabra ingresada".$ex->getMessage();
    //     }
    // }

    public function _getOne($where=array()){
        try {
            if ($where["dni"]=='') return false;
                
                $wherestr= "dni = '".$where['dni']."' ";

                $row = $this->fetchRow($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
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

    public function _getPersonasOrdenadoxApellido(){
        try{
            $sql=$this->_db->query("
                select * from persona
                order by ape_paterno
            ");
            $row=$sql->fetchAll();
           return $row;  
        }catch (Exception $ex) {
            print "Error: Retornando los datos del alumno deacuerdo a una palabra ingresada".$ex->getMessage();
        }
    }

    public function _getBuscarPersonas($busca=null){
        try {
            //if ($busca=='') return false;
            $sql=$this->_db->query("
            select 
            p.ape_paterno,p.ape_materno,p.nombres,p.alias,p.segundo_nombre,p.tercer_nombre,p.dni            
            from persona as p            
            where p.ape_paterno like '$busca%' or p.ape_materno like '$busca%' or p.nombres like '$busca%' or p.alias like '$busca%' or p.segundo_nombre like '$busca%' or p.tercer_nombre like '$busca%'   
            order by p.ape_paterno,p.ape_materno 
            ");
            $r = $sql->fetchAll();
            if($r) return $r;
            return false;
        }  catch (Exception $ex){
            print "Error: Retornando los alumnos de una escuela en un periodo".$ex->getMessage();
        }
    }

    public function _getBuscarPersonaXInicial($busca=null){
        try {
            //if ($busca=='') return false;
            $sql=$this->_db->query("
            select 
            p.ape_paterno,p.ape_materno,p.nombres,p.alias,p.segundo_nombre,p.tercer_nombre,p.dni            
            from persona as p            
            where left(p.ape_paterno,1) ='$busca'    
            order by p.ape_paterno,p.ape_materno 
            ");
            $r = $sql->fetchAll();
            if($r) return $r;
            return false;
        }  catch (Exception $ex){
            print "Error: Retornando los alumnos de una escuela en un periodo".$ex->getMessage();
        }
    }



}