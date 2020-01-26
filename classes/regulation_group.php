<?php
class regulation_group
{
	// Class properties and methods go here
	public $regulation_group_id  = "";
	public $description                         = "";

    public function set_properties($regulation_group_id, $description) {
		$this->regulation_group_id  = $regulation_group_id;
		$this->description			= $description;
	}
} 