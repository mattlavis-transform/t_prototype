<?php
class extract_measures extends extract
{
	function extract() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date, $extracted_measure_list;
		$measures = array();
		$ret = "";
		$template = file_get_contents('../templates/measure.xml', true);
				
		$sql = "SELECT measure_sid, measure_type_id, geographical_area_id, goods_nomenclature_item_id, 
		validity_start_date, validity_end_date, measure_generating_regulation_role, measure_generating_regulation_id, 
		justification_regulation_role, justification_regulation_id, stopped_flag, 
		geographical_area_sid, goods_nomenclature_sid, ordernumber, 
		additional_code_type_id, additional_code_id, additional_code_sid, 
		reduction_indicator, export_refund_nomenclature_sid, operation, operation_date
		from measures_oplog WHERE operation_date > $1
		ORDER BY operation_date";
		
		pg_prepare($conn, "extract_measures", $sql);
		$result = pg_execute($conn, "extract_measures", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$measure										= new measure();
					$measure->measure_sid                   		= $row["measure_sid"];
					$measure->measure_type_id              			= $row["measure_type_id"];
					$measure->geographical_area_id					= $row["geographical_area_id"];
					$measure->goods_nomenclature_item_id         	= $row["goods_nomenclature_item_id"];
					$measure->validity_start_date					= xml_date($row["validity_start_date"]);
					$measure->validity_end_date              		= xml_date($row["validity_end_date"]);
					$measure->measure_generating_regulation_role	= $row["measure_generating_regulation_role"];
					$measure->measure_generating_regulation_id   	= $row["measure_generating_regulation_id"];
					$measure->justification_regulation_role			= $row["justification_regulation_role"];
					$measure->justification_regulation_id        	= $row["justification_regulation_id"];
					$measure->stopped_flag							= $row["stopped_flag"];
					$measure->geographical_area_sid              	= $row["geographical_area_sid"];
					$measure->goods_nomenclature_sid				= $row["goods_nomenclature_sid"];
					$measure->ordernumber              				= $row["ordernumber"];
					$measure->additional_code_type_id				= $row["additional_code_type_id"];
					$measure->additional_code_id              		= $row["additional_code_id"];
					$measure->additional_code_sid					= $row["additional_code_sid"];
					$measure->reduction_indicator              		= $row["reduction_indicator"];
					$measure->export_refund_nomenclature_sid		= $row["export_refund_nomenclature_sid"];
					$measure->operation								= $row["operation"];
					$measure->operation_string						= get_operation($measure->operation);
					$measure->message_id							= $message_id;
					$message_id += 1;

					$measure->get_measure_oplog_components();
					$measure->get_measure_oplog_conditions();
					$measure->get_measure_oplog_excluded_geographical_areas();

					array_push($measures, $measure);
					array_push($extracted_measure_list, $measure->measure_sid);
				}
			}
		}

		foreach ($measures as $measure) {
			$instance =														$template;
			$instance = str_replace("[TRANSACTION_ID]",						$last_transaction_id, $instance);
			$instance = str_replace("[MESSAGE_ID]",							$measure->message_id, $instance);
			$instance = str_replace("[RECORD_SEQUENCE_NUMBER]",				$measure->message_id, $instance);
			$instance = str_replace("[OPERATION]",							$measure->operation_string, $instance);

			$instance = str_replace("[MEASURE_SID]",						$measure->measure_sid, $instance);
			$instance = str_replace("[MEASURE_TYPE_ID]",					$measure->measure_type_id, $instance);
			$instance = str_replace("[GEOGRAPHICAL_AREA_ID]",				$measure->geographical_area_id, $instance);
			$instance = str_replace("[GOODS_NOMENCLATURE_ITEM_ID]",			$measure->goods_nomenclature_item_id, $instance);
			$instance = str_replace("[ADDITIONAL_CODE_TYPE_ID]",			$measure->additional_code_type_id, $instance);
			$instance = str_replace("[ADDITIONAL_CODE_ID]",					$measure->additional_code_id, $instance);
			$instance = str_replace("[ORDERNUMBER]",						$measure->ordernumber, $instance);
			$instance = str_replace("[REDUCTION_INDICATOR]",				$measure->reduction_indicator, $instance);
			$instance = str_replace("[VALIDITY_START_DATE]",				$measure->validity_start_date, $instance);
			$instance = str_replace("[MEASURE_GENERATING_REGULATION_ROLE]",	$measure->measure_generating_regulation_role, $instance);
			$instance = str_replace("[MEASURE_GENERATING_REGULATION_ID]",	$measure->measure_generating_regulation_id, $instance);
			$instance = str_replace("[VALIDITY_END_DATE]",					$measure->validity_end_date, $instance);
			$instance = str_replace("[JUSTIFICATION_REGULATION_ROLE]",		$measure->justification_regulation_role, $instance);
			$instance = str_replace("[JUSTIFICATION_REGULATION_ID]",		$measure->justification_regulation_id, $instance);
			$instance = str_replace("[STOPPED_FLAG]",						$measure->stopped_flag, $instance);
			$instance = str_replace("[GEOGRAPHICAL_AREA_SID]",				$measure->geographical_area_sid, $instance);
			$instance = str_replace("[GOODS_NOMENCLATURE_SID]",				$measure->goods_nomenclature_sid, $instance);
			$instance = str_replace("[ADDITIONAL_CODE_SID]",				$measure->additional_code_sid, $instance);
			$instance = str_replace("[EXPORT_REFUND_NOMENCLATURE_SID]",		$measure->export_refund_nomenclature_sid, $instance);

			$instance = str_replace("\t\t\t\t\t\t<oub:goods.nomenclature.item.id></oub:goods.nomenclature.item.id>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:additional.code.type></oub:additional.code.type>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:additional.code></oub:additional.code>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:ordernumber></oub:ordernumber>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:reduction.indicator></oub:reduction.indicator>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:validity.end.date></oub:validity.end.date>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:justification.regulation.role></oub:justification.regulation.role>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:justification.regulation.id></oub:justification.regulation.id>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:geographical.area.sid></oub:geographical.area.sid>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:goods.nomenclature.sid></oub:goods.nomenclature.sid>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:additional.code.sid></oub:additional.code.sid>\n", "", $instance);
			$instance = str_replace("\t\t\t\t\t\t<oub:export.refund.nomenclature.sid></oub:export.refund.nomenclature.sid>\n", "", $instance);

			$instance = str_replace("[MEASURE_COMPONENTS]\n",					$measure->measure_components_xml, $instance);
			$instance = str_replace("[MEASURE_EXCLUDED_GEOGRAPHICAL_AREAS]\n",	$measure->measure_excluded_geographical_areas_xml, $instance);
			$instance = str_replace("[MEASURE_CONDITIONS]\n",					$measure->measure_conditions_xml, $instance);
			$instance = str_replace("[MEASURE_PARTIAL_TEMPORARY_STOPS]\n",		$measure->measure_partial_temporary_stops_xml, $instance);
			$instance = str_replace("[FOOTNOTE_ASSOCIATION_MEASURES]\n",		$measure->footnote_association_measures_xml, $instance);

			$ret .= $instance;
			//$message_id += 1;
			$last_transaction_id += 1;
		}
		return ($ret);
	}
}
?>