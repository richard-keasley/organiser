<?php

namespace App\Models;

use CodeIgniter\Model;

class Houses extends Model {

protected $table = 'tblHouse';
protected $returnType = \App\Entities\House::class;
protected $primaryKey = 'HouseID';
protected $allowedFields = ['HouseTitle', 'defName2'];

function setgroup($HouseID, $GroupID, $value) {
	$HouseID = (int) $HouseID;
	$GroupID = (int) $GroupID;
	$sql = $value ? 
		"INSERT IGNORE INTO tbxHouseGroup (`GroupID`, `HouseID`) VALUES ({$GroupID},{$HouseID})" : 
		"DELETE FROM tbxHouseGroup WHERE GroupID={$GroupID} AND HouseID={$HouseID}" ; 
	return $this->db->query($sql);
}

function ingroup($id) {
	$ckey = "houses-ingroup-{$id}";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$sql = "SELECT tblHouse.*
		FROM tblHouse
		INNER JOIN tbxHouseGroup ON tblHouse.HouseID = tbxHouseGroup.HouseID
		WHERE tbxHouseGroup.GroupID = {$id}
		ORDER BY tblHouse.HouseTitle;";
		$query = $this->db->query($sql);
		$retval = $query->getResult($this->returnType);
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;
}

}
