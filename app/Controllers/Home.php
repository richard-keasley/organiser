<?php

namespace App\Controllers;

class Home extends BaseController {
	
public function index() {
	// check post
	$newhouse = $this->request->getPost('newhouse');
	if($newhouse) $newhouse = trim($newhouse);
	if($newhouse) {
		$house = [
			'HouseTitle' => $newhouse,
		];
		$success = model('Houses')->insert($house);
		if($success) {
			$id = model('Houses')->getInsertID();
			return redirect()->to("edit/{$id}");			
		}
	}
	
	$id = (int) $this->request->getPost('delhouse');
	if($id) {
		$success = model('Houses')->delete($id);		
	}
		
	$people = model('People')->findAll();
	$houses = model('Houses')->orderBy('HouseTitle')->findAll();

	$this->data['houses'] = $houses;
	return view('index', $this->data);
}

public function view($id=0) {
	$this->lookup($id);
	return view('house/view', $this->data);
}

public function edit($id=0) {
	$this->lookup($id);
	return view('house/edit', $this->data);
}

private function lookup($id) {
	$id = (int) $id;	
	$this->data['house'] = model('Houses')->find($id);
	if(!$this->data['house']) throw \App\Exceptions\Exception::notfound('House not found');
	$this->data['title'] = $this->data['house']->HouseTitle;
}

}
