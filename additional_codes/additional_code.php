<?php
class additional_code
{
    // Class properties and methods go here
    public $additional_code = "";
    public $additional_code_type_id = "";
    public $code = "";
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $description = "";
    public $validity_start_date_day = "";
    public $validity_start_date_month = "";
    public $validity_start_date_year = "";
    public $validity_end_date_day = "";
    public $validity_end_date_month = "";
    public $validity_end_date_year = "";
    public $descriptions = array();

    public function __construct()
    {
        $this->get_additional_code_types();
    }


    function validate_form()
    {
        global $application;
        $errors = array();
        $this->additional_code_type_id = strtoupper(get_formvar("additional_code_type_id", "", True));
        $this->additional_code = strtoupper(get_formvar("additional_code", "", True));

        /*
        pre ($_REQUEST);
        h1 ($this->additional_code);
        die();
        */
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

        # Check on the additional_code_type_id
        if (strlen($this->additional_code_type_id) != 1) {
            array_push($errors, "additional_code_type_id");
        }

        # Check on the additional code
        if (strlen($this->additional_code) != 3) {
            array_push($errors, "additional_code");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($application->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "additional_code_exists");
            }
        }

        # Check on the description
        if (($this->description == "") || (strlen($this->description) > 5000)) {
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

    function create()
    {
        global $conn, $application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
        $this->additional_code_description_period_sid = $application->get_next_additional_code_description_period();
        $this->additional_code_sid = $application->get_next_additional_code();

        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }

        $status = 'awaiting approval';
        # Create the additional_code record
        $sql = "INSERT INTO additional_codes_oplog (
                additional_code_sid, additional_code, additional_code_type_id,
                validity_start_date, operation, operation_date, workbasket_id, status)
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
            RETURNING oid;";

        pg_prepare($conn, "create_additional_code", $sql);
        $result = pg_execute($conn, "create_additional_code", array(
            $this->additional_code_sid, $this->additional_code, $this->additional_code_type_id,
            $this->validity_start_date, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "additional_code", $status, $operation, $operation_date);

        // Then upate the additional code record with oid of the workbasket item record
        $sql = "UPDATE additional_codes_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "update_additional_code", $sql);
        $result = pg_execute($conn, "update_additional_code", array(
            $workbasket_item_id, $oid
        ));

        # Create the additional_code description period record
        $sql = "INSERT INTO additional_code_description_periods_oplog (
            additional_code_description_period_sid, additional_code_sid, additional_code,
            additional_code_type_id, validity_start_date,
            operation, operation_date, workbasket_id, status, workbasket_item_id)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)
            RETURNING oid;";

        pg_prepare($conn, "create_additional_code_description_period", $sql);
        $result = pg_execute($conn, "create_additional_code_description_period", array(
            $this->additional_code_description_period_sid, $this->additional_code_sid, $this->additional_code,
            $this->additional_code_type_id, $this->validity_start_date,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));

        # Create the additional_code description record
        $sql = "INSERT INTO additional_code_descriptions_oplog (
            additional_code_description_period_sid, additional_code_sid, additional_code,
            additional_code_type_id, language_id, description,
            operation, operation_date, workbasket_id, status, workbasket_item_id)
            VALUES ($1, $2, $3, $4, 'EN', $5, $6, $7, $8, $9, $10)
            RETURNING oid;";

        pg_prepare($conn, "create_additional_code_description", $sql);
        $result = pg_execute($conn, "create_additional_code_description", array(
            $this->additional_code_description_period_sid, $this->additional_code_sid, $this->additional_code,
            $this->additional_code_type_id, $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT * FROM additional_codes WHERE additional_code_type_id = $1 and additional_code = $2";
        pg_prepare($conn, "additional_code_exists", $sql);
        $result = pg_execute($conn, "additional_code_exists", array($this->additional_code_type_id, $this->additional_code));
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

    public function get_parameters($description = false)
    {
        global $application;
        global $error_handler;

        $this->additional_code_sid = trim(get_querystring("additional_code_sid"));
        $this->additional_code_type_id = trim(get_querystring("additional_code_type_id"));
        $this->additional_code = trim(get_querystring("additional_code"));
        $this->ac = trim(get_querystring("ac"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                $ret = $this->populate_from_db();
                if (!$ret) {
                    h1("An error has occurred - no such additional code");
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
        $sql = "with cte as
        (
        select operation, operation_date,
        validity_start_date, validity_end_date, status, null as description, '0' as object_precedence
        from additional_codes_oplog
        where additional_code_type_id = $1 and additional_code = $2
        union
        select ac.operation, ac.operation_date,
        validity_start_date, null as validity_end_date, ac.status, description, '1' as object_precedence
        from additional_code_descriptions_oplog ac, additional_code_description_periods_oplog acp
        where ac.additional_code_description_period_sid = acp.additional_code_description_period_sid 
        and ac.additional_code_type_id = $1 and ac.additional_code = $2
        )
        select operation, operation_date, validity_start_date, validity_end_date, status, description
        from cte order by operation_date desc, object_precedence desc;";
        $stmt = "stmt_1";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->additional_code_type_id, $this->additional_code));
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

    public function populate_from_cookies()
    {
        return (true);
    }

    public function get_additional_code_types()
    {
        global $conn;
        $sql = "SELECT ft.certificate_type_code, description FROM certificate_types ft, certificate_type_descriptions ftd
 WHERE ft.certificate_type_code = ftd.certificate_type_code
 AND validity_end_date IS NULL ORDER BY 1";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $certificate_type = new certificate_type;
                $certificate_type->certificate_type_code = $row['certificate_type_code'];
                $certificate_type->description = $row['description'];
                array_push($temp, $certificate_type);
            }
            $this->certificate_types = $temp;
        }
    }

    public function get_additional_code_sid()
    {
        global $conn;
        $sql = "select additional_code_sid
 from additional_codes where additional_code_type_id = $1 and additional_code = $2
 order by validity_start_date desc limit 1;";
        pg_prepare($conn, "get_additional_code_sid", $sql);
        $result = pg_execute($conn, "get_additional_code_sid", array($this->additional_code_type_id, $this->additional_code));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->additional_code_sid = $row[0];
        } else {
            $this->additional_code_sid = Null;
        }
        return ($this->additional_code_sid);
    }


    public function set_properties(
        $certificate_code,
        $validity_start_date,
        $validity_end_date,
        $trade_movement_code,
        $priority_code,
        $measure_component_applicable_code,
        $origin_dest_code,
        $order_number_capture_code,
        $measure_explosion_level,
        $certificate_series_id,
        $description,
        $is_quota
    ) {
        $this->certificate_code = $certificate_code;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;
        $this->trade_movement_code = $trade_movement_code;
        $this->priority_code = $priority_code;
        $this->measure_component_applicable_code = $measure_component_applicable_code;
        $this->origin_dest_code = $origin_dest_code;
        $this->order_number_capture_code = $order_number_capture_code;
        $this->measure_explosion_level = $measure_explosion_level;
        $this->certificate_series_id = $certificate_series_id;
        $this->description = $description;
        $this->description_truncated = substr($description, 0, 75);
        $this->is_quota = $is_quota;
    }


    public function get_descriptions()
    {
        global $conn;
        $sql = "select validity_start_date, acd.description
        from additional_code_description_periods acdp, additional_code_descriptions acd
        where acdp.additional_code_description_period_sid = acd.additional_code_description_period_sid
        and acd.additional_code_sid = $1
        order by validity_start_date desc;";
        pg_prepare($conn, "get_descriptions", $sql);
        $result = pg_execute($conn, "get_descriptions", array($this->additional_code_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $description = new description($row['validity_start_date'], $row['description']);
                array_push($this->descriptions, $description);
            }
        }
        return ($row_count);
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "select ac.additional_code_type_id, additional_code, validity_start_date,
        validity_end_date, code, ac.description, actd.description as additional_code_type_description
        from ml.ml_additional_codes ac, additional_code_type_descriptions actd
        where ac.additional_code_type_id = actd.additional_code_type_id
        and ac.additional_code_sid = $1;";
        pg_prepare($conn, "get_additional_code", $sql);
        $result = pg_execute($conn, "get_additional_code", array($this->additional_code_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->additional_code_type_id = $row[0];
            $this->additional_code = $row[1];
            $this->validity_start_date = $row[2];
            $this->validity_end_date = $row[3];
            $this->code = $row[4];
            $this->description = $row[5];
            $this->additional_code_type_description = $row[6];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            if ($this->validity_end_date == "") {
                $this->validity_end_date_day = "";
                $this->validity_end_date_month = "";
                $this->validity_end_date_year = "";
            } else {
                $this->validity_end_date_day = date('d', strtotime($this->validity_end_date));
                $this->validity_end_date_month = date('m', strtotime($this->validity_end_date));
                $this->validity_end_date_year = date('Y', strtotime($this->validity_end_date));
            }
            $this->get_descriptions();
            return (true);
        } else {
            return (false);
        }
    }

    function get_description_from_db()
    {
        global $conn;
        $sql = "SELECT fd.certificate_type_code, fd.certificate_code, fd.description, fdp.validity_start_date
 FROM certificate_description_periods fdp, certificate_descriptions fd
 WHERE fd.certificate_description_period_sid = fdp.certificate_description_period_sid
 AND fd.certificate_description_period_sid = $1 ";

        pg_prepare($conn, "get_certificate_description", $sql);
        $result = pg_execute($conn, "get_certificate_description", array($this->certificate_description_period_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[2];
            $this->validity_start_date = $row[3];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            $this->certificate_heading = "Edit measure type " . $this->certificate_code;
            $this->disable_certificate_code_field = " disabled";
        }
    }

    public function clear_cookies()
    {
        setcookie("certificate_code", "", time() + (86400 * 30), "/");
        setcookie("certificate_type_code", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("certificate_description", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_date_year", "", time() + (86400 * 30), "/");
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

    public function spilt_code()
    {
        if (strlen($this->code) == 4) {
            $this->additional_code_type_id = substr($this->code, 0, 1);
            $this->additional_code = substr($this->code, 1, 3);
        }
    }

    public function get_details_from_id()
    {
        global $conn;
        $sql = "select additional_code_sid, validity_start_date, validity_end_date, description
        from ml.ml_additional_codes
        where additional_code_type_id = $1 and additional_code = $2 limit 1;";
        $stmt = "get_ac" . $this->additional_code_type_id . $this->additional_code;
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array(
            $this->additional_code_type_id,
            $this->additional_code
        ));

        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->additional_code_sid = $row[0];
            $this->validity_start_date = $row[1];
            $this->validity_end_date = $row[2];
            $this->description = $row[3];
        }
    }
}
