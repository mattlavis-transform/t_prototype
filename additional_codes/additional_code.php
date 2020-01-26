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
    public $validity_start_day = "";
    public $validity_start_month = "";
    public $validity_start_year = "";
    public $validity_end_day = "";
    public $validity_end_month = "";
    public $validity_end_year = "";
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
        if ($this->mode == "insert") {
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
        } else {/*
 if ($create_edit == "create") {
 // Do create scripts
 $this->create();
 } else {
 // Do edit scripts
 $this->update();
 }*/
            $url = "./confirmation.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
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
            } else {
                $this->populate_from_cookies();
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
        $sql = "select additional_code_type_id, additional_code, validity_start_date, validity_end_date, code, description
        from ml.ml_additional_codes ac where additional_code_sid = $1;";
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
            $this->validity_start_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_year = date('Y', strtotime($this->validity_start_date));
            if ($this->validity_end_date == "") {
                $this->validity_end_day = "";
                $this->validity_end_month = "";
                $this->validity_end_year = "";
            } else {
                $this->validity_end_day = date('d', strtotime($this->validity_end_date));
                $this->validity_end_month = date('m', strtotime($this->validity_end_date));
                $this->validity_end_year = date('Y', strtotime($this->validity_end_date));
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
            $this->validity_start_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_year = date('Y', strtotime($this->validity_start_date));
            $this->certificate_heading = "Edit measure type " . $this->certificate_code;
            $this->disable_certificate_code_field = " disabled";
        }
    }

    public function clear_cookies()
    {
        setcookie("certificate_code", "", time() + (86400 * 30), "/");
        setcookie("certificate_type_code", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("certificate_description", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_day", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_month", "", time() + (86400 * 30), "/");
        setcookie("certificate_validity_end_year", "", time() + (86400 * 30), "/");
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
