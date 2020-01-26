<?php
class quota_order_number_origin_exclusion
{
	// Class properties and methods go here
	public $quota_order_number_origin_sid	= 0;
	public $excluded_geographical_area_sid	= 0;
	public $description           			= "";

	public function set_properties($quota_order_number_origin_sid, $excluded_geographical_area_sid, $description) {
		$this->quota_order_number_origin_sid	= $quota_order_number_origin_sid;
		$this->excluded_geographical_area_sid   = $excluded_geographical_area_sid;
		$this->description				        = $description;
	}

} 