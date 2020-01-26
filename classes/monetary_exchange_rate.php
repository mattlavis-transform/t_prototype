<?php
class monetary_exchange_rate
{
	// Class properties and methods go here
	public $measure_type_series_id          = "";
	public $exchange_rate                   = "";
	public $validity_start_date             = "";
	public $validity_end_date               = "";
	public $monetary_exchange_period_sid    = -1;

	function populate_from_db() {
		#h1 ("Here");
		global $conn;
		$sql = "SELECT validity_start_date, validity_end_date, exchange_rate
		FROM monetary_exchange_periods mep, monetary_exchange_rates mer
		WHERE mep.monetary_exchange_period_sid = mer.monetary_exchange_period_sid
		AND mep.monetary_exchange_period_sid = $1
		AND child_monetary_unit_code = 'GBP'";
		pg_prepare($conn, "get_mer", $sql);
		$result = pg_execute($conn, "get_mer", array($this->monetary_exchange_period_sid));

		if ($result) {
			$row = pg_fetch_row($result);
			$this->validity_start_date	= $row[0];
			$this->validity_start_day   = date('d', strtotime($this->validity_start_date));
			$this->validity_start_month = date('m', strtotime($this->validity_start_date));
			$this->validity_start_year  = date('Y', strtotime($this->validity_start_date));

			$this->validity_end_date	= $row[1];
			if ($this->validity_end_date == "") {
				$this->validity_end_day   = "";
				$this->validity_end_month = "";
				$this->validity_end_year  = "";
			} else {
				$this->validity_end_day   = date('d', strtotime($this->validity_end_date));
				$this->validity_end_month = date('m', strtotime($this->validity_end_date));
				$this->validity_end_year  = date('Y', strtotime($this->validity_end_date));
			}
			$this->exchange_rate	= $row[2];

		}
	}

	function create() {
		global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
		$this->monetary_exchange_period_sid  = $application->get_next_monetary_exchange_period();
		if ($this->validity_start_date == "") {
			$this->validity_start_date = Null;
		}
		if ($this->validity_end_date == "") {
			$this->validity_end_date = Null;
		}

		$my_date = db_to_date($this->validity_start_date);
		#h1 ($this->validity_start_date);
		$my_date->modify('-1 day');
		$my_date_string = $my_date->format("Y-m-d");
		#h1 ($my_date_string);
		#exit();
		# First we need to close off the previous exchange rate, which is likely to have been un-ended
		# Get the existing values first
		$sql = "SELECT monetary_exchange_period_sid, parent_monetary_unit_code, validity_start_date
		FROM monetary_exchange_periods
		WHERE validity_end_date IS NULL AND parent_monetary_unit_code = $1
		ORDER BY validity_start_date DESC LIMIT 1";

		pg_prepare($conn, "get_unended_period", $sql);
		$result = pg_execute($conn, "get_unended_period", array('EUR'));
		
		if ($result) {
            if (pg_num_rows($result) > 0){
				$t = new monetary_exchange_rate;
				while ($row = pg_fetch_array($result)) {
					$t->monetary_exchange_period_sid	= $row[0];
					$t->parent_monetary_unit_code		= $row[1];
					$t->validity_start_date				= $row[2];
					$t->validity_end_date				= $my_date_string;
				}
			}
		}

		# Then reinsert them with an end date which is one day before the start date of the next item
		$sql = "INSERT INTO monetary_exchange_periods_oplog (monetary_exchange_period_sid,
		parent_monetary_unit_code, validity_start_date,
		validity_end_date, operation, operation_date)
		VALUES ($1, 'EUR', $2, $3, 'U', $4)";

		pg_prepare($conn, "end_period", $sql);
		$result = pg_execute($conn, "end_period", array($t->monetary_exchange_period_sid,
		$t->validity_start_date, $t->validity_end_date, $operation_date));
		
		
		# Then insert the new period
		$sql = "INSERT INTO monetary_exchange_periods_oplog (monetary_exchange_period_sid,
		parent_monetary_unit_code, validity_start_date,
		validity_end_date, operation, operation_date)
		VALUES ($1, 'EUR', $2, $3, $4, $5)";

		pg_prepare($conn, "create_monetary_exchange_periods", $sql);
		$result = pg_execute($conn, "create_monetary_exchange_periods", array($this->monetary_exchange_period_sid,
		$this->validity_start_date, $this->validity_end_date, $operation, $operation_date));

		# And finally the new rate
		$sql = "INSERT INTO monetary_exchange_rates_oplog (monetary_exchange_period_sid,
		child_monetary_unit_code, exchange_rate, operation, operation_date)
		VALUES ($1, 'GBP', $2, $3, $4)";

		pg_prepare($conn, "create_monetary_exchange_rate", $sql);
		$result = pg_execute($conn, "create_monetary_exchange_rate", array($this->monetary_exchange_period_sid, $this->exchange_rate,
		$operation, $operation_date));
	}

	function update() {
		global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();
		if ($this->validity_start_date == "") {
			$this->validity_start_date = Null;
		}
		if ($this->validity_end_date == "") {
			$this->validity_end_date = Null;
		}


		# And finally the new rate
		$sql = "INSERT INTO monetary_exchange_rates_oplog (monetary_exchange_period_sid,
		child_monetary_unit_code, exchange_rate, operation, operation_date)
		VALUES ($1, 'GBP', $2, $3, $4)";

		pg_prepare($conn, "create_monetary_exchange_rate", $sql);
		$result = pg_execute($conn, "create_monetary_exchange_rate", array($this->monetary_exchange_period_sid, $this->exchange_rate,
		$operation, $operation_date));
	}



    function populate_from_cookies() {
        $this->validity_start_day				= get_cookie("monetary_exchange_rate_validity_start_day");
        $this->validity_start_month				= get_cookie("monetary_exchange_rate_validity_start_month");
        $this->validity_start_year				= get_cookie("monetary_exchange_rate_validity_start_year");
        $this->validity_end_day					= get_cookie("monetary_exchange_rate_validity_end_day");
        $this->validity_end_month				= get_cookie("monetary_exchange_rate_validity_end_month");
        $this->validity_end_year				= get_cookie("monetary_exchange_rate_validity_end_year");
        $this->description						= get_cookie("monetary_exchange_rate_description");
	}

    public function clear_cookies() {
        setcookie("monetary_exchange_rate_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("monetary_exchange_rate_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("monetary_exchange_rate_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("monetary_exchange_rate_validity_end_day", "", time() + (86400 * 30), "/");
        setcookie("monetary_exchange_rate_validity_end_month", "", time() + (86400 * 30), "/");
        setcookie("monetary_exchange_rate_validity_end_year", "", time() + (86400 * 30), "/");
	}

	function set_dates(){
		if (($this->validity_start_day == "") || ($this->validity_start_month == "") || ($this->validity_start_year == "")) {
			$this->validity_start_date = Null;
		} else {
			$this->validity_start_date	= to_date_string($this->validity_start_day,	$this->validity_start_month, $this->validity_start_year);
		}
		
		if (($this->validity_end_day == "") || ($this->validity_end_month == "") || ($this->validity_end_year == "")) {
			$this->validity_end_date = Null;
		} else {
			$this->validity_end_date	= to_date_string($this->validity_end_day, $this->validity_end_month, $this->validity_end_year);
		}
	}
}