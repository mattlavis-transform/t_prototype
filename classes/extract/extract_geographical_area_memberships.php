<?php
class extract_geographical_area_memberships extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id;
		$ret = "";
		$template = file_get_contents('../templates/geographical.area.membership.xml', true);
		$sql = "SELECT geographical_area_sid, geographical_area_group_sid, validity_start_date, validity_end_date, operation
		FROM geographical_area_memberships_oplog
		WHERE operation_date > $1 AND operation_date IS NOT NULL
		ORDER BY operation_date";
		
		pg_prepare($conn, "extract_geographical_area_memberships", $sql);
		$result = pg_execute($conn, "extract_geographical_area_memberships", array('2019-10-25 08:00:00'));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                       = $template;
					$geographical_area_sid          = $row["geographical_area_sid"];
					$geographical_area_group_sid    = $row["geographical_area_group_sid"];
					$operation                      = $row["operation"];
					$validity_start_date            = $this->xml_date($row["validity_start_date"]);
					$validity_end_date              = $this->xml_date($row["validity_end_date"]);

					$instance = str_replace("[TRANSACTION_ID]", $last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]", $message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]", $message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]", $message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]", $message_id + 1, $instance);
					$instance = str_replace("[OPERATION]", get_operation($operation), $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_SID]", $geographical_area_sid, $instance);
					$instance = str_replace("[GEOGRAPHICAL_AREA_GROUP_SID]", $geographical_area_group_sid, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]", $validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_END_DATE]", $validity_end_date, $instance);

					$instance = str_replace("\t\t\t\t\t\t<oub:validity.end.date></oub:validity.end.date>\n", "", $instance);

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