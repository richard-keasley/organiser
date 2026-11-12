<?php 

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class House extends Entity {

protected $casts = [
	'HouseID' => 'int',
];

function getPeople() {
	return model('People')->inhouse($this->HouseID);
}

function getPeopleopts() {
	$retval = []; $sortby = [];
	foreach($this->people as $person) {
		$retval[$person->PersonID] = $person->name;
	}
	return $retval;
}

function getContacts() {
	return model('Contacts')->inhouse($this->HouseID);
}

function getEvents() {
	return model('Events')->inhouse($this->HouseID);
}

function getGroups() {
	return model('Groups')->inhouse($this->HouseID);
}

}
