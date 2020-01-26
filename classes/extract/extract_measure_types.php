<?php
class extract_measure_types extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date;
		$ret = "";
		$template = file_get_contents('../templates/measure.type.xml', true);
		$sql = "SELECT mt.measure_type_id, mt.validity_start_date, mt.validity_end_date, mt.trade_movement_code,
		mt.priority_code, mt.measure_component_applicable_code, mt.origin_dest_code,
		mt.order_number_capture_code, mt.measure_explosion_level, mt.measure_type_series_id, mtd.description,
		mt.operation as mt_operation, mtd.operation as mtd_operation
		FROM measure_types_oplog mt, measure_type_descriptions_oplog mtd
		WHERE mt.measure_type_id = mtd.measure_type_id
		AND mt.operation_date > $1 AND mt.operation_date IS NOT NULL
		ORDER BY mt.operation_date";
		
		pg_prepare($conn, "extract_measure_types", $sql);
		$result = pg_execute($conn, "extract_measure_types", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$measure_type_id                    = $row["measure_type_id"];
					$validity_start_date                = $this->xml_date($row["validity_start_date"]);
					$validity_end_date                  = $this->xml_date($row["validity_end_date"]);
					$trade_movement_code                = $row["trade_movement_code"];
					$priority_code                      = $row["priority_code"];
					$measure_component_applicable_code  = $row["measure_component_applicable_code"];
					$origin_dest_code                   = $row["origin_dest_code"];
					$order_number_capture_code          = $row["order_number_capture_code"];
					$measure_explosion_level            = $row["measure_explosion_level"];
					$measure_type_series_id             = $row["measure_type_series_id"];
					$description                        = $row["description"];
					$mt_operation                       = get_operation($row["mt_operation"]);
					$mtd_operation                      = get_operation($row["mtd_operation"]);

					$instance = str_replace("[TRANSACTION_ID]", $last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]", $message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]", $message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]", $message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]", $message_id + 1, $instance);
					$instance = str_replace("[MT_OPERATION]", $mt_operation, $instance);
					$instance = str_replace("[MTD_OPERATION]", $mtd_operation, $instance);
					$instance = str_replace("[MEASURE_TYPE_ID]", $measure_type_id, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]", $validity_start_date, $instance);
					$instance = str_replace("[VALIDITY_END_DATE]", $validity_end_date, $instance);
					$instance = str_replace("[TRADE_MOVEMENT_CODE]", $trade_movement_code, $instance);
					$instance = str_replace("[PRIORITY_CODE]", $priority_code, $instance);
					$instance = str_replace("[MEASURE_COMPONENT_APPLICABLE_CODE]", $measure_component_applicable_code, $instance);
					$instance = str_replace("[ORIGIN_DEST_CODE]", $origin_dest_code, $instance);
					$instance = str_replace("[ORDER_NUMBER_CAPTURE_CODE]", $order_number_capture_code, $instance);
					$instance = str_replace("[MEASURE_EXPLOSION_LEVEL]", $measure_explosion_level, $instance);
					$instance = str_replace("[MEASURE_TYPE_SERIES_ID]", $measure_type_series_id, $instance);
					$instance = str_replace("[DESCRIPTION]", $description, $instance);

					$instance = str_replace("\t\t\t\t\t\t<oub:validity.end.date></oub:validity.end.date>\n", "", $instance);

					$ret .= $instance;
					$message_id += 2;
					$last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}
}