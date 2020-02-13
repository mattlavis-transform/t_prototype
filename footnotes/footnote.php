<?php
class footnote
{
    // Class properties and methods go here
    public $footnote_id = "";
    public $footnote_type_id = "";
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
    public $application_code_description = "";

    public $footnote_assignments = array();

    function validate_form()
    {
        global $application;
        $errors = array();
        $this->footnote_type_id = strtoupper(get_formvar("footnote_type_id", "", True));
        $this->footnote_id = strtoupper(get_formvar("footnote_id", "", True));
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

        # Check on the footnote_type_id
        if (strlen($this->footnote_type_id) != 2) {
            array_push($errors, "footnote_type_id");
        }

        # Check on the footnote_id code
        if ((strlen($this->footnote_id) != 3) && (strlen($this->footnote_id) != 5)) {
            array_push($errors, "footnote_id");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($application->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "footnote_exists");
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

        # Check on the description
        if ($application->mode == "insert") {
            if (($this->description == "") || (strlen($this->description) > 5000)) {
                array_push($errors, "description");
            }
        }

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


    function validate_description_form()
    {
        global $application;
        $errors = array();
        $this->footnote_type_id = strtoupper(get_formvar("footnote_type_id", "", True));
        $this->footnote_id = strtoupper(get_formvar("footnote_id", "", True));
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

        # Check on the footnote_type_id
        if (strlen($this->footnote_type_id) != 2) {
            array_push($errors, "footnote_type_id");
        }

        # Check on the footnote_id code
        if ((strlen($this->footnote_id) != 3) && (strlen($this->footnote_id) != 5)) {
            array_push($errors, "footnote_id");
        }

        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the description
        if (($this->description == "") || (strlen($this->description) > 5000)) {
            array_push($errors, "description");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&certificate_type_code=" . $this->certificate_type_code;
        } else {
            if ($application->mode == "insert") {
                // Do create scripts
                $this->create_description();
            } else {
                // Do edit scripts
                $this->update();
            }
            $url = "./confirmation.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }

    public function get_parameters($description = false)
    {
        global $application;
        global $error_handler;

        $this->footnote_type_id = trim(get_querystring("footnote_type_id"));
        $this->footnote_id = trim(get_querystring("footnote_id"));
        $this->validity_start_date = trim(get_querystring("validity_start_date"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                if ($description == false) {
                    $ret = $this->populate_from_db();
                } else {
                    $ret = $this->get_specific_description($this->validity_start_date);
                    //prend ($_REQUEST);
                }
                if (!$ret) {
                    h1("An error has occurred - no such footnote");
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
        from footnotes_oplog
        where footnote_type_id = $1 and footnote_id = $2
        union
        select fd.operation, fd.operation_date,
        validity_start_date, null as validity_end_date, fd.status, description, '1' as object_precedence
        from footnote_descriptions_oplog fd, footnote_description_periods_oplog fdp
        where fd.footnote_description_period_sid = fdp.footnote_description_period_sid 
        and fd.footnote_type_id = $1 and fd.footnote_id = $2
        )
        select operation, operation_date, validity_start_date, validity_end_date, status, description
        from cte order by operation_date desc, object_precedence desc;";
        $stmt = "stmt_1";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->footnote_type_id, $this->footnote_id));
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

    public function get_descriptions()
    {
        global $conn;
        $sql = "select validity_start_date, fd.description
        from footnote_description_periods fdp, footnote_descriptions fd
        where fd.footnote_type_id = $1 and fd.footnote_id = $2
        and fd.footnote_description_period_sid = fdp.footnote_description_period_sid
        order by validity_start_date desc;";
        pg_prepare($conn, "get_descriptions", $sql);
        $result = pg_execute($conn, "get_descriptions", array($this->footnote_type_id, $this->footnote_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $description = new description($row['validity_start_date'], $row['description']);
                array_push($this->descriptions, $description);
            }
        }
        return ($row_count);
    }

    public function get_specific_description($validity_start_date)
    {
        global $conn;
        if ($this->validity_start_date == null) {
            $sql = "select fd.description
            from footnote_description_periods fdp, footnote_descriptions fd
            where fd.footnote_type_id = $1 and fd.footnote_id = $2
            and fd.footnote_description_period_sid = fdp.footnote_description_period_sid
            order by validity_start_date desc limit 1;";

            pg_prepare($conn, "get_specific_description", $sql);

            $result = pg_execute($conn, "get_specific_description", array($this->footnote_type_id, $this->footnote_id));
        } else {
            $sql = "select fd.description
            from footnote_description_periods fdp, footnote_descriptions fd
            where fd.footnote_type_id = $1 and fd.footnote_id = $2
            and fd.footnote_description_period_sid = fdp.footnote_description_period_sid
            and fdp.validity_start_date = $3
            order by validity_start_date desc;";

            pg_prepare($conn, "get_specific_description", $sql);

            $result = pg_execute($conn, "get_specific_description", array($this->footnote_type_id, $this->footnote_id, $validity_start_date));
        }
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_array($result);
            $this->description = $row['description'];
            return (true);
        }
        return (false);
    }

    public function get_footnote_types()
    {
        global $conn;
        $sql = "SELECT ft.footnote_type_id, description FROM footnote_types ft, footnote_type_descriptions ftd
        WHERE ft.footnote_type_id = ftd.footnote_type_id
        AND validity_end_date IS NULL ORDER BY 1";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $footnote_type = new footnote_type;
                $footnote_type->footnote_type_id = $row['footnote_type_id'];
                $footnote_type->description = $row['description'];
                array_push($temp, $footnote_type);
            }
            $this->footnote_types = $temp;
        }
    }

    public function set_properties(
        $footnote_id,
        $validity_start_date,
        $validity_end_date,
        $trade_movement_code,
        $priority_code,
        $measure_component_applicable_code,
        $origin_dest_code,
        $order_number_capture_code,
        $measure_explosion_level,
        $footnote_series_id,
        $description,
        $is_quota
    ) {
        $this->footnote_id = $footnote_id;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;
        $this->trade_movement_code = $trade_movement_code;
        $this->priority_code = $priority_code;
        $this->measure_component_applicable_code = $measure_component_applicable_code;
        $this->origin_dest_code = $origin_dest_code;
        $this->order_number_capture_code = $order_number_capture_code;
        $this->measure_explosion_level = $measure_explosion_level;
        $this->footnote_series_id = $footnote_series_id;
        $this->description = $description;
        $this->description_truncated = substr($description, 0, 75);
        $this->is_quota = $is_quota;
    }

    function populate_from_cookies()
    {
        #$this->footnote_id = get_cookie("footnote_id");
        #$this->footnote_type_id = get_cookie("footnote_type_id");
        $this->validity_start_date_day = get_cookie("footnote_validity_start_date_day");
        $this->validity_start_date_month = get_cookie("footnote_validity_start_date_month");
        $this->validity_start_date_year = get_cookie("footnote_validity_start_date_year");
        $this->validity_end_date_day = get_cookie("footnote_validity_end_date_day");
        $this->validity_end_date_month = get_cookie("footnote_validity_end_date_month");
        $this->validity_end_date_year = get_cookie("footnote_validity_end_date_year");
        $this->description = get_cookie("footnote_description");
        $this->heading = "Create new footnote";
        $this->disable_footnote_id_field = "";
    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT * FROM footnotes WHERE footnote_id = $1 AND footnote_type_id = $2";
        pg_prepare($conn, "footnote_exists", $sql);
        $result = pg_execute($conn, "footnote_exists", array($this->footnote_id, $this->footnote_type_id));
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

    function create_update($operation)
    {
        global $conn, $application;
        $operation_date = $application->get_operation_date();
        $this->footnote_description_period_sid = $application->get_next_footnote_description_period();

        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }

        $status = 'awaiting approval';
        # Create the footnote record
        $sql = "INSERT INTO footnotes_oplog (
            footnote_id, footnote_type_id, validity_start_date, validity_end_date,
            operation, operation_date, workbasket_id, status)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
            RETURNING oid;";
        pg_prepare($conn, "stmt_1", $sql);
        $result = pg_execute($conn, "stmt_1", array(
            $this->footnote_id, $this->footnote_type_id, $this->validity_start_date, $this->validity_end_date,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "footnote", $status, $operation, $operation_date);

        // Then upate the footnote record with oid of the workbasket item record
        $sql = "UPDATE footnotes_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "stmt_2", $sql);
        $result = pg_execute($conn, "stmt_2", array(
            $workbasket_item_id, $oid
        ));

        if ($operation == "U") {
            # Create the footnote description period record
            $sql = "INSERT INTO footnote_description_periods_oplog (footnote_description_period_sid, footnote_id,
            footnote_type_id, validity_start_date, operation, operation_date, workbasket_id, status, workbasket_item_id)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
            RETURNING oid;";
            pg_prepare($conn, "stmt_3", $sql);
            $result = pg_execute($conn, "stmt_3", array(
                $this->footnote_description_period_sid, $this->footnote_id,
                $this->footnote_type_id, $this->validity_start_date, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
            ));

            # Create the footnote description record
            $sql = "INSERT INTO footnote_descriptions_oplog (footnote_description_period_sid, footnote_id,
            footnote_type_id, language_id, description, operation, operation_date, workbasket_id, status, workbasket_item_id)
            VALUES ($1, $2, $3, 'EN', $4, $5, $6, $7, $8, $9)
            RETURNING oid;";
            pg_prepare($conn, "stmt_4", $sql);
            $result = pg_execute($conn, "stmt_4", array(
                $this->footnote_description_period_sid, $this->footnote_id,
                $this->footnote_type_id, $this->description, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
            ));
        }
    }

    function create_description()
    {
        global $conn, $application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
        $this->footnote_description_period_sid = $application->get_next_footnote_description_period();

        $status = 'awaiting approval';

        # Create the footnote description record
        $sql = "INSERT INTO footnote_descriptions_oplog (footnote_description_period_sid, footnote_id,
            footnote_type_id, language_id, description, operation, operation_date, workbasket_id, status)
            VALUES ($1, $2, $3, 'EN', $4, $5, $6, $7, $8)
            RETURNING oid;";
        pg_prepare($conn, "create_footnote_description", $sql);
        $result = pg_execute($conn, "create_footnote_description", array(
            $this->footnote_description_period_sid, $this->footnote_id,
            $this->footnote_type_id, $this->description, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "footnote_description", $status, $operation, $operation_date);

        // Then upate the footnote description record with oid of the workbasket item record
        $sql = "UPDATE footnote_descriptions_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "update_footnote", $sql);
        $result = pg_execute($conn, "update_footnote", array(
            $workbasket_item_id, $oid
        ));

        # Create the footnote description period record
        $sql = "INSERT INTO footnote_description_periods_oplog (footnote_description_period_sid, footnote_id,
            footnote_type_id, validity_start_date, operation, operation_date, workbasket_id, status, workbasket_item_id)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
            RETURNING oid;";
        pg_prepare($conn, "create_footnote_description_period", $sql);
        $result = pg_execute($conn, "create_footnote_description_period", array(
            $this->footnote_description_period_sid, $this->footnote_id,
            $this->footnote_type_id, $this->validity_start_date, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
        //die();

    }


    function delete_description()
    {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();

        # Get the missing details
        $this->get_missing_details();

        # Insert the footnote
        $sql = "INSERT INTO footnotes_oplog
 (footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
 VALUES ($1, $2, $3, 'U', $4)";
        pg_prepare($conn, "footnote_insert", $sql);
        pg_execute($conn, "footnote_insert", array(
            $this->footnote_type_id,
            $this->footnote_id, $this->validity_start_date, $operation_date
        ));

        # Insert the footnote description period
        $sql = "INSERT INTO footnote_description_periods_oplog
 (footnote_description_period_sid, footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6)";
        pg_prepare($conn, "footnote_description_period_insert", $sql);
        pg_execute($conn, "footnote_description_period_insert", array(
            $this->footnote_description_period_sid, $this->footnote_type_id,
            $this->footnote_id, $this->period_validity_start_date, $operation, $operation_date
        ));

        # Insert the footnote description
        $sql = "INSERT INTO footnote_descriptions_oplog
 (footnote_description_period_sid, language_id, footnote_type_id, footnote_id, description, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "footnote_description_insert", $sql);
        pg_execute($conn, "footnote_description_insert", array(
            $this->footnote_description_period_sid, "EN",
            $this->footnote_type_id, $this->footnote_id, $this->description, $operation, $operation_date
        ));
        return (True);
    }

    function get_missing_details()
    {
        global $conn;
        $sql = "SELECT description, cdp.validity_start_date as period_validity_start_date, c.validity_start_date as c_validity_start_date
 FROM footnote_descriptions cd, footnote_description_periods cdp, footnotes c
 WHERE cd.footnote_description_period_sid = cdp.footnote_description_period_sid
 AND c.footnote_id = cd.footnote_id
 AND c.footnote_type_id = cd.footnote_type_id
 AND c.footnote_id = cdp.footnote_id
 AND c.footnote_type_id = cdp.footnote_type_id
 AND cd.footnote_description_period_sid = $1";
        pg_prepare($conn, "get_missing_details", $sql);
        $result = pg_execute($conn, "get_missing_details", array($this->footnote_id, $this->footnote_type_id));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[0];
            $this->period_validity_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $row[1])->format('Y-m-d');
            $this->validity_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $row[2])->format('Y-m-d');
        } else {
            $this->description = "";
            $this->period_validity_start_date = "";
            $this->validity_start_date = "";
        }
    }



    function get_start_date()
    {
        global $conn;
        $sql = "SELECT validity_start_date FROM footnotes
 WHERE footnote_id = $1 AND footnote_type_id = $2 ORDER BY operation_date DESC LIMIT 1";
        pg_prepare($conn, "get_footnote_validity_start_date", $sql);
        $result = pg_execute($conn, "get_footnote_validity_start_date", array($this->footnote_id, $this->footnote_type_id));

        if ($result) {
            $row = pg_fetch_row($result);
            $d = $row[0];
            return (DateTime::createFromFormat('Y-m-d H:i:s', $d)->format('Y-m-d'));
        } else {
            return ("");
        }
    }

    function insert_description($footnote_id, $footnote_type_id, $validity_start_date, $description)
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $footnote_description_period_sid = $application->get_next_footnote_description_period();
        $operation_date = $application->get_operation_date();

        $this->footnote_id = $footnote_id;
        $this->footnote_type_id = $footnote_type_id;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->footnote_description_period_sid = $footnote_description_period_sid;

        $this->f_validity_start_date = $this->get_start_date();
        $status = 'awaiting approval';

        # Insert the footnote description period
        $sql = "INSERT INTO footnote_description_periods_oplog
        (footnote_description_period_sid, footnote_type_id, footnote_id, validity_start_date, operation, operation_date, workbasket_id, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8
            RETURNING oid;";
        pg_prepare($conn, "footnote_description_period_insert", $sql);
        $result = pg_execute($conn, "footnote_description_period_insert", array(
            $this->footnote_description_period_sid, $this->footnote_type_id,
            $this->footnote_id, $this->validity_start_date, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        $application->session->workbasket->insert_workbasket_item($oid, "footnote_description_period", $status, $operation, $operation_date);

        # Insert the footnote description
        $sql = "INSERT INTO footnote_descriptions_oplog
        (footnote_description_period_sid, language_id, footnote_type_id, footnote_id, description, operation, operation_date, workbasket_id, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9
            RETURNING oid;";
        pg_prepare($conn, "footnote_description_insert", $sql);
        $result = pg_execute($conn, "footnote_description_insert", array(
            $this->footnote_description_period_sid, "EN",
            $this->footnote_type_id, $this->footnote_id, $this->description, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        //$application->session->workbasket->insert_workbasket_item($oid, "footnote_description_period", $status, $operation, $operation_date);
        return (true);
    }

    function update_description($footnote_id, $footnote_type_id, $validity_start_date, $description, $footnote_description_period_sid)
    {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $this->footnote_id = $footnote_id;
        $this->footnote_type_id = $footnote_type_id;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->footnote_description_period_sid = $footnote_description_period_sid;

        $this->f_validity_start_date = $this->get_start_date();

        # Insert the footnote
        $sql = "INSERT INTO footnotes_oplog
 (footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
 VALUES ($1, $2, $3, 'U', $4)";
        pg_prepare($conn, "footnote_insert", $sql);
        pg_execute($conn, "footnote_insert", array(
            $this->footnote_type_id,
            $this->footnote_id, $this->f_validity_start_date, $operation_date
        ));

        # Insert the footnote description period
        $sql = "INSERT INTO footnote_description_periods_oplog
 (footnote_description_period_sid, footnote_type_id, footnote_id, validity_start_date, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6)";
        pg_prepare($conn, "footnote_description_period_insert", $sql);
        pg_execute($conn, "footnote_description_period_insert", array(
            $footnote_description_period_sid, $footnote_type_id,
            $footnote_id, $validity_start_date, $operation, $operation_date
        ));

        # Insert the footnote description
        $sql = "INSERT INTO footnote_descriptions_oplog
 (footnote_description_period_sid, language_id, footnote_type_id, footnote_id, description, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "footnote_description_insert", $sql);
        pg_execute($conn, "footnote_description_insert", array(
            $footnote_description_period_sid, "EN",
            $footnote_type_id, $footnote_id, $description, $operation, $operation_date
        ));
        return (True);
    }


    function update()
    {
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

        $sql = "INSERT INTO footnotes_oplog (footnote_id, validity_start_date,
 validity_end_date, trade_movement_code, priority_code,
 measure_component_applicable_code, origin_dest_code,
 order_number_capture_code, measure_explosion_level, footnote_series_id,
 operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)";

        pg_prepare($conn, "create_footnote", $sql);

        $result = pg_execute($conn, "create_footnote", array(
            $this->footnote_id, $this->validity_start_date,
            $this->validity_end_date, $this->trade_movement_code, $this->priority_code,
            $this->measure_component_applicable_code, $this->origin_dest_code,
            $this->order_number_capture_code, $this->measure_explosion_level, $this->footnote_series_id,
            $operation, $operation_date
        ));


        $sql = "INSERT INTO footnote_descriptions_oplog (footnote_id, language_id, description,
 operation, operation_date) VALUES ($1, 'EN', $2, $3, $4)";

        pg_prepare($conn, "create_footnote_description", $sql);

        $result = pg_execute($conn, "create_footnote_description", array(
            $this->footnote_id, $this->description,
            $operation, $operation_date
        ));
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "SELECT f.description, f.validity_start_date, f.validity_end_date, ft.application_code,
        case
        when ft.application_code in ('1', '2') then 'Nomenclature-related footnote'
        when ft.application_code in ('6', '7') then 'Measure-related footnote'
        else 'Not recommended'
        end as application_code_description, ftd.description as footnote_type_description
        FROM ml.ml_footnotes f, footnote_types ft, footnote_type_descriptions ftd
        where f.footnote_type_id = ft.footnote_type_id
        and f.footnote_type_id = ftd.footnote_type_id
        and f.footnote_id = $1 and f.footnote_type_id = $2 ";
        pg_prepare($conn, "get_footnote", $sql);
        $result = pg_execute($conn, "get_footnote", array($this->footnote_id, $this->footnote_type_id));

        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->description = $row[0];
            $this->validity_start_date = $row[1];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            $this->validity_end_date = $row[2];
            if ($this->validity_end_date == "") {
                $this->validity_end_date_day = "";
                $this->validity_end_date_month = "";
                $this->validity_end_date_year = "";
            } else {
                $this->validity_end_date_day = date('d', strtotime($this->validity_end_date));
                $this->validity_end_date_month = date('m', strtotime($this->validity_end_date));
                $this->validity_end_date_year = date('Y', strtotime($this->validity_end_date));
            }
            $this->application_code = $row[3];
            $this->application_code_description = $row[4];
            $this->footnote_type_description = $row[5];
            $this->get_descriptions();
            $this->get_assignments();
            return (true);
        } else {
            return (false);
        }
    }

    function get_assignments_measure_related()
    {
        global $conn;
        $sql = "select m.measure_sid, m.measure_type_id, mtd.description as measure_type_description, m.geographical_area_id,
        m.validity_start_date, m.validity_end_date, m.goods_nomenclature_item_id, m.goods_nomenclature_sid, m.geographical_area_sid
        from footnote_association_measures fam, measures m, measure_type_descriptions mtd
        where m.measure_sid = fam.measure_sid
        and m.measure_type_id = mtd.measure_type_id
        and m.validity_end_date is null
        and footnote_type_id = $1 and footnote_id = $2 order by m.goods_nomenclature_item_id;";
        //pre($sql);
        pg_prepare($conn, "get_assignments_measure_related", $sql);
        $result = pg_execute($conn, "get_assignments_measure_related", array($this->footnote_type_id, $this->footnote_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $fam = new footnote_association_measure;
                $fam->measure_sid = $row[0];
                $fam->measure_type_id = $row[1];
                $fam->measure_type_description = $row[2];
                $fam->measure_type_id_description = "<b>" . $fam->measure_type_id . "</b> " . $fam->measure_type_description;
                $fam->geographical_area_id = $row[3];
                $fam->validity_start_date = $row[4];
                $fam->validity_end_date = $row[5];
                $fam->goods_nomenclature_item_id = $row[6];
                $fam->goods_nomenclature_sid = $row[7];
                $fam->geographical_area_sid = $row[8];

                $fam->goods_nomenclature_url = "<a class='nodecorate' href='/goods_nomenclatures/goods_nomenclature_item_view.html?goods_nomenclature_sid=" . $fam->goods_nomenclature_sid . "&productline_suffix=80&goods_nomenclature_item_id=" . $fam->goods_nomenclature_item_id . "'>" . format_goods_nomenclature_item_id($fam->goods_nomenclature_item_id) . "</a>";
                $fam->measure_type_id_description_url = "<a class='govuk-link' href=/measure_types/view.html?mode=view&measure_type_id=" . $fam->measure_type_id . "><b>" . $fam->measure_type_id . "</b> " . $fam->measure_type_description . "</a>";
                $fam->geographical_area_id_url = "<a class='govuk-link' href=/geographical_areas/view.html?mode=view&geographical_area_id=" . $fam->geographical_area_id . "&geographical_area_sid=" . $fam->geographical_area_sid . ">" . $fam->geographical_area_id . "</a>";

                array_push($this->footnote_assignments, $fam);
                //pre ($fam);
            }
        }
        return ($row_count);
    }

    function get_assignments_nomenclature_related()
    {
        global $conn;
        $sql = "with association_cte as (
        select distinct on (goods_nomenclature_sid)
        fagn.goods_nomenclature_sid, fagn.validity_start_date, fagn.validity_end_date, gnd.goods_nomenclature_item_id, gnd.description
        from footnote_association_goods_nomenclatures fagn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
        where fagn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        and gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        and footnote_type = $1 and footnote_id = $2
        order by goods_nomenclature_sid, gndp.validity_start_date
        )  select *, count(*) over() as full_count from association_cte;";
        //prend ($sql);
        pg_prepare($conn, "get_assignments_nomenclature_related", $sql);
        $result = pg_execute($conn, "get_assignments_nomenclature_related", array($this->footnote_type_id, $this->footnote_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $fagn = new footnote_association_goods_nomenclature;
                $fagn->goods_nomenclature_sid = $row[0];
                $fagn->validity_start_date = $row[1];
                $fagn->validity_end_date = $row[2];
                $fagn->goods_nomenclature_item_id = $row[3];
                $fagn->goods_nomenclature_description = $row[4];

                $fagn->goods_nomenclature_url = "<a class='nodecorate' href='/goods_nomenclatures/goods_nomenclature_item_view.html?goods_nomenclature_sid=" . $fagn->goods_nomenclature_sid . "&productline_suffix=80&goods_nomenclature_item_id=" . $fagn->goods_nomenclature_item_id . "'>" . format_goods_nomenclature_item_id($fagn->goods_nomenclature_item_id) . "</a>";

                array_push($this->footnote_assignments, $fagn);
            }
        }
        return ($row_count);
    }

    function get_assignments()
    {
        if ($this->application_code_description == "Nomenclature-related footnote") {
            $this->get_assignments_nomenclature_related();
        } elseif ($this->application_code_description == "Measure-related footnote") {
            $this->get_assignments_measure_related();
        } else {
            return;
        }
    }

    function get_description_from_db()
    {
        global $conn;
        $sql = "SELECT fd.footnote_type_id, fd.footnote_id, fd.description, fdp.validity_start_date
        FROM footnote_description_periods fdp, footnote_descriptions fd
        WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
        AND fd.footnote_description_period_sid = $1 ";

        pg_prepare($conn, "get_footnote_description", $sql);
        $result = pg_execute($conn, "get_footnote_description", array($this->footnote_description_period_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[2];
            $this->validity_start_date = $row[3];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            $this->footnote_heading = "Edit measure type " . $this->footnote_id;
            $this->disable_footnote_id_field = " disabled";
        }
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

        setcookie("filter_footnotes_freetext", "", time() + (86400 * 30), "/");
        setcookie("filter_footnotes_start_year", "", time() + (86400 * 30), "/");
        setcookie("filter_footnotes_active_state", "", time() + (86400 * 30), "/");
        setcookie("filter_footnotes_footnote_type_id", "", time() + (86400 * 30), "/");
    }

    function get_latest_description()
    {
        global $conn;
        $sql = "SELECT fd.description
 FROM footnote_description_periods fdp, footnote_descriptions fd
 WHERE fd.footnote_description_period_sid = fdp.footnote_description_period_sid
 AND fd.footnote_id = $1 AND fd.footnote_type_id = $2 
 ORDER BY fdp.validity_start_date DESC LIMIT 1";

        pg_prepare($conn, "get_latest_description", $sql);
        $result = pg_execute($conn, "get_latest_description", array($this->footnote_id, $this->footnote_type_id));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[0];
        }
    }


    public function business_rule_mt3()
    {
        // Business rule MT3
        // When a measure type is used in a measure then the validity period of the measure type must span the validity period of the measure. 
        global $conn;
        $succeeds = true;
        $sql = "SELECT measure_sid
 FROM measures m, base_regulations r
 WHERE m.measure_generating_regulation_id = r.base_regulation_id
 AND m.footnote_id = $1
 AND ( 
 (r.validity_end_date > $2 AND m.validity_end_date IS NULL AND r.effective_end_date IS NULL)
 OR
 (r.effective_end_date > $2 AND m.validity_end_date IS NULL)
 OR
 (m.validity_end_date > $2 OR (m.validity_end_date IS NULL AND r.effective_end_date IS NULL AND r.validity_end_date IS NULL))
 )
 UNION
 SELECT measure_sid
 FROM measures m, modification_regulations r
 WHERE m.measure_generating_regulation_id = r.modification_regulation_id
 AND m.footnote_id = $1
 AND ( 
 (r.validity_end_date > $2 AND m.validity_end_date IS NULL AND r.effective_end_date IS NULL)
 OR
 (r.effective_end_date > $2 AND m.validity_end_date IS NULL)
 OR
 (m.validity_end_date > $2 OR (m.validity_end_date IS NULL AND r.effective_end_date IS NULL AND r.validity_end_date IS NULL))
 )";
        pg_prepare($conn, "business_rule_mt3", $sql);
        $result = pg_execute($conn, "business_rule_mt3", array($this->footnote_id, $this->validity_end_date));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $succeeds = false;
            }
        }
        return ($succeeds);
    }
}
