<?php

namespace App\Models;

use CodeIgniter\Model;

class People extends Model {
	
const houseperson = "[house]";

protected $table = 'tblPerson';
protected $returnType = \App\Entities\Person::class;
protected $primaryKey = 'PersonID';
protected $allowedFields = ['HouseID', 'Name1', 'Name2'];

function inhouse($id) {
	$ckey = "people-inhouse-{$id}";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$retval = $this->where('HouseID', $id)->findAll();
		$sortby = [];
		foreach($retval as $person) {
		$sortby[] = $person->Name1==static::houseperson ? 
			['',''] : 
			[$person->Name1, $person->Name2] ;
		}
		array_multisort($sortby, SORT_ASC, $retval);
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;	
}

}
