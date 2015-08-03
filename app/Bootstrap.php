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
                     ->headLink()->appendStylesheet('/assets/data-tables/DT_bootstrap.css')
                     ->headLink()->appendStylesheet('/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css')
                     ->headLink()->appendStylesheet('/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css')
                     ->headLink()->appendStylesheet('/css/owl.carousel.css')
                     ->headLink()->appendStylesheet('/css/slidebars.css')
                     ->headLink()->appendStylesheet('/css/soon.css')
                     ->headLink()->appendStylesheet('/css/jquery-ui.css')
                     ->headLink()->appendStylesheet('/css/style.css')
                     ->headLink()->appendStylesheet('/css/style-responsive.css')
                     ->headLink()->appendStylesheet('/css/perfect-scrollbar.css')
                     ->headLink()->appendStylesheet('/css/scrollTable.css')
                     ->headLink()->appendStylesheet('/css/angular-chart.css');

            $view   ->headScript()->appendFile('/js/jquery.js')
                        ->headScript()->appendFile('/js/metodosglobales.js')
                        ->headScript()->appendFile('/js/jquery-ui-1.9.2.custom.min.js')
                        ->headScript()->appendFile('/js/jquery-migrate-1.2.1.min.js')
                        ->headScript()->appendFile('/js/bootstrap.min.js')
                        //begin: librerias para el funcionamiento de angular datatable
                        ->headScript()->appendFile('/js/adt/vendor/highlightjs/highlight.pack.js')
                        ->headScript()->appendFile('/js/adt/vendor/backtotop/backtotop.min.js')
                        ->headScript()->appendFile('/js/adt/vendor/angular/angular.js')
                        ->headScript()->appendFile('/js/adt/vendor/angular-highlightjs/angular-highlightjs.min.js')
                        ->headScript()->appendFile('/js/adt/vendor/angular-resource/angular-resource.min.js')
                        ->headScript()->appendFile('/js/adt/vendor/angular-ui-router/release/angular-ui-router.min.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables/media/js/jquery.dataTables.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables-colreorder/js/dataTables.colReorder.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables-colvis/js/dataTables.colVis.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables-tabletools/js/dataTables.tableTools.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables-responsive/js/dataTables.responsive.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables-scroller/js/dataTables.scroller.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables-columnfilter/js/dataTables.columnFilter.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables-fixedcolumns/js/dataTables.fixedColumns.js')
                        ->headScript()->appendFile('/js/adt/vendor/datatables-fixedheader/js/dataTables.fixedHeader.js')
                        ->headScript()->appendFile('/js/adt/vendor/angular-bootstrap/ui-bootstrap.min.js')
                        ->headScript()->appendFile('/js/adt/vendor/angular-bootstrap/ui-bootstrap-tpls.min.js')
                        ->headScript()->appendFile('/js/adt/vendor/angular-translate/angular-translate.min.js')
                        ->headScript()->appendFile('/js/adt/src/angular-datatables.js')
                        ->headScript()->appendFile('/js/adt/src/angular-datatables.util.js')
                        ->headScript()->appendFile('/js/adt/src/angular-datatables.options.js')
                        ->headScript()->appendFile('/js/adt/src/angular-datatables.instances.js')
                        ->headScript()->appendFile('/js/adt/src/angular-datatables.factory.js')
                        ->headScript()->appendFile('/js/adt/src/angular-datatables.renderer.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/bootstrap/angular-datatables.bootstrap.options.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/bootstrap/angular-datatables.bootstrap.colvis.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/bootstrap/angular-datatables.bootstrap.tabletools.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/bootstrap/angular-datatables.bootstrap.js')
                        ->headScript()->appendFile('/js/adt/src/angular-datatables.directive.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/colvis/angular-datatables.colvis.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/colreorder/angular-datatables.colreorder.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/tabletools/angular-datatables.tabletools.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/scroller/angular-datatables.scroller.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/columnfilter/angular-datatables.columnfilter.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/fixedcolumns/angular-datatables.fixedcolumns.js')
                        ->headScript()->appendFile('/js/adt/src/plugins/fixedheader/angular-datatables.fixedheader.js')
                        ->headScript()->appendFile('/js/modulos-angular.js')
                        //end: librerias para el funcionamiento de angular datatables
                        ->headScript()->appendFile('/js/jquery.dcjqaccordion.2.7.js')
                        ->headScript()->appendFile('/js/jquery.scrollTo.min.js')
                        ->headScript()->appendFile('/js/jquery.nicescroll.js')
                        ->headScript()->appendFile('/js/jquery.sparkline.js')
                        ->headScript()->appendFile('/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js')
                        ->headScript()->appendFile('/assets/bootstrap-datepicker/js/bootstrap-datepicker.js')
                        ->headScript()->appendFile('/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')
                        ->headScript()->appendFile('/assets/bootstrap-daterangepicker/moment.min.js')
                        ->headScript()->appendFile('/assets/bootstrap-daterangepicker/daterangepicker.js')
                        ->headScript()->appendFile('/assets/data-tables/jquery.dataTables.js')
                        ->headScript()->appendFile('/assets/data-tables/DT_bootstrap.js')
                        ->headScript()->appendFile('/js/owl.carousel.js')
                        ->headScript()->appendFile('/js/jquery.customSelect.min.js')
                        ->headScript()->appendFile('/js/respond.min.js')
                        ->headScript()->appendFile('/js/slidebars.min.js')
                        ->headScript()->appendFile('/js/sparkline-chart.js')
                        ->headScript()->appendFile('/js/easy-pie-chart.js')
                        ->headScript()->appendFile('/js/advanced-form-components.js')
                        ->headScript()->appendFile('/js/jquery-ui.js')
                        ->headScript()->appendFile('/js/datepicker-es.js')
                        ->headScript()->appendFile('/js/count.js')
                        ->headScript()->appendFile('/js/perfect-scrollbar.jquery.js')
                        ->headScript()->appendFile('/js/jspdf/tableExport.js')
                        ->headScript()->appendFile('/js/jspdf/jquery.base64.js')
                        ->headScript()->appendFile('/js/jspdf/libs/sprintf.js')
                        ->headScript()->appendFile('/js/jspdf/jspdf.js')
                        ->headScript()->appendFile('/js/jspdf/jspdf.plugin.from_html.js')
                        ->headScript()->appendFile('/js/jspdf/FileSaver.js')
                        ->headScript()->appendFile('/js/jspdf/libs/base64.js')
                        ->headScript()->appendFile('/js/ui-bootstrap-0.13.1.min.js');

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
