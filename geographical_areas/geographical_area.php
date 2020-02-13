<?php
class geographical_area
{
    // Class properties and methods go here
    public $geographical_area_sid = Null;
    public $geographical_area_id = "";
    public $geographical_area_group_sid = 0;
    public $geographical_area_group_id = "";
    public $description = "";
    public $geographical_code = "";
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $validity_start_date_day = "";
    public $validity_start_date_month = "";
    public $validity_start_date_year = "";
    public $validity_end_date_day = "";
    public $validity_end_date_month = "";
    public $validity_end_date_year = "";
    public $descriptions = array();
    public $members = array();
    public $roo_members = array();


    public function get_parameters($description = false)
    {
        global $application;
        global $error_handler;

        $this->geographical_area_id = trim(get_querystring("geographical_area_id"));
        $this->geographical_area_sid = trim(get_querystring("geographical_area_sid"));
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
                }
                if (!$ret) {
                    h1("An error has occurred - no such geographical area");
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
        from geographical_areas_oplog
        where geographical_area_id = $1
        union
        select gad.operation, gad.operation_date,
        validity_start_date, null as validity_end_date, gad.status, description, '1' as object_precedence
        from geographical_area_descriptions_oplog gad, geographical_area_description_periods_oplog gadp
        where gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid 
        and gad.geographical_area_id = $1
        )
        select operation, operation_date, validity_start_date, validity_end_date, status, description
        from cte order by operation_date desc, object_precedence desc;";
        $stmt = "stmt_1";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->geographical_area_id));
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

    public function get_members()
    {
        global $conn;
        if ($this->geographical_code == "1") {
            $sql = "SELECT child_id as geographical_area_id, child_sid as geographical_area_sid, child_description as description, validity_start_date, validity_end_date
            FROM ml.ml_geo_memberships WHERE parent_sid = $1
            ORDER BY child_id, validity_start_date desc;";
        } else {
            $sql = "select parent_id as geographical_area_id, parent_sid as geographical_area_sid, parent_description as description, validity_start_date, validity_end_date
            from ml.ml_geo_memberships gm where child_sid = $1
            order by parent_id, validity_start_date desc;";
        }
        $stmt = "get_geographical_members" . strval($this->geographical_area_sid);
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->geographical_area_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $member = new member();
                //$member->validity_start_date = short_date($row['validity_start_date']);
                //$member->validity_end_date = short_date($row['validity_end_date']);
                $member->validity_start_date = $row['validity_start_date'];
                $member->validity_end_date = $row['validity_end_date'];
                $member->geographical_area_id = $row['geographical_area_id'];
                $member->geographical_area_sid = $row['geographical_area_sid'];
                $member->description = $row['description'];
                array_push($this->members, $member);
            }
        }
        return ($row_count);
    }

    public function get_specific_description($validity_start_date)
    {
        global $conn;
        $sql = "select description from geographical_area_description_periods gadp, geographical_area_descriptions gad
        where gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
        and gad.geographical_area_sid = $1 and validity_start_date = $2;
        ";

        pg_prepare($conn, "get_specific_description", $sql);

        $result = pg_execute($conn, "get_specific_description", array($this->geographical_area_sid, $this->validity_start_date));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_array($result);
            $this->description = $row['description'];
            return (true);
        }
        return (false);
    }

    function validate_form()
    {
        global $application;
        $errors = array();
        $this->geographical_area_id = strtoupper(get_formvar("geographical_area_id", "", True));
        $this->geographical_code = get_formvar("geographical_code", "", True);

        //prend ($_REQUEST);

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

        # Check on the geographical_code code
        if (strlen($this->geographical_code) != 1) {
            array_push($errors, "geographical_code");
        }

        # Check on the geographical_area_id
        if ((strlen($this->geographical_area_id) != 2) && (strlen($this->geographical_area_id) != 4)) {
            array_push($errors, "geographical_area_id");
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
        if (($this->description == "") || (strlen($this->description) > 500)) {
            array_push($errors, "description");
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
                $this->create();
            } else {
                // Do edit scripts
                //$this->update();
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
        $this->geographical_area_description_period_sid = $application->get_next_geographical_area_description_period();
        $this->geographical_area_sid = $application->get_next_geographical_area();

        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }

        $status = 'awaiting approval';
        # Create the geographical_area record
        $sql = "INSERT INTO geographical_areas_oplog (
            geographical_area_sid, geographical_area_id, geographical_code, validity_start_date,
            operation, operation_date, workbasket_id, status)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
            RETURNING oid;";

        pg_prepare($conn, "create_geographical_area", $sql);

        $result = pg_execute($conn, "create_geographical_area", array(
            $this->geographical_area_sid, $this->geographical_area_id, $this->geographical_code, $this->validity_start_date,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "geographical_area", $status, $operation, $operation_date);

        // Then upate the additional code record with oid of the workbasket item record
        $sql = "UPDATE geographical_areas_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "update_geographical_area", $sql);
        $result = pg_execute($conn, "update_geographical_area", array(
            $workbasket_item_id, $oid
        ));


        # Create the geographical_area description period record
        $sql = "INSERT INTO geographical_area_description_periods_oplog (
            geographical_area_description_period_sid, geographical_area_sid, geographical_area_id,
            validity_start_date, operation, operation_date, workbasket_id, status, workbasket_item_id)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";

        pg_prepare($conn, "create_geographical_area_description_period", $sql);

        $result = pg_execute($conn, "create_geographical_area_description_period", array(
            $this->geographical_area_description_period_sid, $this->geographical_area_sid, $this->geographical_area_id,
            $this->validity_start_date, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));

        # Create the geographical_area description record
        $sql = "INSERT INTO geographical_area_descriptions_oplog (
            geographical_area_description_period_sid, geographical_area_sid, geographical_area_id,
            language_id, description, operation, operation_date, workbasket_id, status, workbasket_item_id)
        VALUES ($1, $2, $3, 'EN', $4, $5, $6, $7, $8, $9)";

        pg_prepare($conn, "create_geographical_area_description", $sql);

        $result = pg_execute($conn, "create_geographical_area_description", array(
            $this->geographical_area_description_period_sid, $this->geographical_area_sid, $this->geographical_area_id,
            $this->description, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
        //die();

    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT * FROM geographical_areas WHERE geographical_area_id = $1";
        pg_prepare($conn, "geographical_area_exists", $sql);
        $result = pg_execute($conn, "geographical_area_exists", array($this->geographical_area_id));
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

    public function set_properties($geographical_area_sid, $geographical_area_id, $description, $geographical_code, $validity_start_date, $validity_end_date)
    {
        $this->geographical_area_sid = $geographical_area_sid;
        $this->geographical_area_id = $geographical_area_id;
        $this->description = $description;
        $this->geographical_code = $geographical_code;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;
    }


    public function delete_member()
    {
        global $conn;
        $application = new application;

        # Need to get the valid start date
        $sql = "SELECT validity_start_date, validity_end_date FROM geographical_area_memberships WHERE geographical_area_group_sid = $2
 AND geographical_area_sid = $1 ORDER BY 1 DESC LIMIT 1";
        pg_prepare($conn, "get_start_date", $sql);
        $result = pg_execute($conn, "get_start_date", array($this->geographical_area_sid, $this->geographical_area_group_sid));
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->validity_start_date = $row['validity_start_date'];
                $this->validity_end_date = $row['validity_end_date'];
            }
        }

        # Now insert the new record (with an update type)
        $sql = "INSERT INTO geographical_area_memberships_oplog (geographical_area_sid, geographical_area_group_sid,
 validity_start_date, validity_end_date, operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6)";
        pg_prepare($conn, "delete_member", $sql);
        $this->operation = "D";
        $this->operation_date = $application->get_operation_date();
        $result = pg_execute($conn, "delete_member", array(
            $this->geographical_area_sid, $this->geographical_area_group_sid,
            $this->validity_start_date, $this->validity_end_date,
            $this->operation, $this->operation_date
        ));
    }


    public function terminate_member()
    {
        global $conn;
        $application = new application;

        # Need to get the valid start date
        $sql = "SELECT validity_start_date FROM geographical_area_memberships WHERE geographical_area_group_sid = $2
 AND geographical_area_sid = $1 ORDER BY 1 DESC LIMIT 1";
        pg_prepare($conn, "get_start_date", $sql);
        $result = pg_execute($conn, "get_start_date", array($this->geographical_area_sid, $this->geographical_area_group_sid));
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->validity_start_date = $row['validity_start_date'];
            }
        }

        # Now insert the new record (with an update type)
        $sql = "INSERT INTO geographical_area_memberships_oplog (geographical_area_sid, geographical_area_group_sid,
 validity_start_date, validity_end_date, operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6)";
        pg_prepare($conn, "terminate_member", $sql);
        $this->operation = "U";
        $this->operation_date = $application->get_operation_date();
        $result = pg_execute($conn, "terminate_member", array(
            $this->geographical_area_sid, $this->geographical_area_group_sid,
            $this->validity_start_date, $this->validity_end_date,
            $this->operation, $this->operation_date
        ));
    }


    public function get_non_members()
    {
        global $conn;
        $sql = "SELECT geographical_area_sid, geographical_area_id, description FROM ml.ml_geographical_areas WHERE geographical_code = '0'
 AND geographical_area_sid NOT IN
 (
 SELECT geographical_area_sid FROM geographical_area_memberships gam
 WHERE gam.validity_end_date IS NULL
 AND gam.geographical_area_group_sid = (SELECT geographical_area_sid
 FROM geographical_areas WHERE geographical_area_id = '" . $this->geographical_area_id . "')
 )
 ORDER BY 2";
        #p ($sql);
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $geographical_area = new geographical_area;
                $geographical_area->geographical_area_id = $row['geographical_area_id'];
                $geographical_area->geographical_area_sid = $row['geographical_area_sid'];
                $geographical_area->description = $row['description'];
                array_push($temp, $geographical_area);
            }
            $this->non_members = $temp;
        }
    }



    public function delete_description()
    {
        global $conn;
        $application = new application;

        # Before I can delete anything, I need to retrieve the data, so that a "D" type instruction
        # with full data can be sent
        $sql = "SELECT gad.geographical_area_sid, gad.geographical_area_id, gad.description, gadp.validity_start_date,
 gadp.validity_end_date FROM geographical_area_descriptions gad, geographical_area_description_periods gadp
 WHERE gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
 AND gad.geographical_area_description_period_sid = $1";
        pg_prepare($conn, "get_description", $sql);
        $this->operation = "D";
        $this->operation_date = $application->get_operation_date();
        $result = pg_execute($conn, "get_description", array($this->geographical_area_description_period_sid));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->geographical_area_sid = $row[0];
            $this->geographical_area_id = $row[1];
            $this->description = $row[2];
            $this->validity_start_date = $row[3];
            $this->validity_end_date = $row[4];
        } else {
            exit();
        }
        # The I can do the deletes, which are actually not deletes, but inserts with a type of "D"
        # I need an instruction for both the period and the description
        $sql = "INSERT INTO geographical_area_description_periods_oplog (geographical_area_description_period_sid, geographical_area_sid, 
 validity_start_date, geographical_area_id, validity_end_date, operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "delete_description_period", $sql);
        pg_execute($conn, "delete_description_period", array(
            $this->geographical_area_description_period_sid, $this->geographical_area_sid,
            $this->validity_start_date, $this->geographical_area_id, $this->validity_end_date, $this->operation, $this->operation_date
        ));

        $sql = "INSERT INTO geographical_area_descriptions_oplog (geographical_area_description_period_sid, language_id, geographical_area_sid, 
 geographical_area_id, operation, operation_date) VALUES ($1, 'EN', $2, $3, $4, $5)";
        pg_prepare($conn, "delete_description_period", $sql);
        pg_execute($conn, "delete_description_period", array(
            $this->geographical_area_description_period_sid, $this->geographical_area_sid,
            $this->geographical_area_id, $this->operation, $this->operation_date
        ));
    }

    function update_description($geographical_area_description_period_sid, $description)
    {
        global $conn;
        $sql = "UPDATE geographical_area_descriptions_oplog SET description = $1 WHERE geographical_area_description_period_sid = $2";
        pg_prepare($conn, "geographical_area_description_update", $sql);
        pg_execute($conn, "geographical_area_description_update", array($description, $geographical_area_description_period_sid));
    }



    function add_member($geographical_area_group_sid, $geographical_area_id, $geographical_area_sid, $validity_start_date)
    {

        global $conn;
        $application = new application;
        $this->operation = "C";
        $this->operation_date = $application->get_operation_date();

        $sql = "INSERT INTO geographical_area_memberships_oplog (geographical_area_group_sid, geographical_area_sid, 
 validity_start_date, operation, operation_date) VALUES ($1, $2, $3, $4, $5);";


        pg_prepare($conn, "geographical_area_add_member", $sql);
        pg_execute($conn, "geographical_area_add_member", array(
            $geographical_area_group_sid, $geographical_area_sid,
            $validity_start_date, $this->operation, $this->operation_date
        ));
    }



    function insert_description($geographical_area_id, $geographical_area_sid, $validity_start_date, $description)
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $geographical_area_description_period_sid = $application->get_next_geographical_area_description_period();
        $operation_date = $application->get_operation_date();

        $this->quota_order_number_id = $geographical_area_id;
        $this->geographical_area_sid = $geographical_area_sid;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->geographical_area_description_period_sid = $geographical_area_description_period_sid;

        #$errors = $this->conflict_check();
        $errors = [];
        #h1 (count($errors));
        #exit();
        if (count($errors) > 0) {
            /*foreach ($errors as $error) {
 h1 ($error);
 }
 exit();*/
            return ($errors);
        } else {
            # Insert the geographical area description period
            $sql = "INSERT INTO geographical_area_description_periods_oplog
 (geographical_area_description_period_sid, geographical_area_sid, geographical_area_id, validity_start_date, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6)";
            pg_prepare($conn, "geographical_area_description_period_insert", $sql);
            pg_execute($conn, "geographical_area_description_period_insert", array(
                $geographical_area_description_period_sid, $geographical_area_sid,
                $geographical_area_id, $validity_start_date, $operation, $operation_date
            ));

            # Insert the geographical area description
            $sql = "INSERT INTO geographical_area_descriptions_oplog
 (geographical_area_description_period_sid, language_id, geographical_area_sid, geographical_area_id, description, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7)";
            pg_prepare($conn, "geographical_area_description_insert", $sql);
            pg_execute($conn, "geographical_area_description_insert", array(
                $geographical_area_description_period_sid, "EN",
                $geographical_area_sid, $geographical_area_id, $description, $operation, $operation_date
            ));
            return (True);
        }
    }

    function get_description()
    {
        global $conn;
        $errors = array();
        $sql = "SELECT description FROM geographical_area_descriptions gad, geographical_area_description_periods gadp
 WHERE gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
 AND gad.geographical_area_sid = $1 ORDER BY validity_start_date DESC LIMIT 1";

        $stmt = "get_geo_desc" . $this->geographical_area_sid;
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->geographical_area_sid));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_array($result)) {
                    $this->description = $row["description"];
                }
            }
        }
    }

    function conflict_check()
    {
        global $conn;
        $errors = array();
        # First, check for items that start at the exact same start date, which is the real fail
        #h1 ($this->validity_start_date . $this->validity_end_date);
        $sql = "SELECT * FROM quota_definitions WHERE quota_order_number_id = $1 AND validity_start_date = $2";
        pg_prepare($conn, "quota_definition_conflict_check", $sql);
        $result = pg_execute($conn, "quota_definition_conflict_check", array($this->quota_order_number_id, $this->validity_start_date));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                array_push($errors, "Error scenario 1");
            }
        }

        # Second, check all definitions on this order number
        $sql = "SELECT * FROM quota_definitions WHERE quota_order_number_id = $1 ORDER BY validity_start_date DESC";
        pg_prepare($conn, "quota_definition_conflict_check2", $sql);
        $result = pg_execute($conn, "quota_definition_conflict_check2", array($this->quota_order_number_id));
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $quota_definition_sid = $row["quota_definition_sid"];
                $validity_start_date = $row["validity_start_date"];
                $validity_end_date = $row["validity_end_date"];
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
        $this->validity_start_date_day = get_cookie("geographical_area_validity_start_date_day");
        $this->validity_start_date_month = get_cookie("geographical_area_validity_start_date_month");
        $this->validity_start_date_year = get_cookie("geographical_area_validity_start_date_year");
        $this->description = get_cookie("geographical_area_description");
    }

    function populate_from_db()
    {
        global $conn;
        /*
 $sql = "SELECT gad.description, gadp.validity_start_date, gad.geographical_area_description_period_sid,
 gad.geographical_area_sid, gad.geographical_area_id
 FROM geographical_area_descriptions gad, geographical_area_description_periods gadp
 WHERE gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
 AND gad.geographical_area_description_period_sid = $1";
 */
        $sql = "select geographical_area_sid, geographical_area_id, description, geographical_code, validity_start_date, validity_end_date, parent_geographical_area_group_sid
 from ml.ml_geographical_areas mga where geographical_area_sid = $1;";
        $stmt = "get_geographical_area" . strval($this->geographical_area_sid);

        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->geographical_area_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->geographical_area_sid = $row[0];
            $this->geographical_area_id = $row[1];
            $this->description = $row[2];
            $this->geographical_code = $row[3];
            $this->validity_start_date = $row[4];
            $this->validity_end_date = $row[5];
            $this->parent_geographical_area_group_sid = $row[6];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            $this->get_descriptions();
            $this->get_members();
            $this->get_rules_of_origin_schemes();
            $this->get_descriptive_fields();
            return (true);
        } else {
            return (false);
        }
    }

    public function get_rules_of_origin_schemes()
    {
        global $conn;
        $sql = "select roosm.validity_start_date, roosm.validity_end_date, roos.description
        from ml.rules_of_origin_scheme_memberships roosm, ml.rules_of_origin_schemes roos
        where roos.rules_of_origin_scheme_sid = roosm.rules_of_origin_scheme_sid
        and geographical_area_id = $1 and geographical_area_sid = $2";
        $stmt = "get_rules_of_origin_schemes";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->geographical_area_id, $this->geographical_area_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $member = new roo_member();
                $member->geographical_area_id = $this->geographical_area_id;
                $member->geographical_area_sid = $this->geographical_area_sid;
                $member->validity_start_date = $row['validity_start_date'];
                $member->validity_end_date = $row['validity_end_date'];
                $member->description = $row['description'];
                array_push($this->roo_members, $member);
            }
        }
        return ($row_count);
    }

    public function get_descriptions()
    {
        global $conn;
        $sql = "select gadp.validity_start_date, gad.description
        from geographical_area_description_periods gadp, geographical_area_descriptions gad
        where gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
        and gad.geographical_area_sid = $1
        order by gadp.validity_start_date desc;";
        $stmt = "get_descriptions" . strval($this->geographical_area_sid);
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->geographical_area_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $description = new description($row['validity_start_date'], $row['description']);
                array_push($this->descriptions, $description);
            }
        }
        return ($row_count);
    }

    function clear_cookies()
    {
        setcookie("geographical_area_validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("geographical_area_description", "", time() + (86400 * 30), "/");
    }

    public function get_geographical_area_sid()
    {
        global $conn;
        $this->geographical_area_id = trim($this->geographical_area_id);
        $l = strlen($this->geographical_area_id);

        if (($l != 2) and ($l != 4)) {
            return (Null);
        }
        $sql = "select geographical_area_sid from geographical_areas where geographical_area_id = $1
 order by validity_start_date desc limit 1";
        pg_prepare($conn, "get_geographical_area_sid", $sql);
        $result = pg_execute($conn, "get_geographical_area_sid", array($this->geographical_area_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->geographical_area_sid = $row[0];
        }
        return ($this->geographical_area_sid);
    }

    function validate()
    {
        global $conn;


        $sql = "select measure_type_id from measure_types where measure_type_id = $1
 and validity_end_date is null;;";
        pg_prepare($conn, "validate_measure_type", $sql);
        $result = pg_execute($conn, "validate_measure_type", array($this->measure_type_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $ret = true;
        } else {
            $ret = false;
        }
        return ($ret);
    }

    public function get_descriptive_fields()
    {
        global $application;
        $application->get_geographical_codes();
        $this->geographical_code_description = $application->geographical_codes[$this->geographical_code]->string;
        /*foreach ($this->application_codes as $item) {
            if ($item->id == $this->application_code) {
                $this->application_code_description = $item->string;
            }
        }*/
    }
}
