<?php
$contype = model('Contacts')->types->find($contact->ConTypeID);
if(!$contype) {
	$contype = model('Contacts')->types->findAll()[0];
}

$attrs = $attrs ?? [];
$attrs['value'] = $contact->ConAdd;
$attrs['type'] = $contype['input'];
$attrs['placeholder'] = $contype['ConTypeDes'];

if($attrs['type']=="textarea") {
	unset($attrs['type']);
	$attrs['rows'] = 4;
	echo form_textarea($attrs);
}
else echo form_input($attrs);
