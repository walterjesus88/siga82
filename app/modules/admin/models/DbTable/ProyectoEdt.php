<?php
class Admin_Model_DbTable_ProyectoEdt extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_edt';
    protected $_primary = array("codigo_edt", "codigo_prop_proy", "proyectoid");

    public function _getEdtxProyectoid($proyectoid)
    {
      try {
        $sql = $this->_db->query("select codigo_edt as codigo, nombre_edt as nombre
        from proyecto_edt where proyectoid = '".$proyectoid."'");
        $rows = $sql->fetchAll();
        return $rows;
      } catch (Exception $e) {
        throw new Exception("No hay resultados para el proyecto");
      }
    }
}
