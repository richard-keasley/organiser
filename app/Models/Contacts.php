<?php

namespace App\Models;

use CodeIgniter\Model;

class Contacts extends Model {

protected $table = 'tblContact';
protected $returnType = \App\Entities\Contact::class;
protected $primaryKey = 'ConID';
protected $allowedFields = ['PersonID', 'ConTypeID', 'ConDes', 'ConAdd'];

public $types = null;

function __construct() {
	$this->types = new Contypes;
	parent::__construct();
}

function inhouse($id) {
	$ckey = "contacts-inhouse";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$sql = "SELECT tblContact.* FROM tblContact 
		INNER JOIN tblPerson ON tblContact.PersonID = tblPerson.PersonID
		WHERE tblPerson.HouseID={$id}
		ORDER BY tblContact.ConDes;";
		$query = $this->db->query($sql);
		$retval = $query->getResult($this->returnType);
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;
}

function inperson($id) {
	$ckey = "contacts-inperson{$id}";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$retval = $this->where('PersonID', $id)->findAll();
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;
}

}
