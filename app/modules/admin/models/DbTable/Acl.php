<?php
class Admin_Model_DbTable_Acl extends Zend_Db_Table_Abstract
{
    protected $_name = 'acl';
    protected $_primary = array("moduloid","controlador","vista","uid","dni","areaid");

    public function _getAcl(){
        try{
            $f=$this->fetchAll();
            if($f) return $f->toArray(); 
      return false;
        }catch (Exception $ex){
            print "Error: Listando Acl".$ex->getMessage();
        }
     }

    public function _save($data){
        try{
            if ($data['moduloid']=="" ) return false;
            return $this->insert($data);
        }catch (Exception $ex){
            print "Error: Insertando un nuevo Acl".$ex->getMessage();
        }
    }

    public function eliminar($moduloid,$vista,$controlador,$uid,$dni,$areaid){
        try{
            if ($proyectoid=='') return false;
            return $this->delete("moduloid='$moduloid' and areaid='$areaid' and vista='$vista' and controlador='$controlador' and uid='$uid'
                    and dni='$dni'") ;
        }catch (Exception $ex){
            print "Error: Eliminando un registro de Acl".$ex->getMessage();
        }
    }


    public function _update($data,$str=''){
        try{
            if ($str=="") return false;
            return $this->update($data,$str);
        }catch (Exception $ex){
            print "Error: Actualizando un registro de Acl".$ex->getMessage();
        }
    }


    public function _getAclxUsuario($uid,$dni,$areaid)
     {
        try{
            $sql=$this->_db->query("
               select distinct(acl.moduloid), mod.nombre_modulo from acl as acl
               inner join modulo as mod
               on acl.moduloid=mod.moduloid and acl.vista=mod.vista and acl.controlador=mod.controlador
               where acl.uid='$uid' and acl.dni='$dni' 
               and acl.areaid='$areaid' and acl.estado='A'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

        public function _getAclxUsuarioxModulo($uid,$dni,$areaid,$moduloid)
     {
        try{
            $sql=$this->_db->query("
               select * from acl as acl
               inner join modulo as mod
               on acl.moduloid=mod.moduloid and acl.vista=mod.vista and acl.controlador=mod.controlador
               where acl.uid='$uid' and acl.dni='$dni' 
               and acl.areaid='$areaid' and acl.estado='A' and mod.moduloid='$moduloid'
            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }
   
}


