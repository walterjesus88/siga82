<?php
class Admin_Form_Persona extends Zend_Form{    

    public function init(){


    $this->setName("frmpersona");  

    $dni= new Zend_Form_Element_Text('dni');
    $dni->removeDecorator('Label')->removeDecorator("HtmlTag");
    $dni->setAttrib("maxlength", "15");
    $dni->setAttrib('class', 'form-control');        
  
    $ape_paterno= new Zend_Form_Element_Text('ape_paterno');
    $ape_paterno->removeDecorator('Label')->removeDecorator("HtmlTag");
    $ape_paterno->setAttrib("maxlength", "100");
    $ape_paterno->setAttrib('class', 'form-control');

    $ape_materno= new Zend_Form_Element_Text('ape_materno');
    $ape_materno->removeDecorator('Label')->removeDecorator("HtmlTag");
    $ape_materno->setAttrib("maxlength", "100");
    $ape_materno->setAttrib('class', 'form-control');

    $nombres= new Zend_Form_Element_Text('nombres');
    $nombres->removeDecorator('Label')->removeDecorator("HtmlTag");
    $nombres->setAttrib("maxlength", "100");
    $nombres->setAttrib('class', 'form-control');

    $alias= new Zend_Form_Element_Text('alias');
    $alias->removeDecorator('Label')->removeDecorator("HtmlTag");
    $alias->setAttrib("maxlength", "100");
    $alias->setAttrib('class', 'form-control');

    $sexo = new Zend_Form_Element_Select('sexo');
    $sexo->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el sexo');
    $sexo->setLabel("Ingrese el Tipo de Documento: ");
    $sexo->removeDecorator('Label');
    $sexo->setAttrib('class','form-control');
    $sexo->addMultiOption('M',"Masculino");
    $sexo->addMultiOption('F',"Femenino");

    $estado_civil = new Zend_Form_Element_Select('estado_civil');
    $estado_civil->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el estado civil');
    $estado_civil->setLabel("Ingrese el Estado Civil: ");
    $estado_civil->removeDecorator('Label');
    $estado_civil->setAttrib('class','form-control');
    $estado_civil->addMultiOption('C',"Casado");
    $estado_civil->addMultiOption('S',"Soltero");
    $estado_civil->addMultiOption('V',"Viudo");
    $estado_civil->addMultiOption('D',"Divorciado");

    $email_personal= new Zend_Form_Element_Text('email_personal');
    $email_personal->removeDecorator('Label')->removeDecorator("HtmlTag");
    $email_personal->setAttrib("maxlength", "100");
    $email_personal->setAttrib('class', 'form-control');

    $email_anddes= new Zend_Form_Element_Text('email_anddes');
    $email_anddes->removeDecorator('Label')->removeDecorator("HtmlTag");
    $email_anddes->setAttrib("maxlength", "50");
    $email_anddes->setAttrib('class', 'form-control');

    $celular= new Zend_Form_Element_Text('celular');
    $celular->removeDecorator('Label')->removeDecorator("HtmlTag");
    $celular->setAttrib("maxlength", "15");
    $celular->setAttrib('class', 'form-control');

    $telefono= new Zend_Form_Element_Text('telefono');
    $telefono->removeDecorator('Label')->removeDecorator("HtmlTag");
    $telefono->setAttrib("maxlength", "15");
    $telefono->setAttrib('class', 'form-control');

    $anexo= new Zend_Form_Element_Text('anexo');
    $anexo->removeDecorator('Label')->removeDecorator("HtmlTag");
    $anexo->setAttrib("maxlength", "15");
    $anexo->setAttrib('class', 'form-control');

    $direccion= new Zend_Form_Element_Text('direccion');
    $direccion->removeDecorator('Label')->removeDecorator("HtmlTag");
    $direccion->setAttrib("maxlength", "15");
    $direccion->setAttrib('class', 'form-control');

    $fecha_registro= new Zend_Form_Element_Text('fecha_registro');
    $fecha_registro->removeDecorator('Label')->removeDecorator("HtmlTag");
    $fecha_registro->setAttrib("maxlength", "50");
    $fecha_registro->setAttrib('class', 'form-control');

    $fecha_modificacion= new Zend_Form_Element_Text('fecha_modificacion');
    $fecha_modificacion->removeDecorator('Label')->removeDecorator("HtmlTag");
    $fecha_modificacion->setAttrib("maxlength", "50");
    $fecha_modificacion->setAttrib('class', 'form-control');

    $fecha_nacimiento= new Zend_Form_Element_Text('fecha_nacimiento');
    $fecha_nacimiento->removeDecorator('Label')->removeDecorator("HtmlTag");
    $fecha_nacimiento->setAttrib("maxlength", "50");
    $fecha_nacimiento->setAttrib('class', 'form-control');

    $uid_registro= new Zend_Form_Element_Text('uid_registro');
    $uid_registro->removeDecorator('Label')->removeDecorator("HtmlTag");
    $uid_registro->setAttrib("maxlength", "50");
    $uid_registro->setAttrib('class', 'form-control');

    $uid_modificacion= new Zend_Form_Element_Text('uid_modificacion');
    $uid_modificacion->removeDecorator('Label')->removeDecorator("HtmlTag");
    $uid_modificacion->setAttrib("maxlength", "50");
    $uid_modificacion->setAttrib('class', 'form-control');

    $estado = new Zend_Form_Element_Select('estado');
    $estado->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el estado');
    $estado->setLabel("Ingrese el Estado ");
    $estado->removeDecorator('Label');
    $estado->setAttrib('class','form-control');
    $estado->addMultiOption('A',"Activo");
    $estado->addMultiOption('I',"Inactivo");
    $estado->addMultiOption('E',"Eliminado");

    $color_estilo= new Zend_Form_Element_Text('color_estilo');
    $color_estilo->removeDecorator('Label')->removeDecorator("HtmlTag");
    $color_estilo->setAttrib("maxlength", "200");
    $color_estilo->setAttrib('class', 'form-control');

    $tag= new Zend_Form_Element_Text('tag');
    $tag->removeDecorator('Label')->removeDecorator("HtmlTag");
    $tag->setAttrib("maxlength", "200");
    $tag->setAttrib('class', 'form-control');

    $condicion = new Zend_Form_Element_Select('condicion');
    $condicion->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el estado');
    $condicion->setLabel("Ingrese el Estado ");
    $condicion->removeDecorator('Label');
    $condicion->setAttrib('class','form-control');
    $condicion->addMultiOption('C',"Contratado");
    $condicion->addMultiOption('P',"Permanente");
    $condicion->addMultiOption('I',"Indefinido");

    $iscontacto = new Zend_Form_Element_Select('iscontacto');
    $iscontacto->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el estado');
    $iscontacto->setLabel("Ingrese el Estado ");
    $iscontacto->removeDecorator('Label');
    $iscontacto->setAttrib('class','form-control');
    $iscontacto->addMultiOption('S',"Si");
    $iscontacto->addMultiOption('N',"No");

    $isanddes = new Zend_Form_Element_Select('isanddes');
    $isanddes->removeDecorator('HtmlTag')->setRequired(true)->addErrorMessage('Es necesario que ingrese el estado');
    $isanddes->setLabel("Ingrese el Estado ");
    $isanddes->removeDecorator('Label');
    $isanddes->setAttrib('class','form-control');
    $isanddes->addMultiOption('S',"Si");
    $isanddes->addMultiOption('N',"No");


    $descripcion = new Zend_Form_Element_Textarea('descripcion');
    $descripcion->removeDecorator('Label')
                ->setRequired(true)
                ->setAttrib('class', 'form-control')
                ->setAttrib('rows', '10')
                ->setAttrib('style', 'resize : none;')
                ->setAttrib('title', 'Descripción');

    $ocupacion= new Zend_Form_Element_Text('ocupacion');
    $ocupacion->removeDecorator('Label')->removeDecorator("HtmlTag");
    $ocupacion->setAttrib("maxlength", "200");
    $ocupacion->setAttrib('class', 'form-control');
  
    $submit = new Zend_Form_Element_Submit('guardar');
    $submit->removeDecorator('HtmlTag'); 
    $submit->setAttrib('class','form-control');
    $submit->removeDecorator('Label')->removeDecorator('DtDdWrapper');
    $submit->removeDecorator('Label')->removeDecorator("HtmlTag");
  

    $this->addElements(array($dni,$ape_paterno,$ape_materno,$nombres,$alias,$sexo,$estado_civil,$email_personal,$email_anddes,$celular,$telefono,$anexo,$direccion,$fecha_registro,$uid_registro,$fecha_modificacion,$fecha_nacimiento,$uid_modificacion,$estado,$color_estilo,$tag,$condicion,$iscontacto,$isanddes,$descripcion,$ocupacion));

    }
}