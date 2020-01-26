<?php
class extract_geographical_area_descriptions extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date;
		$ret = "";
		$template = file_get_contents('../templates/geographical.area.descriptions.xml', true);
		$sql = "SELECT gad.geographical_area_description_period_sid, gad.geographical_area_sid, gad.geographical_area_id,
		gad.description, gadp.operation as gadp_operation, gad.operation as gad_operation, gadp.validity_start_date
		FROM geographical_area_descriptions_oplog gad, geographical_area_description_periods_oplog gadp
		WHERE gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
		AND gad.operation_date > $1";
		
		pg_prepare($conn, "extract_geographical_area_descriptions", $sql);
		$result = pg_execute($conn, "extract_geographical_area_descriptions", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                                   = $template;
					$geographical_area_description_period_sid   = $row["geographical_area_description_period_sid"];
					$geographical_area_sid                      = $row["geographical_area_sid"];
					$geographical_area_id                       = $row["geographical_area_id"];
					$description                                = $row["description"];
					$validity_start_date                        = $this->xml_date($row["validity_start_date"]);
					$gad_operation                              = get_operation($row["gad_operation"]);
					$gadp_operation                             = get_operation($row["gadp_operation"]);
					$instance = str_replace("[TRANSACTION_ID]", $last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]", $message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]", $message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]", $message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]", $message_id + 1, $instance);
					$instance = str_replace("[GADP_OPERATION]", $gadp_operation, $instance);
					$instance = str_replace("[GAD_OPERATION]", $gad_operation, $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_DESCRIPTION_PERIOD_SID]", $geographical_area_description_period_sid, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]", $validity_start_date, $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_SID]", $geographical_area_sid, $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_ID]", $geographical_area_id, $instance);
					$instance = str_replace("[DESCRIPTION]", $description, $instance);
					$ret .= $instance;
					$message_id += 2;
					$last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}

}