<?php
class quota_definition
{
	// Class properties and methods go here
    public $quota_order_number_id               = "";
    public $quota_order_number_sid              = 0;
	public $validity_start_day                  = 0;
	public $validity_start_month                = 0;
	public $validity_start_year                 = 0;
	public $validity_start_date                 = "";
	public $validity_end_date                   = "";
	public $validity_end_day                    = 0;
	public $validity_end_month                  = 0;
	public $validity_end_year                   = 0;
    public $initial_volume                      = 0;
    public $measurement_unit_code               = "";
    public $measurement_unit_qualifier_code     = "";
    public $maximum_precision                   = 0;
    public $critical_state                      = "";
    public $critical_threshold                  = 0;
    public $monetary_unit_code                  = "";
    public $description                         = "";

    function insert($quota_order_number_id, $validity_start_date, $validity_end_date,
    $quota_order_number_sid, $initial_volume, $measurement_unit_code, $maximum_precision,
    $critical_state, $critical_threshold, $monetary_unit_code, $measurement_unit_qualifier_code, $description) {
        global $conn;
        $application = new application;
        $operation = "C";
        $quota_definition_sid  = $application->get_next_quota_definition();
        $operation_date = $application->get_operation_date();

        $this->quota_order_number_id = $quota_order_number_id;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;

        $errors = $this->conflict_check();
        #h1 (count($errors));
        #exit();
        if (count($errors) > 0) {
            /*foreach ($errors as $error) {
                h1 ($error);
            }
            exit();*/
            return ($errors);
        } else {
            $sql = "INSERT INTO quota_definitions_oplog
            (quota_definition_sid, quota_order_number_id, validity_start_date, validity_end_date, quota_order_number_sid, volume, initial_volume,
            measurement_unit_code, maximum_precision, critical_state, critical_threshold, monetary_unit_code, measurement_unit_qualifier_code,
            description, operation, operation_date)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16)";
            pg_prepare($conn, "quota_definition_insert", $sql);
            pg_execute($conn, "quota_definition_insert", array($quota_definition_sid, $quota_order_number_id, $validity_start_date, $validity_end_date,
            $quota_order_number_sid, $initial_volume, $initial_volume, $measurement_unit_code, $maximum_precision,
            $critical_state, $critical_threshold, $monetary_unit_code, $measurement_unit_qualifier_code, $description, $operation, $operation_date));
            return (True);
        }
    }

    function update($quota_definition_sid, $quota_order_number_id, $validity_start_date, $validity_end_date,
    $quota_order_number_sid, $initial_volume, $measurement_unit_code, $maximum_precision,
    $critical_state, $critical_threshold, $monetary_unit_code, $measurement_unit_qualifier_code, $description) {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $this->quota_definition_sid = $quota_definition_sid;
        $this->quota_order_number_id = $quota_order_number_id;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;

        $errors = $this->conflict_check($quota_definition_sid);
        #h1 (count($errors));
        #exit();
        if (count($errors) > 0) {
            /*foreach ($errors as $error) {
                h1 ($error);
            }
            exit();*/
            return ($errors);
        } else {
            $sql = "INSERT INTO quota_definitions_oplog
            (quota_definition_sid, quota_order_number_id, validity_start_date, validity_end_date, quota_order_number_sid, volume, initial_volume,
            measurement_unit_code, maximum_precision, critical_state, critical_threshold, monetary_unit_code, measurement_unit_qualifier_code,
            description, operation, operation_date)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16)";
            pg_prepare($conn, "quota_definition_insert", $sql);
            pg_execute($conn, "quota_definition_insert", array($quota_definition_sid, $quota_order_number_id, $validity_start_date, $validity_end_date,
            $quota_order_number_sid, $initial_volume, $initial_volume, $measurement_unit_code, $maximum_precision,
            $critical_state, $critical_threshold, $monetary_unit_code, $measurement_unit_qualifier_code, $description, $operation, $operation_date));
            return (True);
        }
    }

    function conflict_check($quota_definition_sid = -1) {
        global $conn;
        $errors = array();

        # First, check for items that start at the exact same start date, which is the real fail
        if ($quota_definition_sid != -1) {
            $sql = "SELECT * FROM quota_definitions WHERE quota_order_number_id = $1 and quota_definition_sid != $3 AND validity_start_date = $2";
            pg_prepare($conn, "quota_definition_conflict_check", $sql);
            $result = pg_execute($conn, "quota_definition_conflict_check", array($this->quota_order_number_id, $this->validity_start_date, $this->quota_definition_sid));
        } else {
            $sql = "SELECT * FROM quota_definitions WHERE quota_order_number_id = $1 AND validity_start_date = $2";
            pg_prepare($conn, "quota_definition_conflict_check", $sql);
            $result = pg_execute($conn, "quota_definition_conflict_check", array($this->quota_order_number_id, $this->validity_start_date));
        }
        if ($result) {
            if (pg_num_rows($result) > 0){
                array_push($errors, "Error scenario 1");
            }
        }

        # Second, check all definitions on this order number
        if ($quota_definition_sid != -1) {
            $sql = "SELECT * FROM quota_definitions WHERE quota_order_number_id = $1 and quota_definition_sid != $2 ORDER BY validity_start_date DESC";
            pg_prepare($conn, "quota_definition_conflict_check2", $sql);
            $result = pg_execute($conn, "quota_definition_conflict_check2", array($this->quota_order_number_id, $this->quota_definition_sid));      
        } else {
            $sql = "SELECT * FROM quota_definitions WHERE quota_order_number_id = $1 ORDER BY validity_start_date DESC";
            pg_prepare($conn, "quota_definition_conflict_check2", $sql);
            $result = pg_execute($conn, "quota_definition_conflict_check2", array($this->quota_order_number_id));      
        }
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $quota_definition_sid   = $row["quota_definition_sid"];
                $validity_start_date    = $row["validity_start_date"];
                $validity_end_date      = $row["validity_end_date"];
                # Check the four possible clash scenarios
                #p ("D1S: " . $this->validity_start_date . "<br>D1E: " . $this->validity_end_date . "<br>D2S: " . $validity_start_date . "<br>D2E: " . $validity_end_date);
                if (($this->validity_start_date <= $validity_end_date) && ($this->validity_start_date >= $validity_start_date)) {
                    array_push($errors, "Error scenario 2");
                    break;
                }
                if (($this->validity_start_date <= $validity_start_date) && ($this->validity_end_date >= $validity_start_date)) {
                    array_push($errors, "Error scenario 3");
                    break;
                }
            }
        }
        return ($errors);
    }

    function populate_from_db($quota_definition_sid) {
        global $conn;
        $this->$quota_definition_sid = $quota_definition_sid;
        $sql = "SELECT quota_definition_sid, quota_order_number_id, validity_start_date, validity_end_date, quota_order_number_sid,
        volume, initial_volume, measurement_unit_code, maximum_precision, critical_state, critical_threshold,
        monetary_unit_code, measurement_unit_qualifier_code, description FROM quota_definitions WHERE quota_definition_sid = " . $quota_definition_sid;
        $result = pg_query($conn, $sql);
        if  ($result) {
            while ($row = pg_fetch_array($result)) {

                $this->quota_definition_sid             = $row['quota_definition_sid'];
                $this->quota_order_number_id            = $row['quota_order_number_id'];
                $this->validity_start_date              = $row['validity_start_date'];
                $this->validity_end_date                = $row['validity_end_date'];
                $this->quota_order_number_sid           = $row['quota_order_number_sid'];
                $this->volume                           = $row['volume'];
                $this->initial_volume                   = $row['initial_volume'];
                $this->measurement_unit_code            = $row['measurement_unit_code'];
                $this->maximum_precision                = $row['maximum_precision'];
                $this->critical_state                   = $row['critical_state'];
                $this->critical_threshold               = $row['critical_threshold'];
                $this->monetary_unit_code               = $row['monetary_unit_code'];
                $this->measurement_unit_qualifier_code  = $row['measurement_unit_qualifier_code'];
                $this->description                      = $row['description'];

                $this->validity_start_day   = date('d', strtotime($this->validity_start_date));
                $this->validity_start_month = date('m', strtotime($this->validity_start_date));
                $this->validity_start_year  = date('Y', strtotime($this->validity_start_date));

                $this->validity_end_day   = date('d', strtotime($this->validity_end_date));
                $this->validity_end_month = date('m', strtotime($this->validity_end_date));
                $this->validity_end_year  = date('Y', strtotime($this->validity_end_date));
            }
        }

    }

    function populate_from_cookies(){
        $this->validity_start_day				= get_cookie("quota_definition_validity_start_day");
        $this->validity_start_month				= get_cookie("quota_definition_validity_start_month");
        $this->validity_start_year				= get_cookie("quota_definition_validity_start_year");
        $this->validity_end_day					= get_cookie("quota_definition_validity_end_day");
        $this->validity_end_month				= get_cookie("quota_definition_validity_end_month");
        $this->validity_end_year				= get_cookie("quota_definition_validity_end_year");
        $this->initial_volume					= get_cookie("quota_definition_initial_volume");
        $this->measurement_unit_code			= get_cookie("quota_definition_measurement_unit_code");
        $this->measurement_unit_qualifier_code	= get_cookie("quota_definition_measurement_unit_qualifier_code");
        $this->maximum_precision				= get_cookie("quota_definition_maximum_precision");
        $this->critical_state					= get_cookie("quota_definition_critical_state");
        $this->critical_threshold				= get_cookie("quota_definition_critical_threshold");
        $this->monetary_unit_code				= get_cookie("quota_definition_monetary_unit_code");
        $this->description						= get_cookie("quota_definition_description");

    }

    function clear_cookies(){
        setcookie("quota_definition_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_validity_end_day", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_validity_end_month", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_validity_end_year", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_initial_volume", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_measurement_unit_code", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_measurement_unit_qualifier_code", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_critical_state", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_critical_threshold", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_monetary_unit_code", "", time() + (86400 * 30), "/");
        setcookie("quota_definition_description", "", time() + (86400 * 30), "/");
    }

} 