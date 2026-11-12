<?php

namespace App\Models;

use CodeIgniter\Model;

class Events extends Model {

protected $table = 'tblEvent';
protected $returnType = \App\Entities\Event::class;
protected $primaryKey = 'EventID';
protected $allowedFields = ['PersonID', 'EvTypeID', 'EvDes', 'EvDate0', 'EvDate', 'RemDate'];

function inhouse($id) {
	$ckey = "events-inhouse-{$id}";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$sql = "SELECT tblEvent.* FROM tblEvent 
		INNER JOIN tblPerson ON tblEvent.PersonID = tblPerson.PersonID
		WHERE tblPerson.HouseID={$id}
		ORDER BY tblEvent.RemDate;";
		$query = $this->db->query($sql);
		$retval = $query->getResult($this->returnType);
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;	
}

function inperson($id) {
	$ckey = "events-inperson-{$id}";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$retval = $this->orderBy('RemDate')->where('PersonID', $id)->findAll();
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;	
}

}
