<?php
class extract_measure_excluded_geographical_areas extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $extracted_measure_list, $last_exported_operation_date;
		$ret = "";
		$template = file_get_contents('../templates/measure.excluded.geographical.area.xml', true);
				
		$sql = "SELECT measure_sid, excluded_geographical_area, geographical_area_sid, operation
		from measure_excluded_geographical_areas WHERE operation_date > $1
		ORDER BY operation_date";
		
		pg_prepare($conn, "extract_measure_excluded_geographical_areas", $sql);
		$result = pg_execute($conn, "extract_measure_excluded_geographical_areas", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$measure_sid                   		= $row["measure_sid"];
					if (!in_array($measure_sid, $extracted_measure_list)) {
						$excluded_geographical_area     = $row["excluded_geographical_area"];
						$geographical_area_sid          = $row["geographical_area_sid"];
						$operation						= get_operation($row["operation"]);

						$instance = str_replace("[TRANSACTION_ID]",						$last_transaction_id, $instance);
						$instance = str_replace("[MESSAGE_ID]",							$message_id, $instance);
						$instance = str_replace("[RECORD_SEQUENCE_NUMBER]",				$message_id, $instance);
						$instance = str_replace("[OPERATION]",							$operation, $instance);
						$instance = str_replace("[MEASURE_SID]",						$measure_sid, $instance);
						$instance = str_replace("[EXCLUDED_GEOGRAPHICAL_AREA]",			$excluded_geographical_area, $instance);
						$instance = str_replace("[GEOGRAPHICAL_AREA_SID]",				$geographical_area_sid, $instance);
	
						$ret .= $instance;
						$message_id += 1;
						$last_transaction_id += 1;
					}
				}
			}
		}
		return ($ret);
	}
}
?>