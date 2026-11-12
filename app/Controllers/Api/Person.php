<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Person extends Home {
	
use ResponseTrait;
	
public function getIndex() {
	return $this->respondNoContent('ok');	
}

private function filterpost() {
	$postvals = [];
	foreach($this->request->getPost() as $key=>$val) {
		$postvals[$key] = trim($val);
	}
	return new \App\Entities\Person($postvals);
}

public function getView($id=0) {
	try {
		if(!$id) $id = $this->request->getPost('HouseID');
		$house = model('Houses')->find((int) $id);
		if(!$house) return $this->failNotFound();
		$response = view('house/edit-people', ['house'=>$house]);
		return $this->respond($response);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postDelete() {
	try {		
		$model = model('People');
		$person = $this->filterpost();
		$id = $person->PersonID ?? 0;
		$existing = $model->find($id); 
		if(!$existing) return $this->failNotFound();
		$model->delete($id);
		return $this->respond('ok');
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postSave() {
	try {		
		$model = model('People');
		$person = $this->filterpost();
		$id = $person->PersonID ?? 0;
		$existing = $model->find($id); 
		if(!$existing) return $this->failNotFound();
		$model->update($id, $person);
		return $this->respond($model->find($id)->toarray());
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postInsert() {
	try {
		$model = model('People');
		$person = $this->filterpost();
		$model->insert($person);
		return $this->getView();
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

}
