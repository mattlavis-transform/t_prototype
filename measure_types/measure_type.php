<?php
class measure_type
{
    // Class properties and methods go here
    public $measure_type_id = "";
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $trade_movement_code = "";
    public $measure_component_applicable_code = "";
    public $order_number_capture_code = "";
    public $measure_explosion_level = "";
    public $measure_type_series_id = "";
    public $description = "";
    public $is_quota = False;
    public $validity_start_date_day = "";
    public $validity_start_date_month = "";
    public $validity_start_date_year = "";
    public $validity_end_date_day = "";
    public $validity_end_date_month = "";
    public $validity_end_date_year = "";
    public $trade_movement_code_description = "";
    public $priority_code_description = "";
    public $measure_component_applicable_code_description = "";
    public $origin_dest_code_description = "";
    public $order_number_capture_code_description = "";
    public $id_disabled = "";
    public $validity_start_date_string = "";
    public $validity_end_date_string = "";
    public $measure_type_series_id_url = "";

    public $measure_types = array();

    public function __construct()
    {
        $this->get_measure_type_series();

        $this->trade_movement_codes = array();
        array_push($this->trade_movement_codes, new simple_object("0", "Import measure type", "Import"));
        array_push($this->trade_movement_codes, new simple_object("1", "Export measure type", "Export"));
        array_push($this->trade_movement_codes, new simple_object("2", "Import or export measure type", "Bi-directional"));

        $this->measure_component_applicable_codes = array();
        array_push($this->measure_component_applicable_codes, new simple_object("0", "Measure components MAY be applied", "Optional", "", true));
        array_push($this->measure_component_applicable_codes, new simple_object("1", "Measure components MUST be applied", "Mandatory", "", true));
        array_push($this->measure_component_applicable_codes, new simple_object("2", "Measure components MUST NOT be applied", "Not permitted", "", true));

        $this->order_number_capture_codes = array();
        array_push($this->order_number_capture_codes, new simple_object("1", "Mandatory - an order number MUST be supplied", "Mandatory", "", true));
        array_push($this->order_number_capture_codes, new simple_object("2", "Not permitted - an order number MUST NOT be supplied", "Not permitted", "", true));
    }

    public function get_parameters()
    {
        global $application;
        global $error_handler;

        $this->measure_type_id = trim(get_querystring("measure_type_id"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                $ret = $this->populate_from_db();
                if (!$ret) {
                    h1("An error has occurred - no such measure type");
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
        from measure_types_oplog
        where measure_type_id = $1
        union
        select operation, operation_date,
        null as validity_start_date, null as validity_end_date, status, description, '1' as object_precedence
        from measure_type_descriptions_oplog
        where measure_type_id = $1)
        select operation, operation_date, validity_start_date, validity_end_date, status, description
        from cte order by operation_date desc, object_precedence desc;";
        $stmt = "stmt_1";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->measure_type_id));
        if ($result) {
            $this->versions = $result;
            return;
            $row_count = pg_num_rows($result);
            if (($row_count > 0) && (pg_num_rows($result))) {
                while ($row = pg_fetch_array($result)) {
                    $version = new measure_type();
                    $version->validity_start_date = $row["validity_start_date"];
                    $version->validity_end_date = $row["validity_start_date"];
                    $version->validity_start_date = $row["validity_start_date"];
                    $version->validity_start_date = $row["validity_start_date"];
                    array_push($this->versions, $version);
                }
            }
        }
    }

    // Validate form
    function validate_form()
    {
        global $application;
        $errors = array();
        $this->measure_type_id = get_formvar("measure_type_id", "", True);
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

        $this->trade_movement_code = get_formvar("trade_movement_code", "", True);
        $this->priority_code = 1;
        $this->measure_component_applicable_code = get_formvar("measure_component_applicable_code", "", True);
        $this->origin_dest_code = $this->trade_movement_code;
        $this->order_number_capture_code = get_formvar("order_number_capture_code", "", True);
        $this->measure_type_series_id = get_formvar("measure_type_series_id", "", True);
        if ($this->trade_movement_code == '1') {
            $this->measure_explosion_level = 8;
        } else {
            $this->measure_explosion_level = 10;
        }
        $this->set_dates();

        # Check on the measure type id
        if (strlen($this->measure_type_id) != 3) {
            array_push($errors, "measure_type_id");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($application->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "measure_type_exists");
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

        # Check on the measure type series ID
        if ($this->measure_type_series_id == "") {
            array_push($errors, "measure_type_series_id");
        }

        # Check on the trade movement code
        if ($this->trade_movement_code == "") {
            array_push($errors, "trade_movement_code");
        }

        # Check on the measure_component_applicable_code
        if ($this->measure_component_applicable_code == "") {
            array_push($errors, "measure_component_applicable_code");
        }

        # Check on the order_number_capture_code
        if ($this->order_number_capture_code == "") {
            array_push($errors, "order_number_capture_code");
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
            $url = "create_edit.html?err=1&mode=" . $application->mode . "&measure_type_id=" . $this->measure_type_id;
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

    private function get_descriptive_fields()
    {
        // Trade movement code
        foreach ($this->trade_movement_codes as $item) {
            if ($item->id == $this->trade_movement_code) {
                $this->trade_movement_code_description = $item->string;
            }
        }
        // Measure component applicable code
        foreach ($this->measure_component_applicable_codes as $item) {
            if ($item->id == $this->measure_component_applicable_code) {
                $this->measure_component_applicable_code_description = $item->string;
            }
        }
        // Order number capture code
        foreach ($this->order_number_capture_codes as $item) {
            if ($item->id == $this->order_number_capture_code) {
                $this->order_number_capture_code_description = $item->string;
            }
        }
    }
    
    public function get_measure_type_series()
    {
        global $conn;
        $sql = "SELECT mts.measure_type_series_id, mtsd.description FROM measure_type_series mts, measure_type_series_descriptions mtsd
        WHERE mts.measure_type_series_id = mtsd.measure_type_series_id
        AND mts.validity_end_date IS NULL
        ORDER BY 1";
        #p ($sql);
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $measure_type_series = new measure_type_series;
                $measure_type_series->measure_type_series_id = $row['measure_type_series_id'];
                $measure_type_series->description = $row['description'];
                array_push($temp, $measure_type_series);
            }
            $this->measure_type_series = $temp;
        }
    }

    public function set_properties(
        $measure_type_id,
        $validity_start_date,
        $validity_end_date,
        $trade_movement_code,
        $priority_code,
        $measure_component_applicable_code,
        $origin_dest_code,
        $order_number_capture_code,
        $measure_explosion_level,
        $measure_type_series_id,
        $description,
        $is_quota
    ) {
        $this->measure_type_id = $measure_type_id;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;
        $this->trade_movement_code = $trade_movement_code;
        $this->priority_code = $priority_code;
        $this->measure_component_applicable_code = $measure_component_applicable_code;
        $this->origin_dest_code = $origin_dest_code;
        $this->order_number_capture_code = $order_number_capture_code;
        $this->measure_explosion_level = $measure_explosion_level;
        $this->measure_type_series_id = $measure_type_series_id;
        $this->description = $description;
        $this->is_quota = $is_quota;
        $this->get_descriptive_fields();
    }

    function populate_from_cookies()
    {
        //h1 ("Populating from cookies");
        $this->measure_type_id = get_cookie("measure_type_id");
        $this->validity_start_date_day = get_cookie("validity_start_date_day");
        $this->validity_start_date_month = get_cookie("validity_start_date_month");
        $this->validity_start_date_year = get_cookie("validity_start_date_year");
        $this->validity_start_date_string = get_cookie("validity_start_date_string");

        $this->validity_end_date_day = get_cookie("validity_end_date_day");
        $this->validity_end_date_month = get_cookie("validity_end_date_month");
        $this->validity_end_date_year = get_cookie("validity_end_date_year");
        $this->validity_end_date_string = get_cookie("validity_end_date_string");

        $this->description = get_cookie("description");
        $this->trade_movement_code = get_cookie("trade_movement_code");
        $this->measure_component_applicable_code = get_cookie("measure_component_applicable_code");
        $this->order_number_capture_code = get_cookie("order_number_capture_code");
        $this->measure_type_series_id = get_cookie("measure_type_series_id");
        $this->id_disabled = false;
    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT measure_type_id FROM measure_types WHERE measure_type_id = $1";
        pg_prepare($conn, "measure_type_exists", $sql);
        $result = pg_execute($conn, "measure_type_exists", array($this->measure_type_id));
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

    function create()
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
        if ($this->validity_start_date == "") {
            $this->validity_start_date = Null;
        }
        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }

        $status = 'awaiting approval';
        $sql = "INSERT INTO measure_types_oplog (measure_type_id, validity_start_date,
        validity_end_date, trade_movement_code, priority_code,
        measure_component_applicable_code, origin_dest_code,
        order_number_capture_code, measure_explosion_level, measure_type_series_id,
        operation, operation_date, workbasket_id, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14)
        RETURNING oid;";

        pg_prepare($conn, "create_measure_type", $sql);

        $result = pg_execute($conn, "create_measure_type", array(
            $this->measure_type_id, $this->validity_start_date,
            $this->validity_end_date, $this->trade_movement_code, $this->priority_code,
            $this->measure_component_applicable_code, $this->origin_dest_code,
            $this->order_number_capture_code, $this->measure_explosion_level, $this->measure_type_series_id,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }
        
        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "measure_type", $status, $operation, $operation_date);

        // Then upate the measure type record with oid of the workbasket item record
        $sql = "UPDATE measure_types_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "update_measure_type", $sql);
        $result = pg_execute($conn, "update_measure_type", array(
            $workbasket_item_id, $oid
        ));
 
        $sql = "INSERT INTO measure_type_descriptions_oplog (measure_type_id, language_id, description,
        operation, operation_date, workbasket_id, status, workbasket_item_id)
        VALUES ($1, 'EN', $2, $3, $4, $5, $6, $7)
        RETURNING oid;";

        pg_prepare($conn, "create_measure_type_description", $sql);

        $result = pg_execute($conn, "create_measure_type_description", array(
            $this->measure_type_id, $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
    }

    function create_update($operation)
    {
        global $conn;
        $application = new application;
        $operation_date = $application->get_operation_date();
        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }

        $status = 'awaiting approval';
        $sql = "INSERT INTO measure_types_oplog (measure_type_id, validity_start_date,
        validity_end_date, trade_movement_code, priority_code,
        measure_component_applicable_code, origin_dest_code,
        order_number_capture_code, measure_explosion_level, measure_type_series_id,
        operation, operation_date, workbasket_id, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14)
        RETURNING oid;";

        pg_prepare($conn, "stmt_1", $sql);

        $result = pg_execute($conn, "stmt_1", array(
            $this->measure_type_id, $this->validity_start_date,
            $this->validity_end_date, $this->trade_movement_code, $this->priority_code,
            $this->measure_component_applicable_code, $this->origin_dest_code,
            $this->order_number_capture_code, $this->measure_explosion_level, $this->measure_type_series_id,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }
        
        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "measure_type", $status, $operation, $operation_date);

        // Then upate the measure type record with oid of the workbasket item record
        $sql = "UPDATE measure_types_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "stmt_2", $sql);
        $result = pg_execute($conn, "stmt_2", array(
            $workbasket_item_id, $oid
        ));
 
        $sql = "INSERT INTO measure_type_descriptions_oplog (measure_type_id, language_id, description,
        operation, operation_date, workbasket_id, status, workbasket_item_id)
        VALUES ($1, 'EN', $2, $3, $4, $5, $6, $7)
        RETURNING oid;";
        pg_prepare($conn, "stmt_3", $sql);
        $result = pg_execute($conn, "stmt_3", array(
            $this->measure_type_id, $this->description,
            $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "SELECT mtd.description as description, validity_start_date, validity_end_date, trade_movement_code,
        priority_code, measure_component_applicable_code, origin_dest_code,
        order_number_capture_code, measure_explosion_level, mt.measure_type_series_id, mtsd.description as measure_type_series_description
        FROM measure_types mt, measure_type_descriptions mtd, measure_type_series_descriptions mtsd
        WHERE mt.measure_type_id = mtd.measure_type_id
        and mt.measure_type_series_id = mtsd.measure_type_series_id
        AND mt.measure_type_id = $1";
        //prend ($sql);
        pg_prepare($conn, "get_measure_type", $sql);
        $result = pg_execute($conn, "get_measure_type", array($this->measure_type_id));
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
            $this->trade_movement_code = $row[3];
            $this->measure_component_applicable_code = $row[5];
            $this->order_number_capture_code = $row[7];
            $this->measure_type_series_id = $row[9];
            $this->measure_type_series_description = $row[10];

            $this->id_disabled = true;
            $this->get_descriptive_fields();
            return (true);
        } else {
            return (false);
        }
    }

    public function clear_cookies()
    {
        setcookie("measure_type_id", "", time() + (86400 * 30), "/");
        setcookie("description", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("validity_start_date_string", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_year", "", time() + (86400 * 30), "/");
        setcookie("validity_end_date_string", "", time() + (86400 * 30), "/");
        setcookie("trade_movement_code", "", time() + (86400 * 30), "/");
        setcookie("measure_component_applicable_code", "", time() + (86400 * 30), "/");
        setcookie("order_number_capture_code", "", time() + (86400 * 30), "/");
        setcookie("measure_type_series_id", "", time() + (86400 * 30), "/");
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
 AND m.measure_type_id = $1
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
 AND m.measure_type_id = $1
 AND (	
 (r.validity_end_date > $2 AND m.validity_end_date IS NULL AND r.effective_end_date IS NULL)
 OR
 (r.effective_end_date > $2 AND m.validity_end_date IS NULL)
 OR
 (m.validity_end_date > $2 OR (m.validity_end_date IS NULL AND r.effective_end_date IS NULL AND r.validity_end_date IS NULL))
 )";
        pg_prepare($conn, "business_rule_mt3", $sql);
        $result = pg_execute($conn, "business_rule_mt3", array($this->measure_type_id, $this->validity_end_date));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $succeeds = false;
            }
        }
        return ($succeeds);
    }

    function validate()
    {
        global $conn;

        if ($this->measure_type_id == 0) {
            return (false);
        }

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
}
