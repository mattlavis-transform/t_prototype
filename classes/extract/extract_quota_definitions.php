<?php
class extract_quota_definitions extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date;
		$ret = "";
		$template = file_get_contents('../templates/quota.definition.xml', true);
		
		$sql = "select quota_definition_sid, quota_order_number_id, validity_start_date,
        validity_end_date, quota_order_number_sid, volume, initial_volume,
        measurement_unit_code, maximum_precision, critical_state, critical_threshold,
        monetary_unit_code, measurement_unit_qualifier_code, description, operation
        from quota_definitions_oplog WHERE operation_date > $1
		ORDER BY operation_date";
		
		pg_prepare($conn, "extract_quota_definitions", $sql);
		$result = pg_execute($conn, "extract_quota_definitions", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$quota_definition_sid               = $row["quota_definition_sid"];
					$quota_order_number_id              = $row["quota_order_number_id"];
					$validity_start_date                = string_to_date($row["validity_start_date"]);
					$validity_end_date                  = string_to_date($row["validity_end_date"]);
					$quota_order_number_sid             = $row["quota_order_number_sid"];
					$volume                             = $row["volume"];
					$initial_volume                     = $row["initial_volume"];
					$measurement_unit_code              = $row["measurement_unit_code"];
					$maximum_precision                  = $row["maximum_precision"];
					$critical_state                     = $row["critical_state"];
					$critical_threshold                 = $row["critical_threshold"];
					$monetary_unit_code                 = $row["monetary_unit_code"];
					$measurement_unit_qualifier_code    = $row["measurement_unit_qualifier_code"];
					$description                        = $row["description"];
					$operation = get_operation($row["operation"]);
                    
					$instance = str_replace("[TRANSACTION_ID]", $last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID]", $message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER]", $message_id, $instance);
                    $instance = str_replace("[OPERATION]", $operation, $instance);
                    
					$instance = str_replace("[QUOTA_DEFINITION_SID]",               $quota_definition_sid, $instance);
					$instance = str_replace("[QUOTA_ORDER_NUMBER_ID]",              $quota_order_number_id, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]",                $validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_END_DATE]",                  $validity_end_date, $instance);
					$instance = str_replace("[QUOTA_ORDER_NUMBER_SID]",             $quota_order_number_sid, $instance);
					$instance = str_replace("[VOLUME]",                             $volume, $instance);
					$instance = str_replace("[INITIAL_VOLUME]",                     $initial_volume, $instance);
					$instance = str_replace("[MEASUREMENT_UNIT_CODE]",              $measurement_unit_code, $instance);
					$instance = str_replace("[MAXIMUM_PRECISION]",                  $maximum_precision, $instance);
					$instance = str_replace("[CRITICAL_STATE]",                     $critical_state, $instance);
					$instance = str_replace("[CRITICAL_THRESHOLD]",                 $critical_threshold, $instance);
					$instance = str_replace("[MONETARY_UNIT_CODE]",                 $monetary_unit_code, $instance);
					$instance = str_replace("[MEASUREMENT_UNIT_QUALIFIER_CODE]",    $measurement_unit_qualifier_code, $instance);
					$instance = str_replace("[DESCRIPTION]",                        $description, $instance);

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