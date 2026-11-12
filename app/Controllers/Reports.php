<?php

namespace App\Controllers;

class Reports extends BaseController {

function getIndex() {
	return view('reports/index', $this->data);
}

function getView($report='', $groups='') {
	$ids = explode('-', $groups);
	$this->data['groups'] = model('Groups')->find($ids);
		
	$dir = config('Paths')->viewDirectory;
	$viewname = "reports/{$report}";
	$filepath = realpath("{$dir}/{$viewname}.php");
	if(!$filepath) $viewname = 'reports/index';
	
	return view($viewname, $this->data);
}

}
