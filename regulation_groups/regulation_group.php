<?php
class regulation_group
{
    // Class properties and methods go here
    public $regulation_group_id  = "";
    public $description             = "";
    public $id_disabled = "";
    public $validity_start_date_string = "";
    public $validity_end_date_string = "";
    
    public function __construct()
    {

    }

    public function get_parameters()
    {
        global $error_handler;
        $this->regulation_group_id = trim(get_querystring("regulation_group_id"));
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
                    h1("An error has occurred - no such regulation group");
                    die();
                }
            } else {
                $this->populate_from_cookies();
            }
        }
    }

    public function clear_cookies()
    {
        setcookie("regulation_group_id", "", time() + (86400 * 30), "/");
        setcookie("description", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_string", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_year", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_string", "", time() + (86400 * 30), "/");
    }

    function populate_from_cookies()
    {
        $this->regulation_group_id = get_cookie("regulation_group_id");
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
        FROM regulation_groups rg, regulation_group_descriptions rgd
        WHERE rg.regulation_group_id = rgd.regulation_group_id
        AND rg.regulation_group_id = $1";
        pg_prepare($conn, "get_regulation_group", $sql);
        $result = pg_execute($conn, "get_regulation_group", array($this->regulation_group_id));
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
        $errors = array();
        $this->regulation_group_id = get_formvar("regulation_group_id", "", True);
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
        if (strlen($this->regulation_group_id) != 1) {
            array_push($errors, "regulation_group_id");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($this->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "regulation_group_exists");
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
        } else {/*
 if ($create_edit == "create") {
 // Do create scripts
 $this->create();
 } else {
 // Do edit scripts
 $this->update();
 }*/
            $url = "./";
        }
        header("Location: " . $url);
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
        $sql = "SELECT regulation_group_id FROM regulation_groups WHERE regulation_group_id = $1";
        pg_prepare($conn, "regulation_groups_exists", $sql);
        $result = pg_execute($conn, "regulation_groups_exists", array($this->regulation_group_id));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $exists = true;
            }
        }
        return ($exists);
    }

}
