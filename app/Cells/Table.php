<?php

namespace App\Cells;

class Table extends \CodeIgniter\View\Table {
	
function __construct($config="table") {
	if(is_string($config)) {
		// define template for table 
		$config = match($config) {
			'responsive' => [
				'table_open' => '<div class="table-responsive"><table class="table">',
				'table_close' => '</table></div>',
			],
			
			'inputs' => [
				'table_open' => '<div class="table-responsive"><table class="table table-borderless">',
				'table_close' => '</table></div>',
			],
			
			'striped' => [
				'table_open' => '<div class="table-responsive"><table class="table table-sm table-striped">',
				'table_close' => '</table></div>',
			],
			
			'list' => [
				'table_open' => '<table class="table table-sm table-borderless w-auto">',
			],
			
			default => [
				'table_open' => sprintf('<table class="%s">', $config),
			]
		};
	}
	// ensure it's an array before sending
	if(!is_array($config)) $config=[];
	parent::__construct($config);
}

function csv($tableData=null) {
	// copied from CodeIgniter\View\Table::generate()
	// The table data can optionally be passed to this function
	// either as a database result object or an array
	if ($tableData !== null && $tableData !== []) {
		if ($tableData instanceof BaseResult) {
			$this->_setFromDBResult($tableData);
		} elseif (is_array($tableData)) {
			$this->_setFromArray($tableData);
		}
	}
	
	$csv = [];
	if($this->heading) {
		$csvrow = [];
		foreach($this->heading as $cell) {
			$csvrow[] = $cell['data'] ?? '';
		}
		$csv[] = $csvrow;
	}
	foreach($this->rows as $row) {
		$csvrow = [];
		foreach($row as $cell) $csvrow[] = $cell['data'] ?? '';
		$csv[] = $csvrow;
	}
	
	ob_start();
	$out = fopen('php://output', 'w');
	foreach($csv as $csvrow) {
		fputcsv($out, $csvrow, escape:"\\");
	}
	fclose($out);
	return ob_get_clean();
}

static function cell($value, $class=null) : array {
	if(!$class) return $value;
	
	return [
		'class' => match($class) {
			'currency',
			'int' => "text-end",
			default => $class,
		},
		
		'data' => match($class) {
			'currency' => htm_currency($value),
			'int' => number_format($value),
			'date' => format_datetime($value),
			default => $value
		},
	];
}

}
