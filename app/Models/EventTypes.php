<?php

namespace App\Models;

use CodeIgniter\Model;

class EventTypes extends Model {
	
const NONE = 0;
const YEAR = 1;
const MONTH = 2;
const WEEK = 3;

protected $table = 'tblEvType';
protected $primaryKey = 'EvTypeID';
protected $allowedFields = ['EvTypeDes', 'EvTypeFreq', 'EvTypeRemDays', 'EvTypeFontColour'];

protected array $casts = [
	'EvTypeID' => 'int',
	'EvTypeFreq' => 'int',
	'EvTypeRemDays' => 'int',
	'EvTypeFontColour' => 'int',
];

function options() {
	$ckey = "eventtypes-options";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$retval = [];
		foreach($this->findAll() as $item) {
			$retval[ $item['EvTypeID'] ] = $item['EvTypeDes'];
		}
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;
}

}
