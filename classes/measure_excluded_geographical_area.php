<?php
class measure_excluded_geographical_area
{
	// Class properties and methods go here
	public $measure_sid                 = -1;
    public $excluded_geographical_area  = "";
    public $geographical_area_sid       = -1;

    
    function populate_from_cookies() {
		$this->heading          		= "Add geographical area exclusion";
        $this->validity_start_date_day		= get_cookie("base_regulation_validity_start_date_day");
        $this->validity_start_date_month		= get_cookie("base_regulation_validity_start_date_month");
        $this->validity_start_date_year		= get_cookie("base_regulation_validity_start_date_year");
        $this->base_regulation_id		= strtoupper(get_cookie("base_regulation_base_regulation_id"));
        $this->information_text_name	= get_cookie("base_regulation_information_text_name");
        $this->information_text_url		= get_cookie("base_regulation_information_text_url");
        $this->information_text_primary	= get_cookie("base_regulation_information_text_primary");
        $this->regulation_group_id		= get_cookie("base_regulation_regulation_group_id");
	}

    function xml() {
		global $last_transaction_id, $message_id;
		$template = file_get_contents('../templates/measure.excluded.geographical.area.minus.xml', true);
		$template = str_replace("[TRANSACTION_ID]",						$last_transaction_id, $template);
		$template = str_replace("[MESSAGE_ID]",							$message_id, $template);
		$template = str_replace("[RECORD_SEQUENCE_NUMBER]",				$message_id, $template);
		$template = str_replace("[OPERATION]",							get_operation($this->operation), $template);
		$template = str_replace("[MEASURE_SID]",						$this->measure_sid, $template);
		$template = str_replace("[EXCLUDED_GEOGRAPHICAL_AREA]",			$this->excluded_geographical_area, $template);
		$template = str_replace("[GEOGRAPHICAL_AREA_ID]",	            $this->geographical_area_sid, $template);
		$message_id += 1;
        return ($template);
    }

}
