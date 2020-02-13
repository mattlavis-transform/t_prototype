<?php
class certificate_type
{
    // Class properties and methods go here
    public $certificate_type_code  = "";
    public $description             = "";
    public $id_disabled = "";
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $validity_start_date_string = "";
    public $validity_end_date_string = "";
    public $application_code = "";
    public $application_code_description = "";

    public function get_parameters()
    {
        global $error_handler, $application;
        $this->certificate_type_code = trim(get_querystring("certificate_type_code"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            h1($application->mode);
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                $ret = $this->populate_from_db();
                if (!$ret) {
                    h1("An error has occurred - no such certificate type");
                    die();
                }
                $this->get_version_control();
            } else {
                $this->populate_from_cookies();
            }
        }
    }

    public function clear_cookies()
    {
        setcookie("certificate_type_code", "", time() + (86400 * 30), "/");
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
        $this->certificate_type_code = get_cookie("certificate_type_code");
        $this->validity_start_date_day = get_cookie("validity_start_date_day");
        $this->validity_start_date_month = get_cookie("validity_start_date_month");
        $this->validity_start_date_year = get_cookie("validity_start_date_year");
        $this->validity_start_date_string = get_cookie("validity_start_date_string");

        $this->validity_end_date_day = get_cookie("validity_end_date_day");
        $this->validity_end_date_month = get_cookie("validity_end_date_month");
        $this->validity_end_date_year = get_cookie("validity_end_date_year");
        $this->validity_end_date_string = get_cookie("validity_end_date_string");

        $this->description = get_cookie("description");
        $this->id_disabled = false;
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "SELECT description, validity_start_date, validity_end_date
        FROM certificate_types act, certificate_type_descriptions actd
        WHERE act.certificate_type_code = actd.certificate_type_code
        AND act.certificate_type_code = $1";
        pg_prepare($conn, "get_certificate_type", $sql);
        $result = pg_execute($conn, "get_certificate_type", array($this->certificate_type_code));
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
            $this->id_disabled = true;

            return (true);
        } else {
            return (false);
        }
    }

    // Validate form
    function validate_form()
    {
        global $application;
        $errors = array();
        $this->certificate_type_code = strtoupper(get_formvar("certificate_type_code", "", True));
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
        if (strlen($this->certificate_type_code) != 1) {
            array_push($errors, "certificate_type_code");
        }
        # If we are creating, check that the measure type ID does not already exist
        if ($this->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "certificate_type_exists");
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

        //pre ($_REQUEST);
        //prend ($errors);

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
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

        # Create the certificate_type record
        $sql = "INSERT INTO certificate_types_oplog (
            certificate_type_code,
            validity_start_date, validity_end_date, operation, operation_date, workbasket_id, status
            )
            VALUES ($1, $2, $3, $4, $5, $6, $7)
            RETURNING oid;";

        pg_prepare($conn, "stmt_1", $sql);
        $result = pg_execute($conn, "stmt_1", array(
            $this->certificate_type_code,
            $this->validity_start_date, $this->validity_end_date,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "certificate_type", $status, $operation, $operation_date);

        // Then upate the certificate type record with oid of the workbasket item record
        $sql = "UPDATE certificate_types_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "stmt_2", $sql);
        $result = pg_execute($conn, "stmt_2", array(
            $workbasket_item_id, $oid
        ));
        
        # Create the certificate_type description record
        $sql = "INSERT INTO certificate_type_descriptions_oplog (
            certificate_type_code, language_id, description,
            operation, operation_date, workbasket_id, status, workbasket_item_id
            )
            VALUES ($1, 'EN', $2, $3, $4, $5, $6, $7)
            RETURNING oid;";

        pg_prepare($conn, "stmt_3", $sql);
        $result = pg_execute($conn, "stmt_3", array(
            $this->certificate_type_code, $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
    }

    public function get_version_control() {
        global $conn;
        $sql = "with cte as (select operation, operation_date,
        validity_start_date, validity_end_date, status, null as description, '0' as object_precedence
        from certificate_types_oplog ct
        where certificate_type_code = $1
        union
        select operation, operation_date,
        null as validity_start_date, null as validity_end_date, status, description, '1' as object_precedence
        from certificate_type_descriptions_oplog
        where certificate_type_code = $1)
        select operation, operation_date, validity_start_date, validity_end_date, status, description
        from cte order by operation_date desc, object_precedence desc;";
        $stmt = "stmt_1";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->certificate_type_code));
        if ($result) {
            $this->versions = $result;
            return;
            $row_count = pg_num_rows($result);
            if (($row_count > 0) && (pg_num_rows($result))) {
                while ($row = pg_fetch_array($result)) {
                    $version = new footnote_type();
                    $version->validity_start_date = $row["validity_start_date"];
                    $version->validity_end_date = $row["validity_start_date"];
                    $version->validity_start_date = $row["validity_start_date"];
                    $version->validity_start_date = $row["validity_start_date"];
                    array_push($this->versions, $version);
                }
            }
        }
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
        $sql = "SELECT certificate_type_code FROM certificate_types WHERE certificate_type_code = $1";
        pg_prepare($conn, "certificate_type_code_exists", $sql);
        $result = pg_execute($conn, "certificate_type_code_exists", array($this->certificate_type_code));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $exists = true;
            }
        }
        return ($exists);
    }
}
