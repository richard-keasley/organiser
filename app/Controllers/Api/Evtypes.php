<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Evtypes extends Home {
	
use ResponseTrait;
	
public function getIndex() {
	return $this->respondNoContent();	
}

private $model = 'EventTypes';
private $pkey = 'EvTypeID';

private function filterpost() {
	$types = [
		'EvTypeID' => 'int',
		'EvTypeFontColour' => 'hexcol',
	];
	$retval = [];
	foreach($this->request->getPost() as $key=>$val) {
		$type = $types[$key] ?? 'string';
		$retval[$key] = match($type) {
			'int' => (int) $val,
			'hexcol' => colour_hexdec($val),
			default => trim ($val)
		};
	}
	return $retval;
}

public function postDelete() {
	try {
		$model = model($this->model);
		$evtype = $this->filterpost();
		$id = $evtype[$this->pkey] ?? 0;
		$existing = $model->find($id); 
		if(!$existing) return $this->failNotFound();
		$model->delete($id);
		return $this->respond('ok');
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postUpdate() {
	try {
		$model = model($this->model);
		$evtype = $this->filterpost();
		$id = $evtype[$this->pkey] ?? 0;
		unset($evtype[$this->pkey]);
		$existing = $model->find($id); 
		if(!$existing) return $this->failNotFound();
		$model->update($id, $evtype);
		return $this->respond($evtype);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postInsert() {
	try {
		$model = model($this->model);
		$evtype = $this->filterpost();
		if(!empty($evtype['EvTypeDes'])) $model->insert($evtype);
		return $this->respond('ok');
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

}
