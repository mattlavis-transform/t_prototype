<?php
class extract_footnotes extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date;
		$ret = "";
		$template = file_get_contents('../templates/footnotes.xml', true);
		$sql = "SELECT footnote_type_id, footnote_id, operation, validity_start_date
		FROM footnotes_oplog WHERE operation_date > $1 ORDER BY operation_date";

		$sql = "SELECT f.validity_start_date as f_validity_start_date, fd.footnote_description_period_sid, fd.footnote_type_id, fd.footnote_id,
		fd.description, f.operation as f_operation, fd.operation as fd_operation, fdp.operation as fdp_operation, fdp.validity_start_date,
		fd.operation_date, fdp.operation_date
		FROM footnote_descriptions_oplog fd, footnote_description_periods_oplog fdp, footnotes_oplog as f
		WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
		AND f.footnote_id = fd.footnote_id
		AND f.footnote_type_id = fd.footnote_type_id
		AND fd.operation_date = fdp.operation_date
		AND f.operation_date = fdp.operation_date		
		AND f.operation_date > $1
		AND fd.operation_date > $1
		AND fdp.operation_date > $1
		ORDER BY fd.operation_date";

		pg_prepare($conn, "extract_footnotes", $sql);
		$result = pg_execute($conn, "extract_footnotes", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$footnote_type_id                   = $row["footnote_type_id"];
					$footnote_id                        = $row["footnote_id"];
					$description                        = $row["description"];
					$footnote_description_period_sid	= $row["footnote_description_period_sid"];
					$f_validity_start_date              = $this->xml_date($row["f_validity_start_date"]);
					$validity_start_date                = $this->xml_date($row["validity_start_date"]);
					$f_operation						= get_operation($row["f_operation"]);
					$fd_operation						= get_operation($row["fd_operation"]);
					$fdp_operation						= get_operation($row["fdp_operation"]);

					$instance = str_replace("[TRANSACTION_ID]",						$last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]",						$message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]",						$message_id + 1, $instance);
					$instance = str_replace("[MESSAGE_ID3]",						$message_id + 2, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]",			$message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]",			$message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER3]",			$message_id + 2, $instance);
					$instance = str_replace("[F_OPERATION]",						$f_operation, $instance);
					$instance = str_replace("[FD_OPERATION]",						$fd_operation, $instance);
					$instance = str_replace("[FDP_OPERATION]",						$fdp_operation, $instance);
					$instance = str_replace("[F_VALIDITY_START_DATE]",				$f_validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]",				$validity_start_date, $instance);
					$instance = str_replace("[FOOTNOTE_TYPE_ID]",					$footnote_type_id, $instance);
					$instance = str_replace("[FOOTNOTE_ID]",						$footnote_id, $instance);
					$instance = str_replace("[FOOTNOTE_DESCRIPTION_PERIOD_SID]",	$footnote_description_period_sid, $instance);
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