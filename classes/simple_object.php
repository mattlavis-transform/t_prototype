<?php
class simple_object
{
    // Class properties and methods go here
	public $id = "";
	public $string = "";
	public $abbreviation = "";
	public $detail = "";

    public function __construct($id, $string, $abbreviation = "", $detail = "", $use_abbreviation = false) {
		$this->id  = $id;
		if ($use_abbreviation == true){
			$this->string  = $abbreviation;

		} else {
			$this->string  = $string;
		}
		$this->abbreviation  = $abbreviation;
		$this->detail  = $detail;
	}
}