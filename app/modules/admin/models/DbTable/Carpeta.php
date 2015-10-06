<?php
class Admin_Model_DbTable_Carpeta extends Zend_Db_Table_Abstract
{
    protected $_name = 'carpeta';
    protected $_primary = array("carpetaid");

    public function _getAll()
    {
      $rows = $this->fetchAll();
      if (!$rows) {
           throw new Exception("No hay resultados para ese transmittal");
      }
      return $rows->toArray();
    }
}
