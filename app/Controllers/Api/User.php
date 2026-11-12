<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class User extends Home {
	
use ResponseTrait;

private function init() {
	$this->data['users'] = auth()->getProvider();
	$this->data['user_fields'] = \App\Controllers\Setup::user_fields;
		
	$id = (int) $this->request->getPost('id');
	return $this->data['users']->findById($id);
}


public function getIndex() {
	return $this->respondNoContent('ok');	
}

public function postDelete() {
	try {
		$user = $this->init();
		if(!$user) return $this->failNotFound();
		
		$this->data['users']->delete($user->id, true);
		return view('setup/auth-users', $this->data);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postSave() {
	try {
		$user = $this->init();
		if(!$user) return $this->failNotFound();
		
		$update = [];
		foreach(array_keys($this->data['user_fields']) as $key) {
			$value = trim($this->request->getPost($key));
			if($value) $update[$key] = $value;
		}
		if($update) {
			$user->fill($update);
			$this->data['users']->save($user);
		}
		
		return view('setup/auth-users', $this->data);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

}
