<?php
class base_regulation
{
    // Class properties and methods go here
    public $base_regulation_id = "";
    public $regulation_type = "";
    public $validity_start_date = "";
    public $validity_start_date_day = "";
    public $validity_start_date_month = "";
    public $validity_start_date_year = "";
    public $validity_end_date = "";
    public $validity_end_date_day = "";
    public $validity_end_date_month = "";
    public $validity_end_date_year = "";
    public $regulation_group_id = "";
    public $information_text = "";
    public $public_identifier = "";
    public $trade_remedies_case = "";
    public $url = "";

    public function get_parameters()
    {
        global $application;
        global $error_handler;

        $this->measure_type_id = trim(get_querystring("base_regulation_id"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                $ret = $this->populate_from_db();
                if (!$ret) {
                    h1("An error has occurred - no such regulation");
                    die();
                }
            } else {
                $this->populate_from_cookies();
            }
        }
    }

    function validate_form()
    {
        global $application;
        $errors = array();
        $this->base_regulation_id = strtoupper(get_formvar("base_regulation_id", "", True));
        $this->regulation_group_id = get_formvar("regulation_group_id", "", True);
        $this->information_text = get_formvar("information_text", "", True);
        $this->public_identifier = get_formvar("public_identifier", "", True);
        $this->url = get_formvar("url", "", True);

        //prend($_REQUEST);

        $this->description = get_formvar("description", "", True);

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

        # Check on the base_regulation_id
        if (strlen($this->base_regulation_id) != 8) {
            array_push($errors, "base_regulation_id");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($application->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "base_regulation_exists");
            }
        }

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

        # Check on the public_identifier
        if ($this->public_identifier == "") {
            array_push($errors, "public_identifier");
        }


        # Check on the regulation_group_id
        if ($this->regulation_group_id == "") {
            array_push($errors, "regulation_group_id");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {
            if ($application->mode == "insert") {
                // Do create scripts
                $this->create();
            } else {
                // Do edit scripts
                $this->update();
            }
            $url = "./confirmation.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function create()
    {
        global $conn, $application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
        
        # Create the certificate record
        $sql = "INSERT INTO base_regulations_oplog (
            base_regulation_id, base_regulation_role, validity_start_date, community_code,
            regulation_group_id, replacement_indicator, stopped_flag, information_text,
            approved_flag, published_date, officialjournal_number, officialjournal_page,
            public_identifier, url, trade_remedies_case,
            operation, operation_date, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18)";
        pg_prepare($conn, "create_base_regulation", $sql);
        $result = pg_execute($conn, "create_base_regulation", array(
            $this->base_regulation_id, "1", $this->validity_start_date, 1, 
            $this->regulation_group_id, 0, 'f', $this->information_text,
            't', $this->validity_start_date, "1", 1,
            $this->public_identifier, $this->url, $this->trade_remedies_case,
            $operation, $operation_date, 'Awaiting approval'
        ));

        prend($this);

    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT * FROM base_regulations WHERE base_regulation_id = $1 AND base_regulation_role = 1";
        pg_prepare($conn, "base_regulation_exists", $sql);
        $result = pg_execute($conn, "base_regulation_exists", array($this->base_regulation_id));
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
            $this->validity_start_date = to_date_string($this->validity_start_date_day, $this->validity_start_date_month, $this->validity_start_date_year);
        }

        if (($this->validity_end_date_day == "") || ($this->validity_end_date_month == "") || ($this->validity_end_date_year == "")) {
            $this->validity_end_date = Null;
        } else {
            $this->validity_end_date = to_date_string($this->validity_end_date_day, $this->validity_end_date_month, $this->validity_end_date_year);
        }
    }


    public function set_properties($base_regulation_id)
    {
        $this->base_regulation_id = $base_regulation_id;
    }

    function insert($base_regulation_id, $information_text, $validity_start_date, $regulation_group_id)
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();

        $this->base_regulation_id = $base_regulation_id;
        $this->information_text = $information_text;
        $this->validity_start_date = $validity_start_date;
        $this->regulation_group_id = $regulation_group_id;
        $this->published_date = $validity_start_date;

        $errors = $this->conflict_check();
        if (count($errors) > 0) {
            /*foreach ($errors as $error) {
 h1 ($error);
 }
 exit();*/
            #h1 ("fail");
            return ($errors);
        } else {
            h1($this->base_regulation_id);
            # Insert the geographical area description period
            $sql = "INSERT INTO base_regulations_oplog
 (base_regulation_role, base_regulation_id, validity_start_date, validity_end_date, community_code, regulation_group_id, replacement_indicator,
 stopped_flag, information_text, approved_flag, published_date, officialjournal_number, officialjournal_page, effective_end_date,
 antidumping_regulation_role, related_antidumping_regulation_id, complete_abrogation_regulation_role, complete_abrogation_regulation_id,
 explicit_abrogation_regulation_role, explicit_abrogation_regulation_id, operation, operation_date)
 VALUES (
 1, $1, $3, Null, 1, $4, 0,
 False, $2, True, $7, '1', '1', Null,
 Null, Null, Null, Null,
 Null, Null, $5, $6)";

            pg_prepare($conn, "base_regulation_insert", $sql);
            pg_execute($conn, "base_regulation_insert", array(
                $this->base_regulation_id, $this->information_text,
                $this->validity_start_date, $this->regulation_group_id, $operation, $operation_date, $this->published_date
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

        $this->published_date = $this->validity_start_date;

        #h1 ("Update" . $this->base_regulation_id);
        #exit();
        $sql = "INSERT INTO base_regulations_oplog
 (base_regulation_role, base_regulation_id, validity_start_date, validity_end_date, community_code, regulation_group_id, replacement_indicator,
 stopped_flag, information_text, approved_flag, published_date, officialjournal_number, officialjournal_page, effective_end_date,
 antidumping_regulation_role, related_antidumping_regulation_id, complete_abrogation_regulation_role, complete_abrogation_regulation_id,
 explicit_abrogation_regulation_role, explicit_abrogation_regulation_id, operation, operation_date)
 VALUES (
 1, $1, $3, Null, 1, $4, 0,
 False, $2, True, $7, '1', '1', Null,
 Null, Null, Null, Null,
 Null, Null, $5, $6)";

        pg_prepare($conn, "base_regulation_insert", $sql);
        pg_execute($conn, "base_regulation_insert", array(
            $this->base_regulation_id, $this->information_text,
            $this->validity_start_date, $this->regulation_group_id, $operation, $operation_date, $this->published_date
        ));

        return (True);
    }

    function conflict_check()
    {
        global $conn;
        $errors = array();
        # Check that the regulation does not already exist
        $sql = "SELECT * FROM base_regulations WHERE base_regulation_id = $1";
        pg_prepare($conn, "base_regulation_conflict_check", $sql);
        $result = pg_execute($conn, "base_regulation_conflict_check", array($this->base_regulation_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                array_push($errors, "base_regulation_id conflict");
                #h1 ("err" . $this->base_regulation_id);
                #exit();
            }
        }


        return ($errors);
    }

    function get_latest_description()
    {
        global $conn;
        $sql = "SELECT gad.description
 FROM geographical_area_description_periods gadp, geographical_area_descriptions gad
 WHERE gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
 AND gad.geographical_area_id = $1
 ORDER BY gadp.validity_start_date DESC LIMIT 1";

        pg_prepare($conn, "get_latest_description", $sql);
        $result = pg_execute($conn, "get_latest_description", array($this->geographical_area_id));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[0];
        }
    }


    function populate_from_cookies()
    {
        $this->validity_start_date_day = get_cookie("base_regulation_validity_start_date_day");
        $this->validity_start_date_month = get_cookie("base_regulation_validity_start_date_month");
        $this->validity_start_date_year = get_cookie("base_regulation_validity_start_date_year");
        $this->base_regulation_id = strtoupper(get_cookie("base_regulation_base_regulation_id"));
        $this->information_text_name = get_cookie("base_regulation_information_text_name");
        $this->information_text_url = get_cookie("base_regulation_information_text_url");
        $this->information_text_primary = get_cookie("base_regulation_information_text_primary");
        $this->regulation_group_id = get_cookie("base_regulation_regulation_group_id");
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "SELECT validity_start_date, regulation_group_id, information_text FROM base_regulations WHERE base_regulation_id = $1";
        pg_prepare($conn, "get_specific_description", $sql);
        $result = pg_execute($conn, "get_specific_description", array($this->base_regulation_id));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->validity_start_date = $row[0];
            $this->regulation_group_id = $row[1];
            $this->information_text = $row[2];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            $this->split_information_text();
        }
    }

    function split_information_text()
    {
        $split = explode("|", $this->information_text);
        if (count($split) == 3) {
            $this->information_text_name = $split[0];
            $this->information_text_url = $split[1];
            $this->information_text_primary = $split[2];
        }
    }


    function clear_cookies()
    {
        setcookie("geographical_area_validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_description", "", time() + (86400 * 30), "/");
    }

    function validate()
    {
        global $conn;

        $this->base_regulation_id = trim($this->base_regulation_id);
        if (strlen($this->base_regulation_id) != 8) {
            $ret = false;
            return $ret;
        }

        $sql = "select base_regulation_id from base_regulations where base_regulation_id = $1
 and validity_end_date is null;";
        pg_prepare($conn, "validate_base_regulation", $sql);
        $result = pg_execute($conn, "validate_base_regulation", array($this->base_regulation_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $ret = true;
        } else {
            $ret = false;
        }
        return ($ret);
    }
}
