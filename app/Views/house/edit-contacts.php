<?php

$type_opts = model('Contacts')->types->options();
$contypes = model('Contacts')->types->findall();

$people_opts = $house->peopleopts;
$sel_attrs = [
	'class' => "form-control",
	'onChange' => "houseedit.contacts.description(this)",
	'data-fldname' => "des_select",
	'style' => "width:7em",
];

$des_attrs = [
	'style' => "width:15em",
	'class' => "form-control",
	'onKeyup' => "houseedit.tr.dirty(this)",
];

$addr_attrs = [
	'style' => "width:18em",
	'class' => "form-control",
	'onKeyup' => "houseedit.tr.dirty(this)",
	'name' => "ConAdd",
];

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "save",
	'onClick' => "houseedit.contacts.update(this)",
	'title' => "Save this contact",
];
$label = '<i class="bi bi-check"></i>';
$btn_save = trim(form_button($attrs, $label));

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-danger",
	'data-cmd' => "delete",
	'onClick' => "houseedit.contacts.update(this)",
	'title' => "Delete this contact",
];
$label = '<i class="bi bi-trash"></i>';
$btn_delete = trim(form_button($attrs, $label));

$tbody = [];
foreach($house->contacts as $contact) {
	
	$addr_data = [
		'contact' => $contact,
		'attrs' => $addr_attrs,
	];
		
	$tr = [
		'person' => new \App\Cells\input\select("PersonID", $contact->PersonID, $people_opts, $sel_attrs),
		'type' => new \App\Cells\input\select("ConTypeID", $contact->ConTypeID, $type_opts, $sel_attrs),
		'description' => new \App\Cells\input("ConDes", $contact->ConDes, $des_attrs),
		
		'address' => view('contact/edit', $addr_data),
		'_' => [$btn_save, $btn_delete],
	];
	
	$tr['_']= implode('&nbsp;', $tr['_']);
	$attrs = ['type'=>"hidden"];
	$tr['_'] .= new \App\Cells\input('ConID', $contact->ConID, $attrs);
	
	$tbody[] = $tr;
}

// new entry
$contact = [
	'ConTypeID' => $contypes[0]['ConTypeID'],
];
$contact = new \App\Entities\Contact($contact);
$addr_data['contact'] = $contact;
$tr = [
	'person' => new \App\Cells\input\select("PersonID", '', $people_opts, $sel_attrs),
	'type' => new \App\Cells\input\select("ConTypeID", '', $type_opts, $sel_attrs),
	'description' => new \App\Cells\input("ConDes", '', $des_attrs),
	'address' => view('contact/edit', $addr_data),
	'_' => '',
];
$btn_attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "insert",
	'onClick' => "houseedit.contacts.update(this)",
	'title' => "Add new event",
];
$btn_label = '<i class="bi bi-plus"></i>';
$tr['_'] .= trim(form_button($btn_attrs, $btn_label));
$tbody[] = $tr;

$thead = array_keys($tr);
$last = array_key_last($thead);
$thead[$last] = '';
$table = new \App\Cells\Table('responsive');
$table->setHeading($thead);
echo $table->generate($tbody);

// templates for address edit
$addr_data['contact'] = new \App\Entities\Contact;
$template = '<template id="tmpl-contype-%s">%s</template>';
foreach($contypes as $contype) {
	$addr_data['contact']->ConTypeID = $contype['ConTypeID'];
	printf($template, $contype['ConTypeID'], view('contact/edit', $addr_data));
}
