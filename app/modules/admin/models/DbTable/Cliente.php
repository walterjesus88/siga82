<?php 
class Admin_Model_DbTable_Cliente extends Zend_Db_Table_Abstract
{
    protected $_name = 'cliente';
    protected $_primary = array("clienteid");

    /* Lista toda las Cliente */
   


     /* Lista toda las Personas */    
    public function _getClienteAll(){
        try{
            $f = $this->fetchAll();
            if ($f) return $f->toArray ();
            return false;
        }catch (Exception $e){
            print "Error: Al momento de leer todas las personas".$e->getMessage();
        }
    }

    public function _getClientexIndice($clienteid)
     {
        try{
            $sql=$this->_db->query("
               select * from cliente 
               where clienteid='$clienteid'  

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getClientexPropuestaxRevisionxCodigo($propuestaid,$revision,$codigo)
     {
        try{
            $sql=$this->_db->query("
               select * from cliente as cli inner join propuesta as pro
               on cli.clienteid=pro.clienteid
               where pro.propuestaid='$propuestaid'  and pro.revision='$revision' and pro.codigo_prop_proy='$codigo'

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }


     public function _getClientexTipo($tipo)
     {
        try{
            $sql=$this->_db->query("
               select * from cliente 
               where tipo_cliente='$tipo' order by nombre_comercial

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }
    
    public function _getClientexTipoxSocio()
     {
        try{
            $sql=$this->_db->query("
               select * from cliente 
               where tipo_cliente='cliente' and issocio='S' order by nombre_comercial

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }

    public function _getClienteAllOrdenado()     
    {
        try{
            $sql=$this->_db->query("
               select * from cliente 
               order by nombre_comercial asc

            ");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();
        }
    }



    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            if($where['clienteid']=='' ) return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("cliente");
                else $select->from("cliente",$attrib);
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




     public function _buscarCliente($cliente){
        try{
            $sql=$this->_db->query("
                select * from cliente where lower(nombre_comercial) like '%$cliente%'
                order by nombre_comercial asc");
            $row=$sql->fetchAll();
            return $row;           
            }  
            
           catch (Exception $ex){
            print $ex->getMessage();

        }
    }

    public function _getOne($where=array()){
        try{
            if ($where['clienteid']=='' ) return false;
            $wherestr="clienteid = '".$where['clienteid']."' ";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Add_reportacad_adm ".$e->getMessage();
        }
    }

 


}