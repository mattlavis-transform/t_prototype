<?php
class extract_measure_conditions extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date;
		$ret = "";
		$template = file_get_contents('../templates/measure.component.xml', true);
				
		$sql = "SELECT measure_sid, duty_expression_id, duty_amount, monetary_unit_code, measurement_unit_code, measurement_unit_qualifier_code, operation
		from measure_components WHERE operation_date > $1
		ORDER BY operation_date";
		
		pg_prepare($conn, "extract_measure_components", $sql);
		$result = pg_execute($conn, "extract_measure_components", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$measure_sid                   		= $row["measure_sid"];
					$duty_expression_id                 = $row["duty_expression_id"];
					$duty_amount                   		= $row["duty_amount"];
					$monetary_unit_code                 = $row["monetary_unit_code"];
					$measurement_unit_code              = $row["measurement_unit_code"];
					$measurement_unit_qualifier_code	= $row["measurement_unit_qualifier_code"];
					$operation							= get_operation($row["operation"]);

					$instance = str_replace("[TRANSACTION_ID]",						$last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID]",							$message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER]",				$message_id, $instance);
					$instance = str_replace("[OPERATION]",							$operation, $instance);
					$instance = str_replace("[MEASURE_SID]",						$measure_sid, $instance);
					$instance = str_replace("[DUTY_EXPRESSION_ID]",					$duty_expression_id, $instance);
					$instance = str_replace("[DUTY_AMOUNT]",						$duty_amount, $instance);
					$instance = str_replace("[MONETARY_UNIT_CODE]",					$monetary_unit_code, $instance);
					$instance = str_replace("[MEASUREMENT_UNIT_CODE]",				$measurement_unit_code, $instance);
					$instance = str_replace("[MEASUREMENT_UNIT_QUALIFIER_CODE]",	$measurement_unit_qualifier_code, $instance);

					$instance = str_replace("\t\t\t\t\t\t<oub:duty.amount></oub:duty.amount>\n", "", $instance);
					$instance = str_replace("\t\t\t\t\t\t<oub:monetary.unit.code></oub:monetary.unit.code>\n", "", $instance);
					$instance = str_replace("\t\t\t\t\t\t<oub:measurement.unit.code></oub:measurement.unit.code>\n", "", $instance);
					$instance = str_replace("\t\t\t\t\t\t<oub:measurement.unit.qualifier.code></oub:measurement.unit.qualifier.code>\n", "", $instance);

					$ret .= $instance;
					$message_id += 1;
					$last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}
}
?>