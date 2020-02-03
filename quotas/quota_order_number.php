<?php
class quota_order_number
{
    // Class properties and methods go here
    public $quota_order_number_id = "";
    public $quota_order_number_sid = 0;
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $description = "";
    public $origin_quota = null;
    public $quota_scope = null;
    public $quota_staging = null;
    public $quota_mechanism = null;
    public $critical_threshold = null;
    public $monetary_unit_code = null;
    public $measurement_unit_code = null;
    public $commodity_codes = null;
    public $measure_generating_regulation_id = null;
    public $measurement_unit_qualifier_code = null;
    public $introductory_period_option = null;
    public $period_type = null;
    public $maximum_precision = null;
    public $year_count = null;
    public $duties_same_for_all_commodities = null;
    public $quota_category = "";
    public $origins = array();
    public $measure_types = array();

    #$quota_order_number_origin = new quota_order_number_origin;

    public function set_properties($quota_order_number_id, $validity_start_date, $validity_end_date)
    {
        $this->quota_order_number_id = $quota_order_number_id;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;
    }

    function validate_form_step1()
    {
        global $application;
        $errors = array();

        //prend($_REQUEST);

        $this->measure_generating_regulation_id = strtoupper(get_formvar("measure_generating_regulation_id", "", True));
        $hyphen_pos = strpos($this->measure_generating_regulation_id, "-");
        if ($hyphen_pos !== -1) {
            $this->measure_generating_regulation_id = trim(substr($this->measure_generating_regulation_id, 0, $hyphen_pos - 1));
        }
        $this->quota_category = get_formvar("quota_category", "", True);
        $this->quota_mechanism = get_formvar("quota_mechanism", "", True);
        $this->quota_order_number_id = get_formvar("quota_order_number_id", "", True);
        $this->quota_category = get_formvar("quota_category", "", True);
        $this->description = get_formvar("description", "", True);
        $this->geographical_area_id_countries = get_formvar("geographical_area_id_countries", "", True);
        $this->description = get_formvar("description", "", True);

        /*
        $this->validity_start_date_day = get_formvar("validity_start_date_day", "", True);
        $this->validity_start_date_month = get_formvar("validity_start_date_month", "", True);
        $this->validity_start_date_year = get_formvar("validity_start_date_year", "", True);
        $this->validity_start_date_string = $this->validity_start_date_day . "|" . $this->validity_start_date_month . "|" . $this->validity_start_date_year;
        setcookie("validity_start_date_string", $this->validity_start_date_string, time() + (86400 * 30), "/");

        $this->validity_end_date_day = get_formvar("validity_end_date_day", "", True);
        $this->validity_end_date_month = get_formvar("validity_end_date_month", "", True);
        $this->validity_end_date_year = get_formvar("validity_end_date_year", "", True);
        $this->validity_end_date_string = $this->validity_end_date_day . "|" . $this->validity_end_date_month . "|" . $this->validity_end_date_year;
        setcookie("validity_end_date_string", $this->validity_end_date_string, time() + (86400 * 30), "/");

        //$this->set_dates();
        */

        # Check on the measure_generating_regulation_id
        if (strlen($this->measure_generating_regulation_id) != 8) {
            array_push($errors, "measure_generating_regulation_id");
        }

        # Check on the quota_category
        if ($this->quota_category == "Unspecified") {
            array_push($errors, "quota_category");
        }

        # Check on the quota_mechanism
        if (empty($this->quota_mechanism)) {
            array_push($errors, "quota_mechanism");
        }

        # Check on the quota_order_number_id
        if (strlen($this->quota_order_number_id) != 6) {
            array_push($errors, "quota_order_number_id");
        }

        # If we are creating, check that the quota order number ID does not already exist
        if ($this->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "quota_order_number_exists");
            }
        }

        /*
        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if ($application->mode == "update") {
            if (($this->validity_end_date_day == "") && ($this->validity_end_date_month == "") && ($this->validity_end_date_year == "")) {
                $valid_end_date = 1;
            } else {
                $valid_end_date = checkdate($this->validity_end_date_month, $this->validity_end_date_day, $this->validity_end_date_year);
            }
            if ($valid_end_date != 1) {
                array_push($errors, "validity_end_date");
            }
        }
        */

        # Check on the description
        if (($this->description == "") || (strlen($this->description) > 400)) {
            array_push($errors, "description");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
 if ($create_edit == "create") {
 // Do create scripts
 $this->create();
 } else {
 // Do edit scripts
 $this->update();
 }*/
            $url = "./create_edit2.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function validate_form_step2()
    {
        global $application;
        $errors = array();

        $this->measure_generating_regulation_id = strtoupper(get_formvar("measure_generating_regulation_id", "", True));
        $hyphen_pos = strpos($this->measure_generating_regulation_id, "-");
        if ($hyphen_pos !== -1) {
            $this->measure_generating_regulation_id = trim(substr($this->measure_generating_regulation_id, 0, $hyphen_pos - 1));
        }
        $this->quota_category = get_formvar("quota_category", "", True);
        $this->quota_mechanism = get_formvar("quota_mechanism", "", True);
        $this->quota_order_number_id = get_formvar("quota_order_number_id", "", True);
        $this->quota_category = get_formvar("quota_category", "", True);
        $this->description = get_formvar("description", "", True);
        $this->geographical_area_id_countries = get_formvar("geographical_area_id_countries", "", True);
        $this->description = get_formvar("description", "", True);

        $this->quota_scope = get_formvar("quota_scope", "", True);
        $this->quota_staging = get_formvar("quota_staging", "", True);
        $this->origin_quota = get_formvar("origin_quota", "", True);

        # Check on the measure_generating_regulation_id
        if (strlen($this->origin_quota) == "") {
            array_push($errors, "origin_quota");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit2.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit3.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function validate_form_step3()
    {
        global $application;
        $errors = array();

        //pre($_REQUEST);

        $this->commodity_codes = trim(get_formvar("commodity_codes", "", True));
        $this->commodity_codes = str_replace("\n", " ", $this->commodity_codes);
        $this->commodity_codes = str_replace("\r", " ", $this->commodity_codes);
        $this->commodity_codes = trim(preg_replace('/\s\s+/', ' ', $this->commodity_codes));


        $parts = preg_split("@[\s+　]@u", trim($this->commodity_codes));
        h1(count($parts));
        $commodity_errors = false;

        foreach ($parts as $part) {
            if (!preg_match('/[0-9]{10}/', $part)) {
                $commodity_errors = true;
            }
        }

        # Check on the commodity_codes
        if ((strlen($this->commodity_codes) == "") || ($commodity_errors == true)) {
            array_push($errors, "commodity_codes");
        }


        # Check on the duties_same_for_all_commodities
        $this->duties_same_for_all_commodities = trim(get_formvar("duties_same_for_all_commodities", "", True));
        if (strlen($this->duties_same_for_all_commodities) == "") {
            array_push($errors, "duties_same_for_all_commodities");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit3.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit4.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }


    function validate_form_step4()
    {
        global $application;
        $errors = array();

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit4.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit5.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function validate_form_step5()
    {
        global $application;
        $errors = array();

        //prend ($_REQUEST);

        
        $this->measurement_unit_code = get_formvar("measurement_unit_code", "", True);
        $this->measurement_unit_qualifier_code = get_formvar("measurement_unit_qualifier_code", "", True);
        $this->maximum_precision = get_formvar("maximum_precision", "", True);
        $this->critical_threshold = intval(get_formvar("critical_threshold", "", True));

        if (($this->measurement_unit_code == "Unspecified") || ($this->measurement_unit_code == "")) {
            array_push($errors, "measurement_unit_code");
        }

        if (($this->maximum_precision == "Unspecified") || ($this->maximum_precision == "")) {
            array_push($errors, "maximum_precision");
        }

        if ($this->critical_threshold == "") {
            array_push($errors, "critical_threshold");
        }


        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit5.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit6.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }


    function validate_form_step6()
    {
        global $application;
        $errors = array();

        //prend ($_REQUEST);

        $this->period_type = get_formvar("period_type", "", True);
        if ($this->period_type == "") {
            array_push($errors, "period_type");
        }

        $this->year_count = get_formvar("year_count", "", True);
        if ($this->year_count == "") {
            array_push($errors, "year_count");
        }

        $this->introductory_period_option = get_formvar("introductory_period_option", "", True);
        if ($this->introductory_period_option == "") {
            array_push($errors, "introductory_period_option");
        }

                


        
        $this->validity_start_date_day = get_formvar("validity_start_date_day", "", True);
        $this->validity_start_date_month = get_formvar("validity_start_date_month", "", True);
        $this->validity_start_date_year = get_formvar("validity_start_date_year", "", True);
        $this->validity_start_date_string = $this->validity_start_date_day . "|" . $this->validity_start_date_month . "|" . $this->validity_start_date_year;
        setcookie("validity_start_date_string", $this->validity_start_date_string, time() + (86400 * 30), "/");

        $this->validity_end_date_day = get_formvar("validity_end_date_day", "", True);
        $this->validity_end_date_month = get_formvar("validity_end_date_month", "", True);
        $this->validity_end_date_year = get_formvar("validity_end_date_year", "", True);
        $this->validity_end_date_string = $this->validity_end_date_day . "|" . $this->validity_end_date_month . "|" . $this->validity_end_date_year;
        setcookie("validity_end_date_string", $this->validity_end_date_string, time() + (86400 * 30), "/");

        $this->set_dates();

        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        /*
        if ($application->mode == "update") {
            if (($this->validity_end_date_day == "") && ($this->validity_end_date_month == "") && ($this->validity_end_date_year == "")) {
                $valid_end_date = 1;
            } else {
                $valid_end_date = checkdate($this->validity_end_date_month, $this->validity_end_date_day, $this->validity_end_date_year);
            }
            if ($valid_end_date != 1) {
                array_push($errors, "validity_end_date");
            }
        }
        */

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit6.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {/*
            if ($create_edit == "create") {
            // Do create scripts
            $this->create();
            } else {
            // Do edit scripts
            $this->update();
            }*/
            $url = "./create_edit7.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT * FROM quota_order_numbers WHERE quota_order_number_id = $1 AND validity_end_date is not null";
        pg_prepare($conn, "quota_order_number_exists", $sql);
        $result = pg_execute($conn, "quota_order_number_exists", array($this->quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $exists = true;
            }
        }
        return ($exists);
    }

    function set_dates()
    {
        if (($this->validity_start_date_day == "") || ($this->validity_start_date_month == "") || ($this->validity_start_date_year == "")) {
            $this->validity_start_date = Null;
        } else {
            $this->validity_start_date    = to_date_string($this->validity_start_date_day,    $this->validity_start_date_month, $this->validity_start_date_year);
        }

        if (($this->validity_end_date_day == "") || ($this->validity_end_date_month == "") || ($this->validity_end_date_year == "")) {
            $this->validity_end_date = Null;
        } else {
            $this->validity_end_date    = to_date_string($this->validity_end_date_day, $this->validity_end_date_month, $this->validity_end_date_year);
        }
    }


    public function get_parameters($description = false)
    {
        global $application;
        global $error_handler;

        $this->quota_order_number_sid = trim(get_querystring("quota_order_number_sid"));
        $this->quota_order_number_id = trim(get_querystring("quota_order_number_id"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                if ($description == false) {
                    $ret = $this->populate_from_db();
                } else {
                    //$ret = $this->get_specific_description($this->validity_start_date);
                    $a = 1;
                }
                if (!$ret) {
                    h1("An error has occurred - no such quota order number");
                    die();
                }
            } else {
                $this->populate_from_cookies();
            }
        }
        //pre ($this);
    }


    public function clear_cookies()
    {
        setcookie("footnote_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_type_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("footnote_description", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_date_year", "", time() + (86400 * 30), "/");
    }


    function populate_from_db()
    {
        global $conn;
        $sql = "SELECT validity_start_date, validity_end_date, description, origin_quota, quota_scope, quota_category, quota_order_number_sid
        FROM quota_order_numbers WHERE quota_order_number_sid = $1
        order by validity_start_date desc limit 1";
        pg_prepare($conn, "quota_populate_from_db", $sql);
        $result = pg_execute($conn, "quota_populate_from_db", array($this->quota_order_number_sid));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $row = pg_fetch_row($result);
                $this->validity_start_date = $row[0];
                $this->validity_end_date = $row[1];
                $this->description = $row[2];
                $this->origin_quota = $row[3];
                $this->quota_scope = $row[4];
                $this->quota_category = $row[5];
                $this->quota_order_number_sid = $row[6];
                $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
                $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
                $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
                if ($this->validity_end_date != "") {
                    $this->validity_end_date_day = date('d', strtotime($this->validity_end_date));
                    $this->validity_end_date_month = date('m', strtotime($this->validity_end_date));
                    $this->validity_end_date_year = date('Y', strtotime($this->validity_end_date));
                } else {
                    $this->validity_end_date_day = "";
                    $this->validity_end_date_month = "";
                    $this->validity_end_date_year = "";
                }
            }
        }
    }

    function populate_from_cookies()
    {
        $this->validity_start_date_day = get_cookie("quota_order_number_validity_start_date_day");
        $this->validity_start_date_month = get_cookie("quota_order_number_goods_nomenclature_validity_start_date_month");
        $this->validity_start_date_year = get_cookie("quota_order_number_goods_nomenclature_validity_start_date_year");
        $this->validity_end_date_day = get_cookie("quota_order_number_goods_nomenclature_validity_end_date_day");
        $this->validity_end_date_month = get_cookie("quota_order_number_goods_nomenclature_validity_end_date_month");
        $this->validity_end_date_year = get_cookie("quota_order_number_goods_nomenclature_validity_end_date_year");
        $this->description = get_cookie("quota_order_number_goods_nomenclature_description");
        $this->quota_scope = get_cookie("quota_order_number_quota_scope");
        $this->quota_staging = get_cookie("quota_order_number_quota_staging");
        $this->origin_quota = get_cookie("quota_order_number_origin_quota");
    }

    function clear()
    {
        $this->validity_start_date_day = "";
        $this->validity_start_date_month = "";
        $this->validity_start_date_year = "";
        $this->validity_end_date_day = "";
        $this->validity_end_date_month = "";
        $this->validity_end_date_year = "";
        $this->description = "";
        $this->quota_scope = "";
        $this->quota_staging = "";
        $this->origin_quota = "";
    }


    function insert()
    {
        global $conn;
        $application = new application;
        $this->quota_order_number_sid = $application->get_next_quota_order_number();
        $operation = "C";
        $operation_date = $application->get_operation_date();

        $errors = $this->conflict_check();
        h1(count($errors));
        if (count($errors) > 0) {
            /*
 foreach ($errors as $error) {
 h1 ($error);
 }
 exit();
 */
            return ($errors);
        } else {
            $sql = "INSERT INTO quota_order_numbers_oplog
 (
 quota_order_number_sid,
 quota_order_number_id,
 validity_start_date,
 description,
 origin_quota,
 quota_scope,
 quota_staging,
 operation,
 operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
            pg_prepare($conn, "quota_order_number_insert", $sql);
            pg_execute($conn, "quota_order_number_insert", array(
                $this->quota_order_number_sid,
                $this->quota_order_number_id,
                $this->validity_start_date,
                $this->description,
                $this->origin_quota,
                $this->quota_scope,
                $this->quota_staging,
                $operation,
                $operation_date
            ));
            return (True);
        }
    }


    function update()
    {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $errors = array();
        $sql = "INSERT INTO quota_order_numbers_oplog
 (
 quota_order_number_sid,
 quota_order_number_id,
 validity_start_date,
 validity_end_date,
 description,
 origin_quota,
 quota_scope,
 quota_staging,
 operation,
 operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)";

        pg_prepare($conn, "quota_order_number_insert", $sql);

        pg_execute($conn, "quota_order_number_insert", array(
            $this->quota_order_number_sid,
            $this->quota_order_number_id,
            $this->validity_start_date,
            $this->validity_end_date,
            $this->description,
            $this->origin_quota,
            $this->quota_scope,
            $this->quota_staging,
            $operation,
            $operation_date
        ));
    }

    function get_measure_types()
    {
        $out = "";
        foreach ($this->measure_types as $m) {
            $out .= $m . ", ";
        }
        $out = trim($out);
        $out = trim($out, ",");
        return ($out);
    }

    function conflict_check()
    {
        global $conn;
        $errors = array();
        # First, check for items that exist already and have not been end-dated
        $sql = "SELECT * FROM quota_order_numbers WHERE quota_order_number_id = $1 AND validity_end_date IS NOT NULL";
        pg_prepare($conn, "quota_order_number_conflict_check", $sql);
        $result = pg_execute($conn, "quota_order_number_conflict_check", array($this->quota_order_number_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                array_push($errors, "Error scenario 1");
            }
        }
        return ($errors);
    }


    public function get_quota_order_number_sid()
    {
        global $conn;
        $sql = "SELECT quota_order_number_sid FROM quota_order_numbers
 WHERE quota_order_number_id = '" . $this->quota_order_number_id . "' ORDER BY validity_start_date DESC LIMIT 1";
        $result = pg_query($conn, $sql);
        if ($result) {
            $row = pg_fetch_row($result);
            while ($row = pg_fetch_array($result)) {
                $this->quota_order_number_sid = $row['quota_order_number_sid'];
            }
        }
    }

    public function get_quota_definitions()
    {
        global $conn;
        $sql = "select quota_definition_sid, validity_start_date, validity_end_date
 from quota_definitions
 where quota_order_number_id = '" . $this->quota_order_number_id . "'
 and validity_end_date > current_date";
        //echo ($sql);
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $quota_definition = new quota_definition;
                $quota_definition->quota_definition_sid = $row['quota_definition_sid'];
                $quota_definition->validity_start_date = $row['validity_start_date'];
                $quota_definition->validity_end_date = $row['validity_end_date'];
                array_push($temp, $quota_definition);
            }
            $this->quota_definitions = $temp;
        }
    }



    public function get_origins()
    {
        global $conn;
        # Get all the quota order number exclusions
        $sql = "SELECT qonoe.quota_order_number_origin_sid, excluded_geographical_area_sid, ga.description
 FROM quota_order_number_origin_exclusions qonoe, ml.ml_geographical_areas ga, quota_order_number_origins qono, quota_order_numbers qon
 WHERE qonoe.excluded_geographical_area_sid = ga.geographical_area_sid
 AND qono.quota_order_number_origin_sid = qonoe.quota_order_number_origin_sid
 AND qon.quota_order_number_sid = qono.quota_order_number_sid
 AND qon.quota_order_number_id = '" . $this->quota_order_number_id . "'";
        #p ($sql);
        $result = pg_query($conn, $sql);
        $quota_order_number_origin_exclusions = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $quota_order_number_origin_sid = $row['quota_order_number_origin_sid'];
                $excluded_geographical_area_sid = $row['excluded_geographical_area_sid'];
                $description = $row['description'];
                $qonoe = new quota_order_number_origin_exclusion;
                $qonoe->set_properties($quota_order_number_origin_sid, $excluded_geographical_area_sid, $description);
                array_push($quota_order_number_origin_exclusions, $qonoe);
            }
        }

        # Get the complete list of quota order number origins
        $sql = "SELECT qono.quota_order_number_origin_sid, qono.quota_order_number_sid, qono.geographical_area_id,
 ga.description, qon.quota_order_number_id, qono.validity_start_date, qono.validity_end_date
 FROM quota_order_number_origins qono, ml.ml_geographical_areas ga, quota_order_numbers qon
 WHERE ga.geographical_area_id = qono.geographical_area_id
 AND qon.quota_order_number_sid = qono.quota_order_number_sid
 AND (qono.validity_end_date IS NULL OR qono.validity_end_date > CURRENT_DATE)
 AND quota_order_number_id = '" . $this->quota_order_number_id . "'
 ORDER BY qono.validity_start_date desc, qono.quota_order_number_sid, ga.description";
        #p ($sql);

        $result = pg_query($conn, $sql);
        $quota_order_number_origins = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $geographical_area_id = $row['geographical_area_id'];
                $quota_order_number_origin_sid = $row['quota_order_number_origin_sid'];
                $quota_order_number_sid = $row['quota_order_number_sid'];
                $quota_order_number_id = $row['quota_order_number_id'];
                $description = $row['description'];
                $validity_start_date = $row['validity_start_date'];
                $validity_end_date = $row['validity_end_date'];
                $qono = new quota_order_number_origin;
                $qono->set_properties($quota_order_number_origin_sid, $geographical_area_id, $quota_order_number_id, $quota_order_number_sid, $description);
                $qono->validity_start_date = $validity_start_date;
                $qono->validity_end_date = $validity_end_date;
                $qonoe_count = count($quota_order_number_origin_exclusions);
                for ($i = 0; $i < $qonoe_count; $i++) {
                    $t = $quota_order_number_origin_exclusions[$i];
                    if ($t->quota_order_number_origin_sid == $quota_order_number_origin_sid) {
                        array_push($qono->exclusions, $t);
                        #p ("Adding exclusion");
                    }
                }
                array_push($quota_order_number_origins, $qono);
            }
        }
        $this->origins = $quota_order_number_origins;
    }

    function validate_fcfs_order_number()
    {
        global $conn;

        $this->quota_order_number_id = trim($this->quota_order_number_id);
        if (strlen($this->quota_order_number_id) != 6) {
            $ret = false;
            return $ret;
        }
        if (substr($this->quota_order_number_id, 0, 3) == "094") {
            $ret = false;
            return $ret;
        }
        $sql = "select quota_order_number_sid from quota_order_numbers
        where validity_end_date is null and quota_order_number_id = $1
        order by validity_start_date desc;";
        pg_prepare($conn, "validate_fcfs_order_number", $sql);
        $result = pg_execute($conn, "validate_fcfs_order_number", array($this->quota_order_number_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $ret = true;
        } else {
            $ret = false;
        }
        return ($ret);
    }


    public function get_commodity_code_array()
    {
        $this->commodity_code_list = array();
        $ccs = $this->commodity_codes;

        $ccs = str_replace("\n", " ", $ccs);
        $ccs = str_replace("\r", " ", $ccs);


        $parts = preg_split('/\s+/', $ccs);

        foreach ($parts as $cc) {
            $gn = new goods_nomenclature;
            $gn->goods_nomenclature_item_id = $cc;
            array_push($this->commodity_code_list, $gn);
        }
    }
}
