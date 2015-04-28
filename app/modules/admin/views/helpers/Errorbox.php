<?php 
 
class Zend_View_Helper_Messages extends Zend_View_Helper_Abstract {
 
    public function messages() {
      return $this;
    }
 
    public function error($msg) {
      return "<div class='error'>$msg</div>";
    }
 
    public function success($msg) {
      return "<div class='success'>$msg</div>";
    }

	public function ejemplo($idUsuario) {
	 
	}
}
?> 
