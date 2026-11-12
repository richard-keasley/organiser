<?php 

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Event extends Entity {
	
protected $casts = [
	'EventID' => 'int',
	'PersonID' => 'int',
	'EvTypeID' => 'int',
	'EvDate0' => '?datetime',
	'EvDate' => '?datetime',
	'RemDate' => '?datetime',
];

function getHouse() {
	return \App\Libraries\cache::entity('Houses', $this->person->HouseID);
}

function getPerson() {
	return \App\Libraries\cache::entity('People', $this->PersonID);
}

function getType() {
	return \App\Libraries\cache::entity('EventTypes', $this->EvTypeID);
}

function getColour() {
	return colour_dechex($this->type['EvTypeFontColour']);
}

function getInterval() {
	$str = match($this->type['EvTypeFreq']) {
		\App\Models\EventTypes::YEAR => 'P1Y',
		\App\Models\EventTypes::MONTH => 'P1M',
		\App\Models\EventTypes::WEEK => 'P7D',
		default => null
	};
	return $str ? new \DateInterval($str) : null ;
}

function getDue() {
	return ($this->RemDate) ?
		$this->RemDate <= new \CodeIgniter\I18n\Time : 
		false ;
}

function getNextdate() {
	if(!$this->interval) return null;
	
	$now = new \CodeIgniter\I18n\Time;
	$retval = $this->EvDate0;
	if(!$retval) return $now;
	
	$minimum = $now;
	if($this->EvDate && $this->EvDate > $now) {
		$minimum = $this->EvDate;
	}
	
	while($retval <= $minimum) {
		$retval = $retval->add($this->interval);
	}
	
	return $retval;
}

function set_evdate() {
	$val = $this->nextdate;
	$this->attributes['EvDate'] = $val;
	return $val;
}

function set_remdate() {
	if($this->interval && $this->EvDate) {
		$days = $this->type['EvTypeRemDays'];
		$interval = new \Dateinterval("P{$days}D");
		$this->attributes['RemDate'] = $this->EvDate->sub($interval);
	}
	else $this->attributes['RemDate'] = null;
	return $this->attributes['RemDate'];
}

function getRembtn($response='ok') {
	$attrs = [
		'type' => "button",
		'onClick' => "reminders.update(this)",
		'class' => "btn btn-sm btn-info",
		'title' => "Next reminder for this event",
		'data-eventid' => $this->EventID,
		'data-response' => $response,
	];
	$label = '<i class="bi bi-skip-end"></i>';
	return trim(form_button($attrs, $label));
}
	
}
