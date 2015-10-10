<?php
/**
* 
*/
class Admin_TbpermisosController extends Zend_Controller_Action
{
	
	// listar todos los permisos x user
	public function listarpermisosAction() {
      $tb_acl = new Admin_Model_DbTable_Acl();
      $listatabla = $tb_acl->_getAcl();
      $this->view->lista_permisos = $listatabla;
   	}


   	
}
