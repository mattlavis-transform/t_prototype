<?php
class quota_suspension_period
{
	// Class properties and methods go here
	public $workbasket_name				= "";
	public $quota_order_number_id       = "";
	public $description                 = "";
	public $suspension_period_start_day	= "";
	public $suspension_period_start_month	= "";
	public $suspension_period_start_year	= "";
	public $suspension_period_end_day     = "";
	public $suspension_period_end_month	= "";
	public $suspension_period_end_year	= "";

    public function __construct() {
        // Construction code goes here
	}

    function populate_from_cookies() {
        $this->quota_order_number_id   = get_cookie("quota_order_number_id");
        $this->sub_quota_order_number_id    = get_cookie("sub_quota_order_number_id");
	}

    function create() {
		global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
		$this->footnote_description_period_sid  = $application->get_next_footnote_description_period();
		#h1 ($this->footnote_description_period_sid);
		#exit();
		if ($this->validity_start_date == "") {
			$this->validity_start_date = Null;
		}
		if ($this->validity_end_date == "") {
			$this->validity_end_date = Null;
		}
		
		# Create the footnote record
		$sql = "INSERT INTO footnotes_oplog (footnote_id, footnote_type_id, 
		validity_start_date, validity_end_date, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6)";
		pg_prepare($conn, "create_footnote", $sql);
		$result = pg_execute($conn, "create_footnote", array($this->footnote_id, $this->footnote_type_id,
		$this->validity_start_date, $this->validity_end_date, $operation, $operation_date));

		# Create the footnote description period record
		$sql = "INSERT INTO footnote_description_periods_oplog (footnote_description_period_sid, footnote_id,
		footnote_type_id, validity_start_date, operation, operation_date)
		VALUES ($1, $2, $3, $4, $5, $6)";
		pg_prepare($conn, "create_footnote_description_period", $sql);
		$result = pg_execute($conn, "create_footnote_description_period", array($this->footnote_description_period_sid, $this->footnote_id,
		$this->footnote_type_id, $this->validity_start_date, $operation, $operation_date));

		# Create the footnote description record
		$sql = "INSERT INTO footnote_descriptions_oplog (footnote_description_period_sid, footnote_id,
		footnote_type_id, language_id, description, operation, operation_date)
		VALUES ($1, $2, $3, 'EN', $4, $5, $6)";
		pg_prepare($conn, "create_footnote_description", $sql);
		$result = pg_execute($conn, "create_footnote_description", array($this->footnote_description_period_sid, $this->footnote_id,
		$this->footnote_type_id, $this->description, $operation, $operation_date));
		#echo ($result);
		#exit();

	}





	function populate_from_db() {
		global $conn;
		$sql = "SELECT description, validity_start_date, validity_end_date, description
		FROM footnotes mt, footnote_descriptions mtd
		WHERE mt.footnote_id = mtd.footnote_id
		AND mt.footnote_id = $1 ";
		pg_prepare($conn, "get_footnote", $sql);
		$result = pg_execute($conn, "get_footnote", array($this->footnote_id));

		if ($result) {
            $row = pg_fetch_row($result);
        	$this->description  						= $row[0];
			$this->validity_start_date					= $row[1];
			$this->validity_start_day   				= date('d', strtotime($this->validity_start_date));
			$this->validity_start_month 				= date('m', strtotime($this->validity_start_date));
			$this->validity_start_year  				= date('Y', strtotime($this->validity_start_date));
			$this->validity_end_date					= $row[2];
			if ($this->validity_end_date == "") {
				$this->validity_end_day   					= "";
				$this->validity_end_month 					= "";
				$this->validity_end_year  					= "";
			} else {
				$this->validity_end_day   					= date('d', strtotime($this->validity_end_date));
				$this->validity_end_month 					= date('m', strtotime($this->validity_end_date));
				$this->validity_end_year  					= date('Y', strtotime($this->validity_end_date));
			}

			$this->footnote_heading					= "Edit measure type " . $this->footnote_id;
			$this->disable_footnote_id_field		= " disabled";

		}
	}


    public function clear_cookies() {
        setcookie("footnote_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_type_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("footnote_description", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_year", "", time() + (86400 * 30), "/");
	}

}

