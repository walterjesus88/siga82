<?php
class Admin_Form_Proyecto extends Zend_Form{    

    public function init(){


    $this->setName("frmproyecto");
        
    $codigo_prop_proy= new Zend_Form_Element_Text('codigo_prop_proy');
    $codigo_prop_proy->setRequired(true)->addErrorMessage('Este campo es requerido');
    $codigo_prop_proy->removeDecorator('Label')->removeDecorator("HtmlTag");
    $codigo_prop_proy->setAttrib("maxlength", "9");
  
    $nombre_proyecto= new Zend_Form_Element_Text('nombre_proyecto');
    $nombre_proyecto->setRequired(true)->addErrorMessage('Este campo es requerido');
    $nombre_proyecto->removeDecorator('Label')->removeDecorator("HtmlTag");
    $nombre_proyecto->setAttrib("maxlength", "9");

        

    $propuestaid= new Zend_Form_Element_Select('proyectoid');
    $propuestaid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $bdpropuesta = new Admin_Model_DbTable_Proyecto(); 
    $listpropuesta=$bdpropuesta->_getProyectoAll();
    foreach ($listpropuesta as $propuesta ){
        $propuestaid->addMultiOption($propuesta['proyectoid']);
    }

    $clienteid= new Zend_Form_Element_Select('clienteid');
    $clienteid->removeDecorator('Label')->removeDecorator("HtmlTag");
    $bdcliente = new Admin_Model_DbTable_Cliente(); 
    $listcliente=$bdcliente->_getClienteAll();
    foreach ($listcliente as $cliente ){
        $clienteid->addMultiOption($cliente['nombre']);
    }

    $unidad_minera= new Zend_Form_Element_Select('unidad_minera');
    $unidad_minera->removeDecorator('Label')->removeDecorator("HtmlTag");
    $bdunidad_minera = new Admin_Model_DbTable_Unidadminera();
    $listunidad_minera=$bdunidad_minera->_getUnidadmineraAll();
    foreach ($listunidad_minera as $uminera ){
        $unidad_minera->addMultiOption($uminera['nombre']);
    }

    $gerente_proyecto= new Zend_Form_Element_Text('gerente_proyecto');
    $gerente_proyecto->removeDecorator('Label')->removeDecorator("HtmlTag");
    $gerente_proyecto->setAttrib("maxlength", "100");

    $submit = new Zend_Form_Element_Submit('guardar');
    $submit->removeDecorator('HtmlTag'); 
    $submit->removeDecorator('Label')->removeDecorator('DtDdWrapper');
    $submit->removeDecorator('Label')->removeDecorator("HtmlTag");
    $submit->setAttrib("class","info");

    $this->addElements(array($codigo_prop_proy,$nombre_proyecto,$propuestaid,$clienteid,$unidad_minera,$gerente_proyecto)); 


    }
}