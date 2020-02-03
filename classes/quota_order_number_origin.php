<?php
class quota_order_number_origin
{
	// Class properties and methods go here
	public $quota_order_number_origin_sid	= 0;
	public $geographical_area_id			= "";
	public $quota_order_number_id           = "";
	public $quota_order_number_sid          = 0;
	public $description           			= "";
	public $validity_start_date   			= "";
	public $validity_end_date   			= "";
	public $exclusion_text        			= "";
	public $exclusions = array ();

    function populate_from_cookies(){
        $this->validity_start_date_day				= get_cookie("quota_order_number_origin_validity_start_date_day");
        $this->validity_start_date_month				= get_cookie("quota_order_number_origin_validity_start_date_month");
        $this->validity_start_date_year				= get_cookie("quota_order_number_origin_validity_start_date_year");
        $this->geographical_area_id				= get_cookie("geographical_area_id");
    }


	public function set_properties($quota_order_number_origin_sid, $geographical_area_id, $quota_order_number_id, $quota_order_number_sid, $description) {
		$this->quota_order_number_origin_sid	= $quota_order_number_origin_sid;
		$this->geographical_area_id				= $geographical_area_id;
		$this->quota_order_number_id			= $quota_order_number_id;
		$this->quota_order_number_sid			= $quota_order_number_sid;
		$this->description						= $description;
	}

	public function get_exclusion_text() {
		$qonoe_count = count($this->exclusions);
		$this->exclusion_text = "";
		if ($qonoe_count > 0) {
			for($k = 0; $k < $qonoe_count; $k++) {
				$this->exclusion_text .= "<p class='tight'>" . $this->exclusions[$k]->description . "</p>";
			}
		}
	}
} 