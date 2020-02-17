<?php
class measure_condition
{
	// Class properties and methods go here
	public $condition_string    						= "";
	public $action_string       						= "";

	public $measure_condition_sid       				= Null;
	public $measure_sid       							= Null;
	public $condition_code       						= Null;
	public $component_sequence_number       			= Null;
	public $condition_duty_amount       				= Null;
	public $condition_monetary_unit_code       			= Null;
	public $condition_measurement_unit_code       		= Null;
	public $condition_measurement_unit_qualifier_code	= Null;
	public $action_code       							= Null;
	public $certificate_type_code       				= Null;
	public $certificate_code       						= Null;

	function populate_from_cookies() {
		$this->heading          		= "Add measure condition";
        $this->validity_start_date_day		= get_cookie("base_regulation_validity_start_date_day");
        $this->validity_start_date_month		= get_cookie("base_regulation_validity_start_date_month");
        $this->validity_start_date_year		= get_cookie("base_regulation_validity_start_date_year");
        $this->base_regulation_id		= strtoupper(get_cookie("base_regulation_base_regulation_id"));
        $this->information_text_name	= get_cookie("base_regulation_information_text_name");
        $this->information_text_url		= get_cookie("base_regulation_information_text_url");
        $this->information_text_primary	= get_cookie("base_regulation_information_text_primary");
        $this->regulation_group_id		= get_cookie("base_regulation_regulation_group_id");
	}

	public function get_condition_string() {
		$duty_list = explode(",", $this->duties);
		$m = new measure();
		foreach ($duty_list as $duty) {
			if ($duty != "") {
				$parts = explode("|", $duty);
				$mc = new duty();
				$mc->measure_type_id = "n/a";
				$mc->duty_expression_id = $parts[0];
				$mc->duty_amount = $parts[1];
				$mc->monetary_unit_code = $parts[2];
				$mc->measurement_unit_code = $parts[3];
				$mc->measurement_unit_qualifier_code = $parts[4];
				$mc->get_duty_string();
				array_push($m->duty_list, $mc);
	
			}
		}
		$m->combine_duties();
		$this->condition_string = $m->combined_duty;
	}

	/*
	mcc.duty_expression_id || '|' ||
            coalesce (mcc.duty_amount::text, '') || '|' ||
            coalesce (mcc.monetary_unit_code, '') || '|' ||
            coalesce (mcc.measurement_unit_code, '') || '|' ||
			coalesce (mcc.measurement_unit_qualifier_code, ''),
	*/

	function get_next_measure_condition_sid() {
		global $conn;
        $application = new application;
		$s = $application->get_single_value("SELECT MAX(measure_condition_sid) FROM measure_conditions");
		$s += 1;
		return ($s);
	}

	function insert() {
		global $conn;
        $application = new application;
        $operation = "C";
		$operation_date = $application->get_operation_date();
		$this->measure_condition_sid = $this->get_next_measure_condition_sid();
		h1 ($this->measure_condition_sid);
		$sql = "INSERT INTO measure_conditions_oplog
		(measure_condition_sid, measure_sid, condition_code, component_sequence_number, condition_duty_amount,
		condition_monetary_unit_code, condition_measurement_unit_code,
		condition_measurement_unit_qualifier_code, action_code,
		certificate_type_code, certificate_code,
		operation, operation_date)
		VALUES (
		$1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13)";
		pg_prepare($conn, "measure_condition_insert", $sql);
		pg_execute($conn, "measure_condition_insert", array(
			$this->measure_condition_sid,
			$this->measure_sid,
			$this->condition_code,
			$this->component_sequence_number,
			$this->condition_duty_amount,
			$this->condition_monetary_unit_code,
			$this->condition_measurement_unit_code,
			$this->condition_measurement_unit_qualifier_code,
			$this->action_code,
			$this->certificate_type_code,
			$this->certificate_code,
			$operation,
			$operation_date
		));
	}

	public function xml() {
		global $last_transaction_id, $message_id;
		$template = file_get_contents('../templates/measure.condition.minus.xml', true);
		$template = str_replace("[TRANSACTION_ID]",								$last_transaction_id, $template);
		$template = str_replace("[MESSAGE_ID]",									$message_id, $template);
		$template = str_replace("[RECORD_SEQUENCE_NUMBER]",						$message_id, $template);
		$template = str_replace("[OPERATION]",									get_operation($this->operation), $template);
		$template = str_replace("[MEASURE_SID]",								$this->measure_sid, $template);
		$template = str_replace("[MEASURE_CONDITION_SID]",						$this->measure_condition_sid, $template);
		$template = str_replace("[CONDITION_CODE]",								$this->condition_code, $template);
		$template = str_replace("[COMPONENT_SEQUENCE_NUMBER]",					$this->component_sequence_number, $template);
		$template = str_replace("[CONDITION_DUTY_AMOUNT]",						$this->condition_duty_amount, $template);
		$template = str_replace("[CONDITION_MONETARY_UNIT_CODE]",				$this->condition_monetary_unit_code, $template);
		$template = str_replace("[CONDITION_MEASUREMENT_UNIT_CODE]",			$this->condition_measurement_unit_code, $template);
		$template = str_replace("[CONDITION_MEASUREMENT_UNIT_QUALIFIER_CODE]",	$this->condition_measurement_unit_qualifier_code, $template);
		$template = str_replace("[ACTION_CODE]",								$this->action_code, $template);
		$template = str_replace("[CERTIFICATE_TYPE_CODE]",						$this->certificate_type_code, $template);
		$template = str_replace("[CERTIFICATE_CODE]",							$this->certificate_code, $template);
		
		$template = str_replace("\t\t\t\t\t\t<oub:condition.duty.amount></oub:condition.duty.amount>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:condition.monetary.unit.code></oub:condition.monetary.unit.code>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:condition.measurement.unit.code></oub:condition.measurement.unit.code>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:condition.measurement.unit.qualifier.code></oub:condition.measurement.unit.qualifier.code>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:action.code></oub:action.code>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:certificate.type.code></oub:certificate.type.code>\n", "", $template);
		$template = str_replace("\t\t\t\t\t\t<oub:certificate.code></oub:certificate.code>\n", "", $template);
		$message_id += 1;
		return ($template);
	}
} 
