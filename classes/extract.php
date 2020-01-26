<?php

class extract
{
	public $extract_string;
	public $filename;

	public function __construct() {
		$this->envelope_id		= "200000000000000";
	}

	public function create_subclasses() {
		$this->extract_footnotes							= new extract_footnotes();								// 200 00
		$this->extract_certificates							= new extract_certificates();							// 205 00
		$this->extract_measure_types						= new extract_measure_types();							// 235 00
		$this->extract_geographical_area_descriptions		= new extract_geographical_area_descriptions();			// 250 00
		$this->extract_geographical_area_memberships		= new extract_geographical_area_memberships();			// 250 15
		$this->extract_base_regulations						= new extract_base_regulations();						// 285 00
		$this->extract_quota_definitions					= new extract_quota_definitions();						// 370 00
		$this->extract_measures								= new extract_measures();								// 430 00
		$this->extract_measure_components					= new extract_measure_components();						// 430 05
		$this->extract_measure_excluded_geographical_areas	= new extract_measure_excluded_geographical_areas();	// 430 15
	}


	function extract_data() {
		global $extracted_measure_list;
		$extracted_measure_list = array();
		$this->extract_string = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
		$this->extract_string .= '<env:envelope xmlns="urn:publicid:-:DGTAXUD:TARIC:MESSAGE:1.0" xmlns:env="urn:publicid:-:DGTAXUD:GENERAL:ENVELOPE:1.0" id="' . $this->envelope_id . '">' . "\r\n";
		$this->extract_string .= $this->extract_footnotes->extract();							// 200 00
		$this->extract_string .= $this->extract_certificates->extract();						// 205 00
		$this->extract_string .= $this->extract_measure_types->extract();						// 235 00
		$this->extract_string .= $this->extract_geographical_area_descriptions->extract();		// 250 00
		$this->extract_string .= $this->extract_geographical_area_memberships->extract();		// 250 15
		$this->extract_string .= $this->extract_base_regulations->extract();					// 285 00
		$this->extract_string .= $this->extract_quota_definitions->extract();					// 370 00
		$this->extract_string .= $this->extract_measures->extract();							// 430 00
		$this->extract_string .= $this->extract_measure_components->extract();					// 430 05
		$this->extract_string .= $this->extract_measure_excluded_geographical_areas->extract();	// 430 15
		
		$this->extract_string .= "</env:envelope>";
		$this->write_data();
		return;
		/*
		$this->extract_string .= $this->extract_certificates();
		*/
	}

	public function set_parameters() {
		global $conn, $message_id, $last_transaction_id, $last_exported_operation_date;
		$message_id = 1;

		/*
		$last_exported_operation_date = '2019-10-25 08:00:00';
		$last_transaction_id				= 1;
		*/

		$sql = "SELECT last_exported_operation_date, last_transaction_id FROM ml.config";
		$result = pg_query($conn, $sql);
		if ($result) {
			if (pg_num_rows($result) > 0){
				$row = pg_fetch_row($result);
				$last_exported_operation_date	= $row[0];
				$last_transaction_id			= $row[1];
			} else {
				$sql = "INSERT INTO ml.config (last_exported_operation_date, last_transaction_id) VALUES ($1, $2)";
				$operation_date = "2019-03-01";
				$last_transaction_id = 500000;
				pg_prepare($conn, "last_exported_operation_date_insert", $sql);
				pg_execute($conn, "last_exported_operation_date_insert", array($operation_date, $last_transaction_id));
				$last_exported_operation_date = $operation_date;
				$last_transaction_id          = $last_transaction_id;
			}
		}
	}





	function extract_footnote_descriptions() {
		global $conn;
		$ret = "";
		$template = file_get_contents('../templates/footnote.description.update.xml', true);
		$sql = "SELECT fd.footnote_description_period_sid, fd.footnote_type_id, fd.footnote_id,
		fd.description, fdp.operation as fdp_operation, fd.operation as fd_operation, fdp.validity_start_date
		FROM footnote_descriptions_oplog fd, footnote_description_periods_oplog fdp
		WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
		AND fd.operation_date = fdp.operation_date
		AND fd.operation_date > $1 ORDER BY fd.operation_date";
		
		pg_prepare($conn, "extract_footnote_descriptions", $sql);
		$result = pg_execute($conn, "extract_footnote_descriptions", array($last_exported_operation_date));
		
		if ($result) {
			if (pg_num_rows($result) > 0){
				while ($row = pg_fetch_array($result)) {
					$instance                           = $template;
					$footnote_description_period_sid    = $row["footnote_description_period_sid"];
					$footnote_type_id                   = $row["footnote_type_id"];
					$footnote_id                        = $row["footnote_id"];
					$description                        = $row["description"];
					$validity_start_date                = $this->xml_date($row["validity_start_date"]);
					$fd_operation                       = get_operation($row["fd_operation"]);
					$fdp_operation						= get_operation($row["fdp_operation"]);

					$instance = str_replace("[TRANSACTION_ID]",						$this->last_transaction_id, $instance);
					$instance = str_replace("[MESSAGE_ID1]",						$this->message_id, $instance);
					$instance = str_replace("[MESSAGE_ID2]",						$this->message_id + 1, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER1]",			$this->message_id, $instance);
					$instance = str_replace("[RECORD_SEQUENCE_NUMBER2]",			$this->message_id + 1, $instance);
					$instance = str_replace("[FDP_OPERATION]",						$fdp_operation, $instance);
					$instance = str_replace("[FD_OPERATION]",						$fd_operation, $instance);
					$instance = str_replace("[FOOTNOTE_DESCRIPTION_PERIOD_SID]",	$footnote_description_period_sid, $instance);
					$instance = str_replace("[VALIDITY_START_DATE]",				$validity_start_date, $instance);
					$instance = str_replace("[FOOTNOTE_TYPE_ID]",					$footnote_type_id, $instance);
					$instance = str_replace("[FOOTNOTE_ID]",						$footnote_id, $instance);
					$instance = str_replace("[DESCRIPTION]",						$description, $instance);
					$ret .= $instance;
					$this->message_id += 2;
					$this->last_transaction_id += 1;
				}
			}
		}
		return ($ret);
	}



	function xml_date($s) {
		$array = explode(" ", $s);
		$s = $array[0];
		return ($s);
	}


	function validate_data() {
		# Do not believe this is used
		$validator = new XmlValidator;
		$validated = $validator->validateFeeds('../extracts/newfile.xml');
		if ($validated) {
		  echo "Feed successfully validated";
		} else {
		  print_r($validator->displayErrors());
		}        
	}

	function write_data() {
		global $http_host, $last_exported_operation_date;

		$xml = new DOMDocument();
		$opdate = str_replace(":", "-", $last_exported_operation_date);
		$opdate = str_replace(" ", "-", $opdate);
		$filename = "../extracts/" . $http_host . "_since_" . $opdate . ".xml";
		#$filename = "../extracts/newfile2.xml";

		$xml->loadXML($this->extract_string, LIBXML_NOBLANKS);
		$myfile = fopen($filename, "w") or die("Unable to open file!");
		fwrite($myfile, $this->extract_string);
		fclose($myfile);
		error_reporting(E_ALL ^ E_WARNING);
		if (!$xml->schemaValidate("../xsd/envelope.xsd")) {
			// You have an error in the XML file
			H1 ("The file has not validated - please fix!");
			prex ($this->extract_string);
			exit();
		} else {
			H1 ("The file has validated ");
			prex ($this->extract_string);
			/*
			$last_exported_operation_date = date("Y-m-d h:i:s");
			$this->update_last_exported_operation_date();
			*/
			#h1 (date("Y-m-d h:i:s"));

		}
		error_reporting(E_ALL);
	}

	function update_last_exported_operation_date(){
		global $conn, $last_exported_operation_date;
		$sql = "UPDATE ml.config SET last_exported_operation_date = $1";
		pg_prepare($conn, "update_last_exported_operation_date", $sql);
		$result = pg_execute($conn, "update_last_exported_operation_date", array($last_exported_operation_date));
		#h1 (date("Y-m-d h:i:s"));
	}
}