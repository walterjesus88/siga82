<?php
class Default_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setName("frmLoginP");
        $usuario  = new Zend_Form_Element_Text('usuario');
        $usuario->setRequired(true)->addErrorMessage('Este campo es requerido');
        $usuario->setAttrib("title","Ingrese codigo de usuario");
        $usuario->removeDecorator('Label');
        $usuario->removeDecorator('HtmlTag');
        $usuario->setAttrib('placeholder','Usuario');


        $clave = new Zend_Form_Element_Password("clave");
        $clave->setRequired(true)->addErrorMessage('Este campo es requerido');;
        $clave->setAttrib("title","Ingrese su contraseña");
        $clave->removeDecorator('Label');
        $clave->removeDecorator('HtmlTag');
        $clave->setAttrib('placeholder','Password');
		$clave->addFilters(array('StringTrim', 'StripTags'));
        
        $submit = new Zend_Form_Element_Submit('enviar');
        $submit->setAttrib('class', 'button radius expand success')->setLabel("Iniciar");
		$submit->setAttrib('id', 'enviarf');
        $submit->removeDecorator('Label');
        $submit->removeDecorator('HtmlTag');
         $submit->removeDecorator('DtDdWrapper');

        $this->addElements(array($usuario,$clave,$submit));        
    }
}



