<?php 

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Person extends Entity {
	
protected $casts = [
	'PersonID' => 'int',
	'HouseID' => 'int',
];

function getHouseperson() : bool {
	return $this->Name1==model('People')::houseperson;
}
	
function getName() : string {
	if($this->houseperson) {
		return "[{$this->house->HouseTitle}]";
	}
		
	$name = [
		$this->Name1,
		$this->Name2,
	];
	$name = implode(' ', $name); 
	return trim($name);	
}

function getHouse() {
	return \App\Libraries\cache::entity('Houses', $this->HouseID);
}

function getContacts() {
	return model('Contacts')->inperson($this->PersonID);
}

function getEvents() {
	return model('Events')->inperson($this->PersonID);
}
	
}
