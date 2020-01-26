<?php
class extract_certificates extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date;

		$ret = "";
		$template = file_get_contents('../templates/certificates.xml', true);

		$sql = "SELECT c.validity_start_date as c_validity_start_date, cd.certificate_description_period_sid, cd.certificate_type_code, cd.certificate_code,
		cd.description, c.operation as c_operation, cd.operation as cd_operation, cdp.operation as cdp_operation, cdp.validity_start_date,
		cd.operation_date, cdp.operation_date
		FROM certificate_descriptions_oplog cd, certificate_description_periods_oplog cdp, certificates_oplog as c
		WHERE cd.certificate_description_period_sid = cdp.certificate_description_period_sid
		AND c.certificate_code = cd.certificate_code
		AND c.certificate_type_code = cd.certificate_type_code
		AND cd.operation_date = cdp.operation_date
		AND c.operation_date = cdp.operation_date		
		AND c.operation_date > $1
		AND cd.operation_date > $1
		AND cdp.operation_date > $1
		ORDER BY cd.operation_date";

		pg_prepare($conn, "extract_certificates", $sql);
		$result = pg_execute($conn, "extract_certificates", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$certificate_type_code              = $row["certificate_type_code"];
					$certificate_code                   = $row["certificate_code"];
					$description                        = $row["description"];
					$certificate_description_period_sid	= $row["certificate_description_period_sid"];
					$c_validity_start_date              = $this->xml_date($row["c_validity_start_date"]);
					$validity_start_date                = $this->xml_date($row["validity_start_date"]);
					$c_operation						= get_operation($row["c_operation"]);
					$cd_operation						= get_operation($row["cd_operation"]);
					$cdp_operation						= get_operation($row["cdp_operation"]);

					$instance = str_replace("[TRANSACTION_ID]",						$last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]",						$message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]",						$message_id + 1, $instance);
					$instance = str_replace("[MESSAGE_ID3]",						$message_id + 2, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]",			$message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]",			$message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER3]",			$message_id + 2, $instance);
					$instance = str_replace("[C_OPERATION]",						$c_operation, $instance);
					$instance = str_replace("[CD_OPERATION]",						$cd_operation, $instance);
					$instance = str_replace("[CDP_OPERATION]",						$cdp_operation, $instance);
					$instance = str_replace("[C_VALIDITY_START_DATE]",				$c_validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]",				$validity_start_date, $instance);
					$instance = str_replace("[CERTIFICATE_TYPE_CODE]",				$certificate_type_code, $instance);
					$instance = str_replace("[CERTIFICATE_CODE]",					$certificate_code, $instance);
					$instance = str_replace("[CERTIFICATE_DESCRIPTION_PERIOD_SID]",	$certificate_description_period_sid, $instance);
					$instance = str_replace("[DESCRIPTION]",						$description, $instance);
					
					$ret .= $instance;
					$message_id += 3;
					$last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}
}