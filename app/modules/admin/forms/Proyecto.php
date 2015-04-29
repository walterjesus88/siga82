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
    $fecha_inicio->setAttrib('class', 'form-control');


    $propuestaid= new Zend_Form_Element_Select('propuestaid');
    $propuestaid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $propuestaid->setAttrib('class', 'form-control');    
    $bdpropuesta = new Admin_Model_DbTable_Propuesta(); 
    $propuestaid->addMultiOption('0',' -- Seleccione -- ');
    $listpropuesta=$bdpropuesta->_getPropuestaAll();
    foreach ($listpropuesta as $propuesta ){        
        $propuestaid->addMultiOption($propuesta['propuestaid'],$propuesta['nombre_propuesta']);
    }

    $codigo_prop_proy= new Zend_Form_Element_Text('codigo_prop_proy');
    $codigo_prop_proy->setRequired(true)->addErrorMessage('Este campo es requerido');
    $codigo_prop_proy->removeDecorator('Label')->removeDecorator("HtmlTag");
    $codigo_prop_proy->setAttrib("maxlength", "100");
    $codigo_prop_proy->setAttrib('class', 'form-control');
    // $bdcodigo_prop_proy = new Admin_Model_DbTable_Propuesta();   
    // $data['propuestaid']=$propuestaid;        
    // $attr = "";
    // $rows = $bdcodigo_prop_proy->_getFilter($data,$attr);    
    // $codigo_prop_proy->addMultiOption('0',$rows['codigo_prop_proy']);



    $clienteid= new Zend_Form_Element_Select('clienteid');
    $clienteid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $clienteid->setAttrib('class', 'form-control');    
    $bdcliente = new Admin_Model_DbTable_Cliente(); 
    $listcliente=$bdcliente->_getClienteAll();
    foreach ($listcliente as $cliente ){
        $clienteid->addMultiOption($cliente['clienteid'],$cliente['nombre']);
    }

    $unidad_minera= new Zend_Form_Element_Select('unidad_minera');
    $unidad_minera->removeDecorator('Label')->removeDecorator("HtmlTag");
    $unidad_minera->setAttrib('class', 'form-control');
    $bdunidad_minera = new Admin_Model_DbTable_Unidadminera();
    $listunidad_minera=$bdunidad_minera->_getUnidadmineraAll();
    foreach ($listunidad_minera as $uminera ){
        $unidad_minera->addMultiOption($uminera['unidad_mineraid'],$uminera['nombre']);
    }

    $gerente_proyecto= new Zend_Form_Element_Text('gerente_proyecto');
    $gerente_proyecto->removeDecorator('Label')->removeDecorator("HtmlTag");
    $gerente_proyecto->setAttrib("maxlength", "100");
    $gerente_proyecto->setAttrib('class', 'form-control');

    $control_proyecto= new Zend_Form_Element_Text('control_proyecto');
    $control_proyecto->removeDecorator('Label')->removeDecorator("HtmlTag");
    $control_proyecto->setAttrib("maxlength", "100");
    $control_proyecto->setAttrib('class', 'form-control');

    $control_documentario= new Zend_Form_Element_Text('control_documentario');
    $control_documentario->removeDecorator('Label')->removeDecorator("HtmlTag");
    $control_documentario->setAttrib("maxlength", "100");
    $control_documentario->setAttrib('class', 'form-control');

    $descripcion = new Zend_Form_Element_Text('descripcion');
    $descripcion->setRequired(true)->addErrorMessage('Este campo es requerido');
    $descripcion->removeDecorator('Label')->removeDecorator("HtmlTag");
    $descripcion->setAttrib("class", "input-small");
    $descripcion->setAttrib("maxlength", "4");
    $descripcion->setAttrib('class', 'form-control');

    $tipo_proyecto= new Zend_Form_Element_Text('tipo_proyecto');
    $tipo_proyecto->removeDecorator('Label')->removeDecorator("HtmlTag");
    $tipo_proyecto->setAttrib("maxlength", "100");
    $tipo_proyecto->setAttrib('class', 'form-control');


    $observacion= new Zend_Form_Element_Text('observacion');
    $observacion->removeDecorator('Label')->removeDecorator("HtmlTag");
    $observacion->setAttrib("maxlength", "100");
    $observacion->setAttrib('class', 'form-control');

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
    $estado->setAttrib('class', 'form-control');

    $submit = new Zend_Form_Element_Submit('guardar');
    $submit->removeDecorator('HtmlTag'); 
    $submit->setAttrib('class','form-control');
    $submit->removeDecorator('Label')->removeDecorator('DtDdWrapper');
    $submit->removeDecorator('Label')->removeDecorator("HtmlTag");
  
    
    $this->addElements(array($proyectoid,$codigo_prop_proy,$nombre_proyecto,$revision,$fecha_inicio,$propuestaid,$clienteid,$unidad_minera,$gerente_proyecto,$control_proyecto,$control_documentario,$descripcion,$tipo_proyecto,$observacion,$tag,$estado,$submit)); 


    }
}