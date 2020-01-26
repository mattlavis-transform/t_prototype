<?php
class measure_component
{
	// Class properties and methods go here
	public $measure_sid                     = -1;
	public $duty_expression_id              = "";
	public $duty_amount                     = Null;
	public $monetary_unit_code              = "";
	public $measurement_unit_code           = "";
	public $measurement_unit_qualifier_code = "";
	public $heading							= "";


	function populate_from_db() {
		global $conn;
		$this->heading          					= "Edit measure component";
		$sql = "select duty_amount, monetary_unit_code, measurement_unit_code, measurement_unit_qualifier_code
		from measure_components
		where measure_sid = $1 and duty_expression_id = $2";
		pg_prepare($conn, "get_component", $sql);
		$result = pg_execute($conn, "get_component", array($this->measure_sid, $this->duty_expression_id));

		if ($result) {
            $row = pg_fetch_row($result);
        	$this->duty_amount  					= $row[0];
			$this->monetary_unit_code				= $row[1];
			$this->measurement_unit_code			= $row[2];
			$this->measurement_unit_qualifier_code	= $row[3];

			$this->footnote_heading					= "Edit measure component " . $this->measure_sid;
			$this->disable_footnote_id_field		= " disabled";

		}
	}

	function populate_from_cookies() {
		$this->heading          		= "Add measure component";
        $this->validity_start_day		= get_cookie("base_regulation_validity_start_day");
        $this->validity_start_month		= get_cookie("base_regulation_validity_start_month");
        $this->validity_start_year		= get_cookie("base_regulation_validity_start_year");
        $this->base_regulation_id		= strtoupper(get_cookie("base_regulation_base_regulation_id"));
        $this->information_text_name	= get_cookie("base_regulation_information_text_name");
        $this->information_text_url		= get_cookie("base_regulation_information_text_url");
        $this->information_text_primary	= get_cookie("base_regulation_information_text_primary");
        $this->regulation_group_id		= get_cookie("base_regulation_regulation_group_id");
	}

	public function xml() {
		global $last_transaction_id, $message_id;
		$template = file_get_contents('../templates/measure.component.minus.xml', true);
		$template = str_replace("[TRANSACTION_ID]",						$last_transaction_id, $template);
		$template = str_replace("[MESSAGE_ID]",							$message_id, $template);
		$template = str_replace("[RECORD_SEQUENCE_NUMBER]",				$message_id, $template);
		$template = str_replace("[OPERATION]",							get_operation($this->operation), $template);
		$template = str_replace("[MEASURE_SID]",						$this->measure_sid, $template);
		$template = str_replace("[DUTY_EXPRESSION_ID]",					$this->duty_expression_id, $template);
		$template = str_replace("[DUTY_AMOUNT]",						$this->duty_amount, $template);
		$template = str_replace("[MONETARY_UNIT_CODE]",					$this->monetary_unit_code, $template);
		$template = str_replace("[MEASUREMENT_UNIT_CODE]",				$this->measurement_unit_code, $template);
		$template = str_replace("[MEASUREMENT_UNIT_QUALIFIER_CODE]",	$this->measurement_unit_qualifier_code, $template);
		$template = str_replace("\t\t\t\t\t\t<oub:duty.amount></oub:duty.amount>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:monetary.unit.code></oub:monetary.unit.code>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:measurement.unit.code></oub:measurement.unit.code>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:measurement.unit.qualifier.code></oub:measurement.unit.qualifier.code>\n", "", $template);
		$message_id += 1;
		return ($template);
	}
}
