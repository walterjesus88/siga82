<?php
class Admin_Model_DbTable_ProyectoEdt extends Zend_Db_Table_Abstract
{
    protected $_name = 'proyecto_edt';
    protected $_primary = array("codigo_edt", "codigo_prop_proy", "proyectoid");

    public function _getEdtxProyectoid($proyectoid)
    {
      try {
        $sql = $this->_db->query("select codigo_edt as codigo, nombre_edt as nombre,descripcion_edt as descripcion
        from proyecto_edt where proyectoid = '".$proyectoid."'");
        $rows = $sql->fetchAll();
        return $rows;
      } catch (Exception $e) {
        throw new Exception("No hay resultados para el proyecto");
      }
    }

    public function _save($data)
    {
        try{

            if ($data['codigo_edt']=='' ||  $data['codigo_prop_proy']=='' ||  $data['proyectoid']=='' ) return false;
            return $this->insert($data);
            return false;
        }catch (Exception $e){
                print "Error: Registration ".$e->getMessage();
        }
    }

    public function _update($data,$pk)
    {
        try{
            //if ($pk['id_tproyecto']=='' ||  $pk['proyectoid']=='' ) return false;
            $where = "
                codigo_prop_proy = '".$pk['codigo_prop_proy']."' and 
                proyectoid = '".$pk['proyectoid']."' and 
                codigo_edt = '".$pk['codigo_edt']."' 
            ";
            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update curva".$e->getMessage();
        }
    }

}
