<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Event extends Home {
	
use ResponseTrait;
	
public function getIndex() {
	return $this->respondNoContent();	
}

private $dates = ['EvDate0', 'EvDate', 'RemDate', 'nextdate'];

private function filterpost() {
	$postvals = [];
	foreach($this->request->getPost() as $key=>$val) {
		$val = trim($val);
		if(in_array($key, $this->dates)) {
			$val = $val ? new \CodeIgniter\I18n\Time($val) : null ;
		}
		$postvals[$key] = $val;		
	}
	return new \App\Entities\Event($postvals);
}

public function getView($id=0) {
	try {
		if(!$id) $id = $this->request->getPost('HouseID');
		$house = model('Houses')->find((int) $id);
		if(!$house) return $this->failNotFound('here');
		$response = view('house/edit-events', ['house'=>$house]);
		return $this->respond($response);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postDelete() {
	try {
		$model = model('Events');
		$event = $this->filterpost();
		$id = $event->EventID ?? 0;
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
		$model = model('Events');
		$event = $this->filterpost();
		$id = $event->EventID ?? 0;
		$existing = $model->find($id); 
		if(!$existing) return $this->failNotFound();
		
		// if there's EvDate, but no RemDate, calculate RemDate 
		if(!$event->RemDate) $event->set_RemDate();
		$model->update($id, $event);
		
		$response = $event->toarray();
		$response['nextdate'] = $event->nextdate;
		foreach($response as $key=>$val) {
			if(in_array($key, $this->dates)) {
				$response[$key] = $val ? $val->format('Y-m-d') : '';
			}
		}
		
		return $this->respond($response);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postInsert() {
	try {
		$model = model('Events');
		$event = $this->filterpost();
		
		// Calculate next EvDate and RemDate
		$event->set_evdate();
		$event->set_remdate();
		$model->insert($event);
		
		return $this->getView();
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

public function postRemdate() {
	try {
		$id = (int) $this->request->getPost('eventid');
		$response = (string) $this->request->getPost('response');
		
		$model = model('Events');
		$event = $model->find($id);
		if(!$event) return $this->failNotFound("event {$id} not found");
		
		$event->set_evdate();
		$event->set_remdate();	
		$model->update($id, $event);
		
		$response = match($response) {
			'house' =>  view('house/view-events', ['house'=>$event->house]),
			
			'reminders' => view('events/reminders'),
			
			default => 'ok'
		};
		return $this->respond($response);
	}
	catch(\throwable $ex) {
		return $this->fail($ex->getMessage());
	}
}

}
