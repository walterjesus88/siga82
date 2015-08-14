<?php
class Admin_Model_DbTable_Tipoenvio extends Zend_Db_Table_Abstract
{
    protected $_name = 'tipo_envio';
    protected $_primary = array("tipo", "codigo");

    public function _getAll()
    {
      try{
        $res = [];
        $f = $this->fetchAll();
        if ($f) {
          $data = $f->toArray();
          $i = 0;
          foreach ($data as $tipo) {
            $fila['empresa'] = $tipo['tipo'];
            $fila['abrev'] = $tipo['codigo'];
            $fila['emitido_para'] = $tipo['emitido_para'];
            $res[$i] = $fila;
            $i++;
          }
          return $res;
        }
        return false;
      }catch (Exception $e){
        print "Error: Al momento de leer todos los tipos de envio".$e->getMessage();
      }
    }

    public function _getEmpresas()
    {
      try {
        $sql = $this->_db->query("select distinct tipo from tipo_envio
        order by tipo");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _getEmisiones($tipo_envio)
    {
      try {
        $sql = $this->_db->query("select codigo, emitido_para from tipo_envio
        where tipo ='".$tipo_envio."'");
        $row = $sql->fetchAll();
        return $row;
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    public function _setTipoEnvio($data)
    {
      try {
        $sql = $this->_db->query("insert into tipo_envio values ('".
        $data['empresa']."', '".$data['abrev']."', '".$data['emitido_para']."')");
        $row = $sql->fetchAll();
        $resp = $this->_getAll();
        return $resp;
      } catch (Exception $e) {
        print $e->getMessage();
      }

    }

}
