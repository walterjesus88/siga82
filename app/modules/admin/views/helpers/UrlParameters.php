 <?php 

  class Admin_View_Helper_UrlParameters extends Zend_View_Helper_Abstract
{
    public function urlParameters($page)
    {
        $front = Zend_Controller_Front::getInstance();
        $request = $front->getRequest();
        
        $params = $request->getParams();
        $params['page'] = $page;
        return $this->view->url($params);
    }
}

?> 
