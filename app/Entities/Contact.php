<?php 

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Contact extends Entity {
	
protected $casts = [
	'ConID' => 'int',
	'PersonID' => 'int',
	'ConTypeID' => 'int',
];

function getPerson() {
	return \App\Libraries\cache::entity('People', $this->PersonID);
}

function getIcon() {
	$icon = $this->type['icon'] ?? 'question';
	return sprintf('<i class="bi bi-%s"></i>', $icon);
}

function getLink() {
	$href = $this->ConAdd;
	$strip = parse_url($this->type['ConTypeCom'], PHP_URL_SCHEME) ? true : false ;
	if($strip) {
		$href = preg_replace('/[^A-Z0-9,\.\-_%]+/i', ' ', $href);
	}
	$href = str_replace('%1', $href, $this->type['ConTypeCom']);
	$address = nl2br($this->ConAdd);
	return sprintf('<a href="%s">%s</a>', $href, $address);
}

function getType() {
	return model('Contacts')->types->find($this->ConTypeID);
}
	
}
