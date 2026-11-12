<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Groups extends Home {
	
use ResponseTrait;
	
public function getIndex() {
	return $this->respondNoContent();	
}

private $model = 'Groups';
private $pkey = 'GroupID';

private function filterpost() {
	$types = [
		'GroupID' => 'int',
	];
	$retval = [];
	foreach($this->request->getPost() as $key=>$val) {
		$type = $types[$key] ?? 'string';
		$retval[$key] = match($type) {
			'int' => (int) $val,
			default => trim ($val)
		};
	}
	return $retval;
}

public function getView() {
	try {
		return $this->respond('ok');
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postDelete() {
	try {
		$model = model($this->model);
		$group = $this->filterpost();
		$id = $group[$this->pkey] ?? 0;
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
		$group = $this->filterpost();
		$id = $group[$this->pkey] ?? 0;
		unset($group[$this->pkey]);
		$existing = $model->find($id); 
		if(!$existing) return $this->failNotFound();
		$model->update($id, $group);
		return $this->respond($group);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postInsert() {
	try {
		$model = model($this->model);
		$group = $this->filterpost();
		if(!empty($group['GrpDes'])) $model->insert($group);
		return $this->getView();
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

}
