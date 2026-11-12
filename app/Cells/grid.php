<?php

namespace App\Cells;

class grid implements \stringable {
	
function __construct($cols=1, $rows=1, $class="grid") {
	$this->cols = $cols;
	$this->rows = $rows;
	$this->class = $class;
}

function __tostring() : string  {
	ob_start();
	printf('<table class="%s">', $this->class);
	for($row=1; $row <= $this->rows; $row++) {
		echo '<tr>';
		for ($col=1; $col <= $this->cols; $col++) {
			echo '<td class="border"></td>';
		}
		echo '</tr>';
	}
	echo '</table>';
	return ob_get_clean();
}

}
