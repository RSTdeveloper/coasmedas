<?php

namespace App\Controllers;

use App\Models\ModelMaster;
use App\Libraries\Nativesession;
use App\Models\ModelAutenticacion;


use App\Controllers\BaseController;

class Master extends BaseController
{

	function __construct()
	{
		$this->modelMaster = new ModelMaster();
		$this->modelAutenticacion = new ModelAutenticacion();
		$this->nativesession = new Nativesession();
	}

	public function index()
	{
		//return view('Master');

		if ($this->nativesession->get('logged_in')) {
			return view('Master');
		} else {
			return redirect()->to(base_url('/'));
		}
	}
}
