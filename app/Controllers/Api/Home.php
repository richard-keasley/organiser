<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;

class Home extends \App\Controllers\BaseController {
	
use ResponseTrait;
	
public function getIndex() {
	return $this->respondNoContent('ok');	
}

}
