<?php
class Admin_Form_Proyecto extends Zend_Form{    
    public function init(){
    $this->setName("frmproyecto");
    $proyectoid= new Zend_Form_Element_Text('proyectoid');
    $proyectoid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $proyectoid->setAttrib("maxlength", "100");
    $proyectoid->setAttrib('class', 'form-control');

    $nombre_proyecto= new Zend_Form_Element_Text('nombre_proyecto');
    $nombre_proyecto->removeDecorator('Label')->removeDecorator("HtmlTag");
    $nombre_proyecto->setAttrib("maxlength", "100");
    $nombre_proyecto->setAttrib('class', 'form-control');

    $revision= new Zend_Form_Element_Text('revision');
    $revision->removeDecorator('Label')->removeDecorator("HtmlTag");
    $revision->setAttrib("maxlength", "100");
    $revision->setAttrib('class', 'form-control');

    $fecha_inicio= new Zend_Form_Element_Text('fecha_inicio');
    $fecha_inicio->removeDecorator('Label')->removeDecorator("HtmlTag");
    $fecha_inicio->setAttrib("maxlength", "100");
    $fecha_inicio->setAttrib('class', 'form-control default-date-picker');

    $propuestaid= new Zend_Form_Element_Text('propuestaid');
    $propuestaid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $propuestaid->setAttrib("maxlength", "100");
    $propuestaid->setAttrib('class', 'form-control');
    
    $codigo_prop_proy= new Zend_Form_Element_Text('codigo_prop_proy');
    $codigo_prop_proy->setRequired(true)->addErrorMessage('Este campo es requerido');
    $codigo_prop_proy->removeDecorator('Label')->removeDecorator("HtmlTag");
    $codigo_prop_proy->setAttrib("maxlength", "100");
    $codigo_prop_proy->setAttrib('class', 'form-control');

    $clienteid= new Zend_Form_Element_Select('clienteid');
    $clienteid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $clienteid->setAttrib('class', 'form-control');    
    $bdcliente = new Admin_Model_DbTable_Cliente(); 
    $listcliente=$bdcliente->_getClienteAll();
    foreach ($listcliente as $cliente ){
        $clienteid->addMultiOption($cliente['clienteid'],$cliente['nombre_comercial']);
    }

    $unidad_minera= new Zend_Form_Element_Select('unidad_mineraid');
    $unidad_minera->removeDecorator('Label')->removeDecorator("HtmlTag");
    $unidad_minera->setAttrib('class', 'form-control');
    $bdunidad_minera = new Admin_Model_DbTable_Unidadminera();
    $listunidad_minera=$bdunidad_minera->_getUnidadmineraAll();
    foreach ($listunidad_minera as $uminera ){
        $unidad_minera->addMultiOption($uminera['unidad_mineraid'],$uminera['nombre']);
    }
    
    $gerente_proyecto= new Zend_Form_Element_Select('gerente_proyecto');
    $gerente_proyecto->removeDecorator('Label')->removeDecorator("HtmlTag");
    $gerente_proyecto->setAttrib('class', 'chosen-select');    
    $gerente_proyecto->setAttrib('style', 'width:100%'); 
    $bdusuario = new Admin_Model_DbTable_Persona(); 
    $gerente_proyecto->addMultiOption('0',' -- Seleccione -- ');
    $listgerente=$bdusuario->_getPropuestaxResponsablePropuesta('GERENTE-PROY');
    foreach ($listgerente as $gerente ){        
        $porciones = explode(".", $gerente['uid']);
        $nombre=ucwords($porciones[0])." ".ucwords($porciones[1]);
        $gerente_proyecto->addMultiOption($gerente['uid'],$nombre);
    }

    $control_proyecto= new Zend_Form_Element_Select('control_proyecto');
    $control_proyecto->removeDecorator('Label')->removeDecorator("HtmlTag");
    $control_proyecto->setAttrib('class', 'chosen-select');  
    $control_proyecto->setAttrib('style', 'width:100%'); 
    $bdusuario = new Admin_Model_DbTable_Persona(); 
    $control_proyecto->addMultiOption('0',' -- Seleccione -- ');
    $listcontrol=$bdusuario->_getPersonaxArea('26');
    foreach ($listcontrol as $control ){        
        $porciones = explode(".", $control['uid']);
        $nombrecontrol=ucwords($porciones[0])." ".ucwords($porciones[1]);
        $control_proyecto->addMultiOption($control['uid'],$nombrecontrol);
    }

    $control_documentario= new Zend_Form_Element_Select('control_documentario');
    $control_documentario->removeDecorator('Label')->removeDecorator("HtmlTag");
    $control_documentario->setAttrib('class', 'chosen-select');     
    $control_documentario->setAttrib('style', 'width:100%'); 
    $bdusuario = new Admin_Model_DbTable_Persona(); 
    $control_documentario->addMultiOption('0',' -- Seleccione -- ');
    $listcontroldoc=$bdusuario->_getPersonaxArea('16');
    foreach ($listcontroldoc as $controldoc ){        
        $porciones = explode(".", $controldoc['uid']);
        $nombrecontroldoc=ucwords($porciones[0])." ".ucwords($porciones[1]);
        $control_documentario->addMultiOption($controldoc['uid'],$nombrecontroldoc);
    }

    $descripcion = new Zend_Form_Element_Textarea('descripcion');
    $descripcion->removeDecorator('Label')
                    ->setRequired(true)
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('rows', '9')
                    ->setAttrib('style', 'resize : none;')
                    ->setAttrib('title', 'Descripción');

    $observacion = new Zend_Form_Element_Textarea('observacion');
    $observacion->removeDecorator('Label')
                    ->setRequired(true)
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('rows', '9')
                    ->setAttrib('style', 'resize : none;')
                    ->setAttrib('title', 'Descripción');

    $tipo_proyecto = new Zend_Form_Element_Select('tipo_proyecto');
    $tipo_proyecto->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el tipo');
    $tipo_proyecto->setLabel("Ingrese el Tipo de Documento: ");
    $tipo_proyecto->removeDecorator('Label');
    $tipo_proyecto->setAttrib('class','form-control');
    $tipo_proyecto->addMultiOption('Ingeniería',"Ingeniería");
    $tipo_proyecto->addMultiOption('Geotécnia',"Geotécnia");
    $tipo_proyecto->addMultiOption('Construcción',"Construcción");
    $tipo_proyecto->addMultiOption('Ambinetal',"Ambiental");
    $tipo_proyecto->addMultiOption('Otros',"Otros");
    $tipo_proyecto->setAttrib('class', 'form-control');


    $tag= new Zend_Form_Element_Text('tag');
    $tag->removeDecorator('Label')->removeDecorator("HtmlTag");
    $tag->setAttrib("maxlength", "100");
    $tag->setAttrib('class', 'form-control');

  
    $estado = new Zend_Form_Element_Select('estado');
    $estado->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el estado');
    $estado->setLabel("Ingrese el Tipo de Documento: ");
    $estado->removeDecorator('Label');
    $estado->setAttrib('class','form-control');
    $estado->addMultiOption('A',"Activo");
    $estado->addMultiOption('C',"Cerrado");
    $estado->addMultiOption('CA',"Cancelado");
    $estado->addMultiOption('PA',"Paralizado");
    

    $oid = new Zend_Form_Element_Select('oid');
    $oid->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el estado');
    $oid->setLabel("Ingrese el Tipo de Documento: ");
    $oid->removeDecorator('Label');
    $oid->setAttrib('class','form-control');
    $oid->addMultiOption('AND-10',"Anddes - Perú");
    $oid->addMultiOption('AND-20',"Anddes - Argentina");
    $oid->addMultiOption('AND-40',"Anddes - Chile");
    $oid->setAttrib('class', 'form-control');

    $submit = new Zend_Form_Element_Submit('guardar');
    $submit->removeDecorator('HtmlTag'); 
    $submit->setAttrib('class','form-control');
    $submit->removeDecorator('Label')->removeDecorator('DtDdWrapper');
    $submit->removeDecorator('Label')->removeDecorator("HtmlTag");
  
    


    $ubicacion= new Zend_Form_Element_Text('ubicacion');
    $ubicacion->removeDecorator('Label')->removeDecorator("HtmlTag");
    $ubicacion->setAttrib("maxlength", "100");
    $ubicacion->setAttrib('class', 'form-control');



    $paisid= new Zend_Form_Element_Text('paisid');
    $paisid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $paisid->setAttrib("maxlength", "100");
    $paisid->setAttrib('class', 'form-control');

    $departamentoid= new Zend_Form_Element_Text('departamentoid');
    $departamentoid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $departamentoid->setAttrib("maxlength", "100");
    $departamentoid->setAttrib('class', 'form-control');

    $provinciaid= new Zend_Form_Element_Text('provinciaid');
    $provinciaid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $provinciaid->setAttrib("maxlength", "100");
    $provinciaid->setAttrib('class', 'form-control');

    $distritoid= new Zend_Form_Element_Text('distritoid');
    $distritoid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $distritoid->setAttrib("maxlength", "100");
    $distritoid->setAttrib('class', 'form-control');

    $prioridad= new Zend_Form_Element_Text('prioridad');
    $prioridad->removeDecorator('Label')->removeDecorator("HtmlTag");
    $prioridad->setAttrib("maxlength", "100");
    $prioridad->setAttrib('class', 'form-control');

    $progreso= new Zend_Form_Element_Text('progreso');
    $progreso->removeDecorator('Label')->removeDecorator("HtmlTag");
    $progreso->setAttrib("maxlength", "100");
    $progreso->setAttrib('class', 'form-control');

    $fecha_probable_cierre= new Zend_Form_Element_Text('fecha_probable_cierre');
    $fecha_probable_cierre->removeDecorator('Label')->removeDecorator("HtmlTag");
    $fecha_probable_cierre->setAttrib("maxlength", "100");
    $fecha_probable_cierre->setAttrib('class', 'form-control');
    $fecha_probable_cierre->setAttrib('class', 'default-date-picker');

    $fecha_cierre= new Zend_Form_Element_Text('fecha_cierre');
    $fecha_cierre->removeDecorator('Label')->removeDecorator("HtmlTag");
    $fecha_cierre->setAttrib("maxlength", "100");
    $fecha_cierre->setAttrib('class', 'form-control default-date-picker');
  
    $monto_total= new Zend_Form_Element_Text('monto_total');
    $monto_total->removeDecorator('Label')->removeDecorator("HtmlTag");
    $monto_total->setAttrib("maxlength", "100");
    $monto_total->setAttrib('class', 'form-control');

    $moneda= new Zend_Form_Element_Text('moneda');
    $moneda->removeDecorator('Label')->removeDecorator("HtmlTag");
    $moneda->setAttrib("maxlength", "100");
    $moneda->setAttrib('class', 'form-control');


    $acs= new Zend_Form_Element_Text('acs');
    $acs->removeDecorator('Label')->removeDecorator("HtmlTag");
    $acs->setAttrib("maxlength", "100");
    $acs->setAttrib('class', 'form-control');

    $sistema= new Zend_Form_Element_Text('sistema');
    $sistema->removeDecorator('Label')->removeDecorator("HtmlTag");
    $sistema->setAttrib("maxlength", "100");
    $sistema->setAttrib('class', 'form-control');

    $unidad_red= new Zend_Form_Element_Text('unidad_red');
    $unidad_red->removeDecorator('Label')->removeDecorator("HtmlTag");
    $unidad_red->setAttrib("maxlength", "100");
    $unidad_red->setAttrib('class', 'form-control');

    $ruta = new Zend_Form_Element_Text('ruta ');
    $ruta ->removeDecorator('Label')->removeDecorator("HtmlTag");
    $ruta ->setAttrib("maxlength", "100");
    $ruta ->setAttrib('class', 'form-control');

    $color_estilo = new Zend_Form_Element_Text('color_estilo ');
    $color_estilo ->removeDecorator('Label')->removeDecorator("HtmlTag");
    $color_estilo ->setAttrib("maxlength", "100");
    $color_estilo ->setAttrib('class', 'form-control');

    $this->addElements(array($proyectoid,$codigo_prop_proy,$nombre_proyecto,$revision,$fecha_inicio,$propuestaid,$clienteid,$unidad_minera,$gerente_proyecto,$control_proyecto,$control_documentario,$descripcion,$observacion,$tag,$estado,$submit,$ubicacion,$tipo_proyecto,$paisid,$departamentoid,$provinciaid,$distritoid,$prioridad,$progreso,$fecha_probable_cierre,$fecha_cierre,$monto_total,$moneda,$acs,$sistema,$unidad_red,$ruta,$color_estilo,$oid)); 
    }
}