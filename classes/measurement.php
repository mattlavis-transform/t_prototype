<?php
class measurement
{
	// Class properties and methods go here
	public $measurement_unit_code           = "";
	public $measurement_unit_qualifier_code = "";
	public $description                     = "";

	public function set_properties($measurement_unit_code, $measurement_unit_qualifier_code, $description) {
		$this->measurement_unit_code			= $measurement_unit_code;
		$this->measurement_unit_qualifier_code  = $measurement_unit_qualifier_code;
		$this->description				        = $description;
	}
} 