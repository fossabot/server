<?php 
/**
 * @package Var
 * @subpackage Partners
 */
class Form_PartnerCreate extends Infra_Form
{	
	public function init()
	{
		parent::init();
		
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->setName('new_account'); // form id
		$this->setAttrib('class', 'inline-form');
		
		$this->addElement('text', 'name', array(
			'label' => 'partner-create form name',
			'required' => true,
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'company', array(
			'label' => 'partner-create form company',
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'admin_email', array(
			'label' => 'partner-create form admin email',
			'required' => true,
			'validators' => array('PartnerEmail', 'EmailAddress'),
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'phone', array(
			'label' => 'partner-create form admin phone',
			'required' => true,
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('text', 'website', array(
			'label' => 'partner-create form url',
			'filters'		=> array('StringTrim'),
		));
		
		$this->addElement('select', 'copyPartner', array(
		    'label' => 'Copy content from partner:',
		    'filters'		=> array('StringTrim'),
			'required' 		=> true,
		    'RegisterInArrayValidator' => false
		));
		
		$this->addDisplayGroup(array('name', 'company', 'admin_email', 'phone', 'describe_yourself', 'website', 'copyPartner'), 'partner_info', array(
			'legend' => 'Publisher Info',
			'decorators' => array(
				'Description', 
				'FormElements', 
				array('Fieldset'),
			)
		));
		
		$this->addElement('button', 'submit', array(
			'label' => 'partner-create form create',
			'type' => 'submit',
			'decorators' => array('ViewHelper')
		));
		
		
		$this->addDisplayGroup(array('submit'), 'buttons1', array(
			'decorators' => array(
				'FormElements', 
				array('HtmlTag', array('tag' => 'div', 'class' => 'buttons')),
			)
		));
	}
	
    public function setProviders(array $providers)
	{
		$element = $this->getElement('copyPartner');
		foreach($providers as $type => $name)
			$element->addMultiOption($type, $name);
	}
}