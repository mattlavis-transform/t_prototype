<?php
class additional_code_type
{
    // Class properties and methods go here
    public $additional_code_type_id  = "";
    public $description             = "";
    public $id_disabled = "";
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $validity_start_date_string = "";
    public $validity_end_date_string = "";
    public $application_code = "";
    public $application_code_description = "";
    public $measure_types = array();

    public function __construct()
    {
        $this->application_codes = array();
        array_push($this->application_codes, new simple_object("0", "Export refund nomenclature", "ERN"));
        array_push($this->application_codes, new simple_object("1", "Additional codes", "Additional codes"));
        array_push($this->application_codes, new simple_object("3", "Meursing additional codes", "Meursing"));
        array_push($this->application_codes, new simple_object("4", "Export refund for processed agricultural goods", "ERN (agri)"));
    }

    public function get_parameters()
    {
        global $error_handler;
        $this->additional_code_type_id = trim(get_querystring("additional_code_type_id"));
        $this->mode = trim(get_querystring("mode"));
        if ($this->mode == "") {
            $this->mode = "insert";
        }

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($this->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                $ret = $this->populate_from_db();
                if (!$ret) {
                    h1("An error has occurred - no such additional code type");
                    die();
                }
                $this->get_version_control();
            } else {
                $this->populate_from_cookies();
            }
        }
    }

    public function get_version_control() {
        global $conn;
        $sql = "with cte as (select operation, operation_date,
        validity_start_date, validity_end_date, status, null as description, '0' as object_precedence
        from additional_code_types_oplog
        where additional_code_type_id = $1
        union
        select operation, operation_date,
        null as validity_start_date, null as validity_end_date, status, description, '1' as object_precedence
        from additional_code_type_descriptions_oplog
        where additional_code_type_id = $1)
        select operation, operation_date, validity_start_date, validity_end_date, status, description
        from cte order by operation_date desc, object_precedence desc;";
        $stmt = "stmt_1";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->additional_code_type_id));
        if ($result) {
            $this->versions = $result;
            return;
        }
    }

    public function clear_cookies()
    {
        setcookie("additional_code_type_id", "", time() + (86400 * 30), "/");
        setcookie("description", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_string", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_year", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_string", "", time() + (86400 * 30), "/");
        setcookie("application_code", "", time() + (86400 * 30), "/");
    }

    function populate_from_cookies()
    {
        //h1 ("Populating from cookies");
        $this->additional_code_type_id = get_cookie("additional_code_type_id");
        $this->validity_start_date_day = get_cookie("validity_start_date_day");
        $this->validity_start_date_month = get_cookie("validity_start_date_month");
        $this->validity_start_date_year = get_cookie("validity_start_date_year");
        $this->validity_start_date_string = get_cookie("validity_start_date_string");

        $this->validity_end_date_day = get_cookie("validity_end_date_day");
        $this->validity_end_date_month = get_cookie("validity_end_date_month");
        $this->validity_end_date_year = get_cookie("validity_end_date_year");
        $this->validity_end_date_string = get_cookie("validity_end_date_string");

        $this->description = get_cookie("description");
        $this->application_code = get_cookie("application_code");
        $this->id_disabled = false;

    }

    function populate_from_db()
    {
        global $conn;
        $sql = "SELECT description, validity_start_date, validity_end_date, application_code
        FROM additional_code_types act, additional_code_type_descriptions actd
        WHERE act.additional_code_type_id = actd.additional_code_type_id
        AND act.additional_code_type_id = $1";
        pg_prepare($conn, "get_additional_code_type", $sql);
        $result = pg_execute($conn, "get_additional_code_type", array($this->additional_code_type_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->description = $row[0];
            $this->validity_start_date = $row[1];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            $this->validity_start_date_string = $this->validity_start_date_day . "|" . $this->validity_start_date_month . "|" . $this->validity_start_date_year;

            $this->validity_end_date = $row[2];
            if ($this->validity_end_date == "") {
                $this->validity_end_date_day = "";
                $this->validity_end_date_month = "";
                $this->validity_end_date_year = "";
                $this->validity_end_date_string = "";
            } else {
                $this->validity_end_date_day = date('d', strtotime($this->validity_end_date));
                $this->validity_end_date_month = date('m', strtotime($this->validity_end_date));
                $this->validity_end_date_year = date('Y', strtotime($this->validity_end_date));
                $this->validity_end_date_string = $this->validity_end_date_day . "|" . $this->validity_end_date_month . "|" . $this->validity_end_date_year;
            }
            $this->application_code = $row[3];
            $this->id_disabled = true;
            $this->get_measure_types();
            $this->get_descriptive_fields();
            return (true);
        } else {
            return (false);
        }
    }

    // Get measure types
    function get_measure_types()
    {
        global $conn;
        $sql = "select actmt.measure_type_id, description, validity_start_date, validity_end_date
        from additional_code_type_measure_types actmt, measure_type_descriptions mtd
        where actmt.measure_type_id = mtd.measure_type_id
        and additional_code_type_id = $1 order by 1;";
        pg_prepare($conn, "get_measure_types", $sql);
        $result = pg_execute($conn, "get_measure_types", array($this->additional_code_type_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $temp = new measure_type;
                $temp->measure_type_id = $row[0];
                $temp->description = $row[1];
                $temp->validity_start_date = $row[2];
                $temp->validity_end_date = $row[3];
                
                array_push($this->measure_types, $temp);
            }
        }
        //pre($this->measure_types);
    }

    // Validate form
    function validate_form()
    {
        global $application;
        $errors = array();
        $this->additional_code_type_id = get_formvar("additional_code_type_id", "", True);
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

        $this->application_code = get_formvar("application_code", "", True);
        $this->mode = get_formvar("mode");
        $this->set_dates();

        # Check on the measure type series id
        if (strlen($this->additional_code_type_id) != 1) {
            array_push($errors, "additional_code_type_id");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($this->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "additional_code_type_exists");
            }
        }

        # Check on the description
        if (($this->description == "") || (strlen($this->description) > 500)) {
            array_push($errors, "description");
        }

        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        if (($this->validity_end_date_day == "") && ($this->validity_end_date_month == "") && ($this->validity_end_date_year == "")) {
            $valid_end_date = 1;
        } else {
            $valid_end_date = checkdate($this->validity_end_date_month, $this->validity_end_date_day, $this->validity_end_date_year);
        }
        if ($valid_end_date != 1) {
            array_push($errors, "validity_end_date");
        }

        # Check on the application code
        if ($this->application_code == "") {
            array_push($errors, "application_code");
        }


        /*
 # Check business rules
 # If we are setting an end date on a measure type, there must be no measures of this type that extend beyond
 # the newly-set end date
 $this->set_dates();
 if ($this->validity_end_date != Null) {
 if ($this->business_rule_mt3() == false) {
 array_push($errors, "validity_end_date_mt3");
 }
 }

 */
        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $this->mode . "&measure_type_id=" . $this->measure_type_id;
        } else {
            if ($application->mode == "insert") {
                // Do create scripts
                $this->create_update("C");
            } else {
                // Do edit scripts
                $this->create_update("U");
            }
            $url = "./confirmation.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    function create_update($operation)
    {
        global $conn, $application;
        $operation_date = $application->get_operation_date();

        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }

        $status = 'awaiting approval';
        # Create the additional_code_type record
        $sql = "INSERT INTO additional_code_types_oplog (
            additional_code_type_id, application_code,
            validity_start_date, validity_end_date, operation, operation_date, workbasket_id, status
            )
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
            RETURNING oid;";

        pg_prepare($conn, "stmt_1", $sql);
        $result = pg_execute($conn, "stmt_1", array(
            $this->additional_code_type_id, $this->application_code,
            $this->validity_start_date, $this->validity_end_date,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "additional_code_type", $status, $operation, $operation_date);

        // Then upate the additional code type record with oid of the workbasket item record
        $sql = "UPDATE additional_code_types_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "stmt_2", $sql);
        $result = pg_execute($conn, "stmt_2", array(
            $workbasket_item_id, $oid
        ));
 
        # Create the additional_code_type description record
        $sql = "INSERT INTO additional_code_type_descriptions_oplog (
            additional_code_type_id, language_id, description,
            operation, operation_date, workbasket_id, status, workbasket_item_id
            )
            VALUES ($1, 'EN', $2, $3, $4, $5, $6, $7)
            RETURNING oid;";

        pg_prepare($conn, "stmt_3", $sql);
        $result = pg_execute($conn, "stmt_3", array(
            $this->additional_code_type_id, $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
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

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT additional_code_type_id FROM additional_code_types WHERE additional_code_type_id = $1";
        pg_prepare($conn, "measure_type_series_exists", $sql);
        $result = pg_execute($conn, "measure_type_series_exists", array($this->additional_code_type_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $exists = true;
            }
        }
        return ($exists);
    }

    public function get_descriptive_fields()
    {
        // Application code
        foreach ($this->application_codes as $item) {
            if ($item->id == $this->application_code) {
                $this->application_code_description = $item->string;
            }
        }
    }
}
