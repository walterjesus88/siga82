<?php
class Admin_TablamaestraController extends Zend_Controller_Action {

    public function init() {
                 $options = array(
            'layout' => 'inicio'
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
       $hola="hola mundo";
       $this->view->variablevista = $hola;
   	}


   	public function listarareaAction() {
      $tb_area = new Admin_Model_DbTable_Area();
      $listatabla = $tb_area->_getAreaAll();
      $this->view->lista_de_areas = $listatabla;
   	}

    public function nuevaareaAction(){
      $tb_area = new Admin_Model_DbTable_Area();
      $listatabla = $tb_area->_getAreaAll();
      $this->view->lista_de_areas = $listatabla;
    }

    public function guardarareaAction() {
    try
    {
        $this->_helper->layout()->disableLayout(); 
        $formdata['areaid']=$areaid = $this->_getParam('areaid');
        $formdata['nombre']=$nombre = $this->_getParam("nombre");
        $formdata['area_padre']=$area_padre = $this->_getParam("area_padre");   
        $formdata['isproyecto']=$isproyecto = $this->_getParam("isproyecto");   
        $formdata['ispropuesta']=$ispropuesta = $this->_getParam("ispropuesta");   
        $formdata['iscontacto']=$iscontacto = $this->_getParam("iscontacto");   
        $formdata['iscomercial']=$iscomercial = $this->_getParam("iscomercial");   
        $formdata['orden']=$orden = $this->_getParam("orden"); 
        $tabla_area=new Admin_Model_DbTable_Area();    
        if($tabla_area->_save($formdata))
        {
          ?>
          <script type="text/javascript">
          alert("Guardado");

            </script>
          <?php
            //echo "Archivo Guardado";
          //$this->_helper->redirector('listararea','tablamaestra','admin');
        }
        else
        {print "Error: ".$e->getMessage();
          ?>
          <script type="text/javascript">
          alert("Error");
          </script>

          <?php
            //echo "Verifique Datos";   
        }
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
    }


    public function actualizarareaAction() {
    try
    {
        $areaid = $this->_getParam('areaid');
        $tabla_area=new Admin_Model_DbTable_Area();    
        $datos=$tabla_area->_getAreaxIndice($areaid);
        $this->view->datosarea=$datos;

        $area_padre=$datos[0]['area_padre'];
        $datos_area_padre=$tabla_area->_getAreaxIndice($area_padre);
        $this->view->datos_area_padre=$datos_area_padre;

        //print_r($datos);
        $listatabla = $tabla_area->_getAreaAll();
        $this->view->lista_de_areas = $listatabla;
     
        
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
    }

  public function updateareaAction() {
    try
    {
        $this->_helper->layout()->disableLayout();
        $areaid = $this->_getParam('areaid');
        $formdata['nombre']=$nombre = $this->_getParam("nombre");
        $formdata['area_padre']=$area_padre = $this->_getParam("area_padre");   
        $formdata['isproyecto']=$isproyecto = $this->_getParam("isproyecto");   
        $formdata['ispropuesta']=$ispropuesta = $this->_getParam("ispropuesta");   
        $formdata['iscontacto']=$iscontacto = $this->_getParam("iscontacto");   
        $formdata['iscomercial']=$iscomercial = $this->_getParam("iscomercial");   
        $formdata['orden']=$orden = $this->_getParam("orden"); 
        //print_r($formdata);
        //echo $areaid;
        $tabla_area=new Admin_Model_DbTable_Area();    
        if($tabla_area->_updatearea($formdata,$areaid))
        {
          ?>
          <script type="text/javascript">
          alert("Actualizado");
            </script>
          <?php
        }
        else
        {
          ?>
          <script type="text/javascript">
          alert("Error");
          </script>
          <?php
        }
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
    }

        public function deleteareaAction(){
        try {
            $this->_helper->layout()->disablelayout();
            $areaid=$this->_getParam("areaid");

            $pk  =   array(                        
                        'areaid'   =>$areaid,

                        );
            print_r($pk);
            $delarea = new Admin_Model_DbTable_Area();
            $delarea->_deletearea($pk); 
                $this->_helper->redirector('listararea','tablamaestra','admin');    
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }


}