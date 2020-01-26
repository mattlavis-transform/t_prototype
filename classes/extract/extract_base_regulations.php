<?php
class extract_base_regulations extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date;
		$ret = "";
		$template = file_get_contents('../templates/base.regulation.update.xml', true);
		
		$sql = "SELECT base_regulation_role, base_regulation_id, validity_start_date, validity_end_date,
		community_code, regulation_group_id, replacement_indicator, stopped_flag, information_text,
		approved_flag, published_date, officialjournal_number, officialjournal_page, effective_end_date,
		antidumping_regulation_role, related_antidumping_regulation_id, complete_abrogation_regulation_role,
		complete_abrogation_regulation_id, explicit_abrogation_regulation_role, explicit_abrogation_regulation_id,
		operation, operation_date FROM base_regulations WHERE base_regulations.operation_date > $1
		ORDER BY operation_date";
		
		pg_prepare($conn, "extract_base_regulations", $sql);
		$result = pg_execute($conn, "extract_base_regulations", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                               = $template;
					$base_regulation_role                   = $row["base_regulation_role"];
					$base_regulation_id                     = $row["base_regulation_id"];
					$validity_start_date                    = string_to_date($row["validity_start_date"]);
					$validity_end_date                      = string_to_date($row["validity_end_date"]);
					$community_code                         = $row["community_code"];
					$regulation_group_id                    = $row["regulation_group_id"];
					$replacement_indicator                  = $row["replacement_indicator"];
					$stopped_flag                           = bool_to_int($row["stopped_flag"]);
					$information_text                       = $row["information_text"];
					$approved_flag                          = bool_to_int($row["approved_flag"]);
					$published_date                         = $row["published_date"];
					$officialjournal_number                 = $row["officialjournal_number"];
					$officialjournal_page                   = $row["officialjournal_page"];
					$effective_end_date                     = string_to_date($row["effective_end_date"]);

					$operation = get_operation($row["operation"]);
					$instance = str_replace("[TRANSACTION_ID]", $last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID]", $message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER]", $message_id, $instance);
					$instance = str_replace("[OPERATION]", $operation, $instance);
					$instance = str_replace("[BASE_REGULATION_ROLE]", $base_regulation_role, $instance);
					$instance = str_replace("[BASE_REGULATION_ID]", $base_regulation_id, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]", $validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_END_DATE]", $validity_end_date, $instance);
					$instance = str_replace("[COMMUNITY_CODE]", $community_code, $instance);
					$instance = str_replace("[REGULATION_GROUP_ID]", $regulation_group_id, $instance);
					$instance = str_replace("[REPLACEMENT_INDICATOR]", $replacement_indicator, $instance);
					$instance = str_replace("[STOPPED_FLAG]", $stopped_flag, $instance);
					$instance = str_replace("[INFORMATION_TEXT]", $information_text, $instance);
					$instance = str_replace("[APPROVED_FLAG]", $approved_flag, $instance);
					$instance = str_replace("[PUBLISHED_DATE]", $published_date, $instance);
					$instance = str_replace("[OFFICIALJOURNAL_NUMBER]", $officialjournal_number, $instance);
					$instance = str_replace("[OFFICIALJOURNAL_PAGE]", $officialjournal_page, $instance);
					$instance = str_replace("[EFFECTIVE_END_DATE]", $effective_end_date, $instance);

					$instance = str_replace("\t\t\t\t\t\t<oub:validity.end.date></oub:validity.end.date>\n", "", $instance);
					$instance = str_replace("\t\t\t\t\t\t<oub:effective.end.date></oub:effective.end.date>\n", "", $instance);

					$ret .= $instance;
					$message_id += 1;
					$last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}
}