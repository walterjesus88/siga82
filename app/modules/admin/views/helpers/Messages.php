<?php 
 
class Zend_View_Helper_Messages extends Zend_View_Helper_Abstract {
 
    public function messages() {
      return $this;
    }
 
    public function error($msg) {
      return "<div class='alert alert-error'>
        		<a class='close' data-dismiss='alert'>&times;</a>
        		<strong>&iexcl;Error!</strong> $msg 
        	  </div>";
    }
 
    public function success($msg) {
      return "<div class='alert alert-success'>
        		<a class='close' data-dismiss='alert'>&times;</a>
        		<strong>&iexcl;Bien!</strong> $msg 
        	  </div>";
    }

 	public function ejemplo($msg) {
      return "<div class='success'>$msg</div>";
	
	}
}
?> 
