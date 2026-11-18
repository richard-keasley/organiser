<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Contacts extends Home {
	
use ResponseTrait;
	
public function getIndex() {
	return $this->respondNoContent('ok');	
}

private function filterpost() {
	$postvals = [];
	foreach($this->request->getPost() as $key=>$val) {
		$postvals[$key] = trim($val);
	}
	return new \App\Entities\Contact($postvals);
}

public function getView($id=0) {
	try {
		if(!$id) $id = $this->request->getPost('HouseID');
		$house = model('Houses')->find((int) $id);
		if(!$house) return $this->failNotFound("house {$id} not found");
		$response = view('house/edit-contacts', ['house'=>$house]);
		return $this->respond($response);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postDelete() {
	try {		
		$model = model('Contacts');
		$contact = $this->filterpost();
		$id = $contact->ConID ?? 0;
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
		$model = model('Contacts');
		$contact = $this->filterpost();
		$id = $contact->ConID ?? 0;
		$existing = $model->find($id); 
		if(!$existing) return $this->failNotFound();
		$model->update($id, $contact);
		return $this->respond($model->find($id)->toarray());
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postInsert() {
	try {
		$model = model('Contacts');
		$contact = $this->filterpost();
		$model->insert($contact);
		return $this->getView();
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

}
