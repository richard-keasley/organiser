<?php

namespace App\Controllers;

class Setup extends BaseController {
	
const user_fields = [
	'username' => "name", 
	'email' => "email",
	'password' => "password",
];

function getIndex() {
	return view('setup/index', $this->data);
}

function getGroups($id=0) {
	$group = model('Groups')->find((int) $id);
	if($group) {
		$this->data['group'] = $group;
		$this->data['heading'] = $group['GrpDes'];
		return view('setup/group', $this->data);
	}
	
	$this->data['title'] = "Groups";
	return view('setup/groups', $this->data);
}

function getEvents() {
	$this->data['title'] = "Events";
	return view('setup/events', $this->data);
}

function getContacts() {
	$this->data['title'] = "Contacts";
	return view('setup/contacts', $this->data);
}

function getAuth() {
	$this->data['user_fields'] = static::user_fields;
	$this->data['title'] = "Security";
	return view('setup/auth', $this->data);
}

}
