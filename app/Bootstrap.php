<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initViewHelpers() {
            $this->bootstrap('layout');
            $layout = $this->getResource('layout');
            $view = $layout->getView();
            $view->doctype('HTML5');
            $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
            //$view->headMeta()->appendHttpEquiv('Cache-Control', 'no-cache');

            $view->headLink()->prependStylesheet('/css/bootstrap.min.css')
                     ->headLink()->appendStylesheet('/css/bootstrap-reset.css')
                     ->headLink()->appendStylesheet('/assets/font-awesome/css/font-awesome.css')
                     ->headLink()->appendStylesheet('/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css')
                     ->headLink()->appendStylesheet('/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css')
                     ->headLink()->appendStylesheet('/css/owl.carousel.css')
                     ->headLink()->appendStylesheet('/css/slidebars.css')
                     ->headLink()->appendStylesheet('/css/soon.css')
                     ->headLink()->appendStylesheet('/css/style.css')
                     ->headLink()->appendStylesheet('/css/style-responsive.css');

            $view   ->headScript()->appendFile('/js/jquery.js')
                        ->headScript()->appendFile('/js/bootstrap.min.js')
                        ->headScript()->appendFile('/js/jquery.dcjqaccordion.2.7.js')
                        ->headScript()->appendFile('/js/jquery.scrollTo.min.js')
                        ->headScript()->appendFile('/js/jquery.nicescroll.js')
                        ->headScript()->appendFile('/js/jquery.sparkline.js')
                        ->headScript()->appendFile('/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js')
                        ->headScript()->appendFile('/assets/bootstrap-datepicker/js/bootstrap-datepicker.js')
                        ->headScript()->appendFile('/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')
                        ->headScript()->appendFile('/assets/bootstrap-daterangepicker/moment.min.js')
                        ->headScript()->appendFile('/assets/bootstrap-daterangepicker/daterangepicker.js')
                        ->headScript()->appendFile('/js/owl.carousel.js')
                        ->headScript()->appendFile('/js/jquery.customSelect.min.js')
                        ->headScript()->appendFile('/js/respond.min.js')
                        ->headScript()->appendFile('/js/slidebars.min.js')
                        ->headScript()->appendFile('/js/sparkline-chart.js')
                        ->headScript()->appendFile('/js/easy-pie-chart.js')
                        ->headScript()->appendFile('/js/count.js');

                            

            $view->headTitle()->setSeparator(' - ');
            $view->headTitle('Sistema de Planificación y Control | Anddes');
            Zend_Session::start();
            Zend_Layout::startMvc(APPLICATION_PATH . '/layouts/scripts');
            $view = Zend_Layout::getMvcInstance()->getView();
            $viewRenderer = Zend_controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
            $viewRenderer->setView($view);
            $moneda = new Zend_Locale('es_PE');
            Zend_Registry::set('Zend_Locale', $moneda);
            return;
        }

    protected function _initDbAdaptersToRegistry()
            {
                $this->bootstrap('multidb');
                $resource = $this->getPluginResource('multidb');
                $resource->init();

                $Adapter1 = $resource->getDb('db1');
                $Adapter2 = $resource->getDb('db2');
                $Adapter3 = $resource->getDb('db3');
                Zend_Registry::set('Adaptador1', $Adapter1);
                Zend_Registry::set('Adaptador2',$Adapter2);
                Zend_Registry::set('Adaptador3',$Adapter3);
            }
}