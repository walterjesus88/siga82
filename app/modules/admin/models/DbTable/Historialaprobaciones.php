<?php 
class Admin_Model_DbTable_Historialaprobaciones extends Zend_Db_Table_Abstract
{
    protected $_name = 'historial_aprobaciones';
    protected $_primary = array("semanaid","uid_empleado", "dni_empleado", "areaid_empleado","numero_historial");


    public function _save($data)
    {
        try{
            if ($data['semanaid']=='' || $data['uid_empleado']=='' || $data['dni_empleado']=='' || $data['numero_historial']==''   ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: guardando comentario  ".$e->getMessage();
        }
    }


    public function _getOne($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                //$wherestr= "dni = '".$where['dni']."'  ";
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."' and cargo = '".$where['cargo']."'  ";

                $row = $this->fetchRow($wherestr);

                //print_r($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getOnexUsuario($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                //$wherestr= "dni = '".$where['dni']."'  ";
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."'   ";

                $row = $this->fetchRow($wherestr);

                //print_r($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _getOnexUsuarioxValidador($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                //$wherestr= "dni = '".$where['dni']."'  ";
                $wherestr="uid_validacion = '".$where['uid_validacion']."' and estado_usuario = '".$where['estado_usuario']."' and semanaid = '".$where['semanaid']."'   
                    and etapa = '".$where['etapa']."' and  uid = '".$where['uid']."'  and   dni = '".$where['dni']."' and  dni_validacion = '".$where['dni_validacion']."'
                ";

                $row = $this->fetchRow($wherestr);

                //print_r($wherestr);
                if($row) return $row->toArray();
                return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


        public function _getOnexUsuarioxJefe($where=array()){
        try {
                //if ($where["dni"]=='') return false;                
                //$wherestr= "dni = '".$where['dni']."'  ";
                $wherestr="semanaid = '".$where['semanaid']."' and uid = '".$where['uid']."' and dni = '".$where['dni']."'  
                and dni_validacion = '".$where['dni_validacion']."' and uid_validacion = '".$where['uid_validacion']."'

                  "; 

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
            if ($pk['semanaid']=='' || $pk['uid_empleado']=='' || $pk['dni_empleado']=='' || $pk['areaid_empleado']=='') return false;
            $where = " semanaid = '".$pk['semanaid']."' and uid = '".$pk['uid']."' and dni = '".$pk['dni']."' and cargo = '".$pk['cargo']."'";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }

 public function _updateXUsuario($data,$pk)
    {
        try{
            if ($pk['semanaid']=='' || $pk['uid']=='' || $pk['dni']=='' || $pk['cargo']=='') return false;
            $where = " semanaid = '".$pk['semanaid']."' and uid = '".$pk['uid']."' and dni = '".$pk['dni']."' 
            and cargo = '".$pk['cargo']."'
                       and orden = '".$pk['orden']."'

            ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update persona".$e->getMessage();
        }
    }


    public function _getBuscarEmpleadoxSemana($semana,$uid,$dni)
    {
        try{
            $sql=$this->_db->query("
                select  * from historial_aprobaciones 
                where semanaid='$semana' and uid_empleado='$uid'
                and dni_empleado='$dni' order by numero_historial

            ");
            // print_r($sql);
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getBuscarEmpleadoxSemanaxEstado($where=array()){
        try {
                $wherestr="semanaid = '".$where['semanaid']."' 
                and uid_empleado = '".$where['uid_empleado']."' 
                and dni_empleado = '".$where['dni_empleado']."'  
                and etapa_validador = '".$where['etapa_validador']."' 
                and estado_historial = '".$where['estado_historial']."'
                  "; 
                $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }



    public function _getBuscarEmpleadoxSemanaxEstadoxCodigoAprobacion($etapa_validador,$estado_historial,$codigoaprobacion_empleado)
    {
        try{
            $sql=$this->_db->query("
              
                select  * from historial_aprobaciones as historial
                inner join suma_controlsemana as controlsemana
                    on historial.semanaid=controlsemana.semanaid and historial.uid_empleado=controlsemana.uid 
                    and historial.dni_empleado=controlsemana.dni 
                where historial.etapa_validador='$etapa_validador' and historial.estado_historial='$estado_historial' 
                and historial.codigoaprobacion_empleado='$codigoaprobacion_empleado'
                order by historial.semanaid asc
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getBuscarEmpleadoxSemanaxEstadoxAprobacionFiltro2($where=array()){
        try {
                $wherestr="semanaid = '".$where['semanaid']."' 
                and uid_empleado = '".$where['uid_empleado']."' 
                and dni_empleado = '".$where['dni_empleado']."'  
                and etapa_validador = '".$where['etapa_validador']."' 
                and estado_historial = '".$where['estado_historial']."'
                and uid_validador = '".$where['uid_validador']."'
                and dni_validador = '".$where['dni_validador']."'
                

                  "; 
                $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }

    public function _update($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
            print "Error: Actualizando un registro de Persona".$ex->getMessage();
        }
    }

    public function _getBuscarEmpleadoxSemanaxEstadoxAprobacionGP($where=array()){
        try {
                $wherestr="semanaid = '".$where['semanaid']."' 
                and uid_empleado = '".$where['uid_empleado']."' 
                and dni_empleado = '".$where['dni_empleado']."'  
                and etapa_validador = '".$where['etapa_validador']."' 
                and estado_historial = '".$where['estado_historial']."'
                and uid_validador = '".$where['uid_validador']."'
                and dni_validador = '".$where['dni_validador']."'
                

                  "; 
                $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        } catch (Exception $e) {
            print "Error: Read One Condition".$e->getMessage();
        }
    }


    public function _getBuscarEmpleadoxHojatiempohistorico($etapa_validador,$codigoaprobacion_empleado)
    {
        try{
            $sql=$this->_db->query("
              
                select  * from historial_aprobaciones as historial
                inner join suma_controlsemana as controlsemana
                    on historial.semanaid=controlsemana.semanaid and historial.uid_empleado=controlsemana.uid 
                    and historial.dni_empleado=controlsemana.dni 
                where historial.etapa_validador='$etapa_validador' and historial.estado_historial in ('A','RGP','R')
                and historial.codigoaprobacion_empleado='$codigoaprobacion_empleado'
                order by historial.semanaid asc
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

   

    public function _getListarHistoricoxAprobador($uid,$dni)
    {
        try{
            $sql=$this->_db->query("
              
                select  * from historial_aprobaciones as historial 
                inner join suma_controlsemana as controlsemana
                    on historial.semanaid=controlsemana.semanaid and historial.uid_empleado=controlsemana.uid 
                    and historial.dni_empleado=controlsemana.dni 
                where historial.uid_validador='$uid' and historial.dni_validador='$dni'
                 order by historial.estado_historial desc
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getUltimasFilasxPersona($uid,$dni,$semanaid)
    {
        try{
            $sql=$this->_db->query("
              
                select  * from historial_aprobaciones 
                    where  uid_empleado='$uid' and  
                    dni_empleado='$dni' 
                    and semanaid='$semanaid' 
                    order by numero_historial desc
                    limit 1 
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }
}