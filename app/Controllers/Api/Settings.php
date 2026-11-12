<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Settings extends Home {
	
use ResponseTrait;
	
public function getIndex() {
	return $this->respondNoContent();	
}

public function postSet() {
	try {
		$name = $this->request->getPost('name');
		$type = $this->request->getPost('type');
		$value = $this->request->getPost('value');
		$value = match($type) {
			'booleanx' => (int) $value,
			default => $value
		};
		service('settings')->set($name, $value);
		return $this->respond($this->request->getPost());
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postDelete() {
	try {
		return $this->failNotFound();
		return $this->respond('ok');
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

}
