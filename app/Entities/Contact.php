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
	try {
		$href = normalise_url($this->ConAdd, $this->type['scheme']);
		if($this->type['format']) $href = sprintf($this->type['format'], $href);
		$attrs = parse_url($href, PHP_URL_SCHEME) ? 
			['href' => $href] : 
			['title' => "no link available"] ;
	}
	catch(\throwable $ex) {
		$attrs = ['title' => $ex->getMessage(), 'class'=>"bg-danger-subtle"];
	}
	
	$label = nl2br($this->ConAdd);
	return sprintf('<a %s>%s</a>', stringify_attributes($attrs), $label);
}

function getType() {
	return model('Contacts')->types->find($this->ConTypeID);
}
	
}
