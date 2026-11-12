<?php

namespace App\Models;

use CodeIgniter\Model;

class Groups extends Model {

protected $table = 'tblGroup';
protected $primaryKey = 'GroupID';
protected $allowedFields = ['GrpDes'];

function inhouse($id) {
	$ckey = "groups-inhouse-{$id}";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$sql = "SELECT tblGroup.*
		FROM tblGroup
		INNER JOIN tbxHouseGroup ON tblGroup.GroupID = tbxHouseGroup.GroupID
		WHERE tbxHouseGroup.HouseID = {$id}
		ORDER BY tblGroup.GrpDes;";
		$query = $this->db->query($sql);
		$retval = $query->getResult($this->returnType);
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;
}

}
