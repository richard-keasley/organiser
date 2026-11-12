<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class House extends Home {
	
use ResponseTrait;

private function filterpost() {
	$allowed = ['HouseTitle', 'defName2'];
	$postvals = [];
	
	foreach($allowed as $key) {
		$val = $this->request->getPost($key);
		if($val!==null) {
			$postvals[$key] = trim($val);
		}
	}
	return new \App\Entities\House($postvals);
}
	
public function postUpdate($id=0) {
	try {
		$id = (int) $id;	
		$model = model('Houses');
		$existing = $model->find($id);
		if(!$existing) return $this->failNotFound();
		$house = $this->filterpost();
		$model->update($id, $house);
		return $this->respond($house->toarray());
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postGroup($id=0) {
	try {
		$id = (int) $id;	
		$model = model('Houses');
		$house = $model->find($id);
		if(!$house) return $this->failNotFound();
		
		$grpid = (int) $this->request->getpost('group');
		$value = (int) $this->request->getpost('value');
		$res = model('Houses')->setgroup($id, $grpid, $value);
		if(!$res) return $this->fail('DB error');
		$response = view('house/edit-groups', ['house' => $house]);
		return $this->respond($response);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

}
