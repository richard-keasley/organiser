<?php

namespace App\Models;

use CodeIgniter\Model;

class Contypes extends Model {
	
protected $table = 'tblConType';
protected $primaryKey = 'ConTypeID';
protected $allowedFields = ['ConTypeDes'];

protected array $casts = [
	'ConTypeID' => 'int',
];

public function findAll(?int $limit=null, int $offset=0) {
	$ckey = "contypes";
	$retval = \App\Libraries\cache::get($ckey);
	if($retval===null) {
		$fldnames = $this->allowedFields;
		$fldnames[] = $this->primaryKey;
		$fldnames = sprintf('`%s`', implode('`, `', $fldnames));
		$sql = "SELECT {$fldnames} FROM tblConType ORDER BY ConTypeDes";
		$query = $this->db->query($sql);
		$retval = [];
		foreach($query->getResultArray() as $row) {
			$row['ConTypeID'] = (int) $row['ConTypeID'];
			$rowdes = strtolower($row['ConTypeDes']);
			
			$row['scheme'] = match($rowdes) {
				'phone', 'mobile' => "tel",
				'address' => 'query',
				'e-mail' => "mailto",
				'web-site' => "https",
				default => ""
			};
			
			$row['format'] = match($rowdes) {
				'address' => 'https://maps.google.co.uk?q=%s',
				default => ''
			};
			
			
			$row['input'] = match($rowdes) {
				'address' => "textarea",
				'phone', 'mobile' => "tel",
				'e-mail' => "email",
				'web-site' => "url",
				default => "text"
			};
			
			$row['icon'] = match($rowdes) {
				'address' => "geo-alt-fill",
				'phone' => "telephone",
				'mobile' => "phone",
				'e-mail' => "envelope-at",
				'web-site' => "globe",
				default => "chat-dots"
			};
			
			$retval[] = $row;
		}
		\App\Libraries\cache::set($ckey, $retval);
	}
	return $retval;
}

public function find($id=0) {
	$id = (int) $id;
	foreach($this->findAll() as $contype) {
		if($contype['ConTypeID']===$id) return $contype;
	}
	return null;
}

function options() {
	$retval = [];
	foreach($this->findall() as $contype) {
		$retval[ $contype['ConTypeID'] ] = $contype['ConTypeDes'];
	}
	return $retval;
}
	
}
