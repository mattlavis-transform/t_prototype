<?php
class certificate
{
    // Class properties and methods go here
    public $certificate_code        = "";
    public $certificate_type_code   = "";
    public $validity_start_date     = "";
    public $validity_end_date       = "";
    public $description             = "";
    public $validity_start_date_day      = "";
    public $validity_start_date_month    = "";
    public $validity_start_date_year     = "";
    public $validity_end_date_day        = "";
    public $validity_end_date_month      = "";
    public $validity_end_date_year       = "";

    public $certificates = array();
    public $descriptions = array();

    public function __construct()
    {
        $this->get_certificate_types();
    }

    public function get_descriptions()
    {
        global $conn;
        $sql = "select cdp.validity_start_date, cd.description
        from certificate_description_periods cdp, certificate_descriptions cd
        where cd.certificate_description_period_sid = cdp.certificate_description_period_sid
        and cd.certificate_type_code = $1 and cd.certificate_code = $2
        order by cdp.validity_start_date desc;";
        pg_prepare($conn, "get_descriptions", $sql);
        $result = pg_execute($conn, "get_descriptions", array($this->certificate_type_code, $this->certificate_code));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $description = new description($row['validity_start_date'], $row['description']);
                array_push($this->descriptions, $description);
            }
        }
        return ($row_count);
    }

    function validate_form()
    {
        global $application;
        $errors = array();
        $this->certificate_type_code = strtoupper(get_formvar("certificate_type_code", "", True));
        $this->certificate_code = strtoupper(get_formvar("certificate_code", "", True));
        
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

        //prend ($_REQUEST);
        # Check on the certificate_type_code
        if (strlen($this->certificate_type_code) != 1) {
            array_push($errors, "certificate_type_code");
        }

        # Check on the additional code
        if (strlen($this->certificate_code) != 3) {
            array_push($errors, "certificate_code");
        }

        # If we are creating, check that the measure type ID does not already exist
        if ($application->mode == "insert") {
            if ($this->exists()) {
                array_push($errors, "certificate_code_exists");
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

        $this->certificate_type_code = trim(get_querystring("certificate_type_code"));
        $this->certificate_code = trim(get_querystring("certificate_code"));
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
                    h1("An error has occurred - no such certificate");
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
        from certificates_oplog
        where certificate_type_code = $1 and certificate_code = $2
        union
        select ct.operation, ct.operation_date,
        validity_start_date, null as validity_end_date, ct.status, description, '1' as object_precedence
        from certificate_descriptions_oplog ct, certificate_description_periods_oplog ctp
        where ct.certificate_description_period_sid = ctp.certificate_description_period_sid 
        and ct.certificate_type_code = $1 and ct.certificate_code = $2
        )
        select operation, operation_date, validity_start_date, validity_end_date, status, description
        from cte order by operation_date desc, object_precedence desc;";
        $stmt = "stmt_1";
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->certificate_type_code, $this->certificate_code));
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

    public function get_specific_description($validity_start_date)
    {
        global $conn;
        if ($this->validity_start_date == null) {
            $sql = "select description from certificate_description_periods cdp, certificate_descriptions cd
            where cd.certificate_description_period_sid = cdp.certificate_description_period_sid
            and cd.certificate_type_code = $1 and cd.certificate_code = $2 order by validity_start_date desc limit 1
            ";
    
            pg_prepare($conn, "get_specific_description", $sql);
    
            $result = pg_execute($conn, "get_specific_description", array($this->certificate_type_code, $this->certificate_code));
        } else {
            $sql = "select description from certificate_description_periods cdp, certificate_descriptions cd
            where cd.certificate_description_period_sid = cdp.certificate_description_period_sid
            and cd.certificate_type_code = $1 and cd.certificate_code = $2 and validity_start_date = $3;
            ";
    
            pg_prepare($conn, "get_specific_description", $sql);
    
            $result = pg_execute($conn, "get_specific_description", array($this->certificate_type_code, $this->certificate_code, $this->validity_start_date));
        }
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_array($result);
            $this->description = $row['description'];
            return (true);
        }
        return (false);
    }

    public function get_certificate_types()
    {
        global $conn;
        $sql = "SELECT ft.certificate_type_code, description FROM certificate_types ft, certificate_type_descriptions ftd
        WHERE ft.certificate_type_code = ftd.certificate_type_code
        AND validity_end_date IS NULL ORDER BY 1";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $certificate_type       = new certificate_type;
                $certificate_type->certificate_type_code	= $row['certificate_type_code'];
                $certificate_type->description      	    = $row['description'];
                array_push($temp, $certificate_type);
            }
            $this->certificate_types = $temp;
        }
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
        $this->certificate_code						= $certificate_code;
        $this->validity_start_date				    = $validity_start_date;
        $this->validity_end_date				    = $validity_end_date;
        $this->trade_movement_code				    = $trade_movement_code;
        $this->priority_code				        = $priority_code;
        $this->measure_component_applicable_code    = $measure_component_applicable_code;
        $this->origin_dest_code				        = $origin_dest_code;
        $this->order_number_capture_code			= $order_number_capture_code;
        $this->measure_explosion_level				= $measure_explosion_level;
        $this->certificate_series_id				= $certificate_series_id;
        $this->description				        	= $description;
        $this->description_truncated        	    = substr($description, 0, 75);
        $this->is_quota				        		= $is_quota;
    }

    function populate_from_cookies()
    {
        #$this->certificate_code						    = get_cookie("certificate_code");
        #$this->certificate_type_code						= get_cookie("certificate_type_code");
        $this->validity_start_date_day					= get_cookie("certificate_validity_start_date_day");
        $this->validity_start_date_month					= get_cookie("certificate_validity_start_date_month");
        $this->validity_start_date_year					= get_cookie("certificate_validity_start_date_year");
        $this->validity_end_date_day						= get_cookie("certificate_validity_end_date_day");
        $this->validity_end_date_month					= get_cookie("certificate_validity_end_date_month");
        $this->validity_end_date_year					= get_cookie("certificate_validity_end_date_year");
        $this->description							= get_cookie("certificate_description");
        $this->heading          					= "Create new certificate";
        $this->disable_certificate_code_field		= "";
    }

    function exists()
    {
        global $conn;
        $exists = false;
        $sql = "SELECT * FROM certificates WHERE certificate_code = $1 AND certificate_type_code = $2";
        pg_prepare($conn, "certificate_exists", $sql);
        $result = pg_execute($conn, "certificate_exists", array($this->certificate_code, $this->certificate_type_code));
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
            $this->validity_start_date	= to_date_string($this->validity_start_date_day,	$this->validity_start_date_month, $this->validity_start_date_year);
        }

        if (($this->validity_end_date_day == "") || ($this->validity_end_date_month == "") || ($this->validity_end_date_year == "")) {
            $this->validity_end_date = Null;
        } else {
            $this->validity_end_date	= to_date_string($this->validity_end_date_day, $this->validity_end_date_month, $this->validity_end_date_year);
        }
    }

    function create()
    {
        global $conn, $application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
        $this->certificate_description_period_sid  = $application->get_next_certificate_description_period();

        if ($this->validity_end_date == "") {
            $this->validity_end_date = Null;
        }

        $status = 'awaiting approval';
        # Create the certificate record
        $sql = "INSERT INTO certificates_oplog (certificate_code, certificate_type_code, 
        validity_start_date, operation, operation_date, workbasket_id, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7)
            RETURNING oid;";
        pg_prepare($conn, "create_certificate", $sql);
        $result = pg_execute($conn, "create_certificate", array(
            $this->certificate_code, $this->certificate_type_code,
            $this->validity_start_date, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
        }

        $workbasket_item_id = $application->session->workbasket->insert_workbasket_item($oid, "certificate", $status, $operation, $operation_date);

        // Then upate the certificate record with oid of the workbasket item record
        $sql = "UPDATE certificates_oplog set workbasket_item_id = $1 where oid = $2";
        pg_prepare($conn, "update_certificate", $sql);
        $result = pg_execute($conn, "update_certificate", array(
            $workbasket_item_id, $oid
        ));
 
        # Create the certificate description period record
        $sql = "INSERT INTO certificate_description_periods_oplog (certificate_description_period_sid, certificate_code,
        certificate_type_code, validity_start_date, operation, operation_date, workbasket_id, status, workbasket_item_id)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
            RETURNING oid;";
        pg_prepare($conn, "create_certificate_description_period", $sql);
        $result = pg_execute($conn, "create_certificate_description_period", array(
            $this->certificate_description_period_sid, $this->certificate_code,
            $this->certificate_type_code, $this->validity_start_date, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
        //$application->session->workbasket->insert_workbasket_item($oid, "certificate_description_period", $status, $operation, $operation_date);

        # Create the certificate description record
        $sql = "INSERT INTO certificate_descriptions_oplog (certificate_description_period_sid, certificate_code,
        certificate_type_code, language_id, description, operation, operation_date, workbasket_id, status, workbasket_item_id)
        VALUES ($1, $2, $3, 'EN', $4, $5, $6, $7, $8, $9)
            RETURNING oid;";
        pg_prepare($conn, "create_certificate_description", $sql);
        $result = pg_execute($conn, "create_certificate_description", array(
            $this->certificate_description_period_sid, $this->certificate_code,
            $this->certificate_type_code, $this->description, $operation, $operation_date, $application->session->workbasket->workbasket_id, $status, $workbasket_item_id
        ));
        //die();
    }

    public function old_delete_description()
    {
        global $conn;
        $application = new application;

        # Before I can delete anything, I need to retrieve the data, so that a "D" type instruction
        # with full data can be sent
        $sql = "SELECT fd.certificate_type_code, fd.certificate_code, fd.description, fdp.validity_start_date,
        fdp.validity_end_date FROM certificate_descriptions fd, certificate_description_periods fdp
        WHERE fd.certificate_description_period_sid = fdp.certificate_description_period_sid
        AND fd.certificate_description_period_sid = $1";
        pg_prepare($conn, "get_description", $sql);
        $this->operation = "D";
        $this->operation_date = $application->get_operation_date();
        $result = pg_execute($conn, "get_description", array($this->certificate_description_period_sid));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->certificate_type_code  	= $row[0];
            $this->certificate_code  		= $row[1];
            $this->description  		= $row[2];
            $this->validity_start_date	= $row[3];
            $this->validity_end_date  	= $row[4];
        } else {
            exit();
        }
        # The I can do the deletes, which are actually not deletes, but inserts with a type of "D"
        # I need an instruction for both the period and the description
        $sql = "INSERT INTO certificate_description_periods_oplog (certificate_description_period_sid, certificate_type_code, 
        validity_start_date, certificate_code, validity_end_date, operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "delete_description_period", $sql);
        pg_execute($conn, "delete_description_period", array(
            $this->certificate_description_period_sid, $this->certificate_type_code,
            $this->validity_start_date, $this->certificate_code, $this->validity_end_date, $this->operation, $this->operation_date
        ));

        $sql = "INSERT INTO certificate_descriptions_oplog (certificate_description_period_sid, language_id, certificate_type_code, 
        certificate_code, operation, operation_date) VALUES ($1, 'EN', $2, $3, $4, $5)";
        pg_prepare($conn, "delete_description", $sql);
        pg_execute($conn, "delete_description", array(
            $this->certificate_description_period_sid, $this->certificate_type_code,
            $this->certificate_code, $this->operation, $this->operation_date
        ));
    }


    function get_start_date()
    {
        global $conn;
        $sql = "SELECT validity_start_date FROM certificates
        WHERE certificate_code = $1 AND certificate_type_code = $2 ORDER BY operation_date DESC LIMIT 1";
        pg_prepare($conn, "get_certificate_validity_start_date", $sql);
        $result = pg_execute($conn, "get_certificate_validity_start_date", array($this->certificate_code, $this->certificate_type_code));

        if ($result) {
            $row = pg_fetch_row($result);
            $d = $row[0];
            return (DateTime::createFromFormat('Y-m-d H:i:s', $d)->format('Y-m-d'));
        } else {
            return ("");
        }
    }


    function get_missing_details()
    {
        global $conn;
        $sql = "SELECT description, cdp.validity_start_date as period_validity_start_date, c.validity_start_date as c_validity_start_date
        FROM certificate_descriptions cd, certificate_description_periods cdp, certificates c
        WHERE cd.certificate_description_period_sid = cdp.certificate_description_period_sid
        AND c.certificate_code = cd.certificate_code
        AND c.certificate_type_code = cd.certificate_type_code
        AND c.certificate_code = cdp.certificate_code
        AND c.certificate_type_code = cdp.certificate_type_code
        AND cd.certificate_description_period_sid = $1";
        pg_prepare($conn, "get_missing_details", $sql);
        $result = pg_execute($conn, "get_missing_details", array($this->certificate_code, $this->certificate_type_code));

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

    function insert_description($certificate_code, $certificate_type_code, $validity_start_date, $description)
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $certificate_description_period_sid  = $application->get_next_certificate_description_period();
        $operation_date = $application->get_operation_date();

        $this->certificate_code = $certificate_code;
        $this->certificate_type_code = $certificate_type_code;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->certificate_description_period_sid = $certificate_description_period_sid;

        $this->f_validity_start_date = $this->get_start_date();

        # Insert the certificate
        $sql = "INSERT INTO certificates_oplog
        (certificate_type_code, certificate_code, validity_start_date, operation, operation_date)
        VALUES ($1, $2, $3, 'U', $4)";
        pg_prepare($conn, "certificate_insert", $sql);
        pg_execute($conn, "certificate_insert", array(
            $this->certificate_type_code,
            $this->certificate_code, $this->f_validity_start_date, $operation_date
        ));

        # Insert the certificate description period
        $sql = "INSERT INTO certificate_description_periods_oplog
        (certificate_description_period_sid, certificate_type_code, certificate_code, validity_start_date, operation, operation_date)
        VALUES ($1, $2, $3, $4, $5, $6)";
        pg_prepare($conn, "certificate_description_period_insert", $sql);
        pg_execute($conn, "certificate_description_period_insert", array(
            $this->certificate_description_period_sid, $this->certificate_type_code,
            $this->certificate_code, $this->validity_start_date, $operation, $operation_date
        ));

        # Insert the certificate description
        $sql = "INSERT INTO certificate_descriptions_oplog
        (certificate_description_period_sid, language_id, certificate_type_code, certificate_code, description, operation, operation_date)
        VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "certificate_description_insert", $sql);
        pg_execute($conn, "certificate_description_insert", array(
            $this->certificate_description_period_sid, "EN",
            $this->certificate_type_code, $this->certificate_code, $this->description, $operation, $operation_date
        ));
        return (True);
    }

    function update_description($certificate_code, $certificate_type_code, $validity_start_date, $description, $certificate_description_period_sid)
    {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $this->certificate_code = $certificate_code;
        $this->certificate_type_code = $certificate_type_code;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->certificate_description_period_sid = $certificate_description_period_sid;

        $this->f_validity_start_date = $this->get_start_date();

        # Insert the certificate
        $sql = "INSERT INTO certificates_oplog
        (certificate_type_code, certificate_code, validity_start_date, operation, operation_date)
        VALUES ($1, $2, $3, 'U', $4)";
        pg_prepare($conn, "certificate_insert", $sql);
        pg_execute($conn, "certificate_insert", array(
            $this->certificate_type_code,
            $this->certificate_code, $this->f_validity_start_date, $operation_date
        ));

        # Insert the certificate description period
        $sql = "INSERT INTO certificate_description_periods_oplog
        (certificate_description_period_sid, certificate_type_code, certificate_code, validity_start_date, operation, operation_date)
        VALUES ($1, $2, $3, $4, $5, $6)";
        pg_prepare($conn, "certificate_description_period_insert", $sql);
        pg_execute($conn, "certificate_description_period_insert", array(
            $certificate_description_period_sid, $certificate_type_code,
            $certificate_code, $validity_start_date, $operation, $operation_date
        ));

        # Insert the certificate description
        $sql = "INSERT INTO certificate_descriptions_oplog
        (certificate_description_period_sid, language_id, certificate_type_code, certificate_code, description, operation, operation_date)
        VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "certificate_description_insert", $sql);
        pg_execute($conn, "certificate_description_insert", array(
            $certificate_description_period_sid, "EN",
            $certificate_type_code, $certificate_code, $description, $operation, $operation_date
        ));
        return (True);
    }

    function delete_description()
    {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();

        # Get the missing details
        $this->get_missing_details();

        # Insert the certificate
        $sql = "INSERT INTO certificates_oplog
        (certificate_type_code, certificate_code, validity_start_date, operation, operation_date)
        VALUES ($1, $2, $3, 'U', $4)";
        pg_prepare($conn, "certificate_insert", $sql);
        pg_execute($conn, "certificate_insert", array(
            $this->certificate_type_code,
            $this->certificate_code, $this->validity_start_date, $operation_date
        ));

        # Insert the certificate description period
        $sql = "INSERT INTO certificate_description_periods_oplog
        (certificate_description_period_sid, certificate_type_code, certificate_code, validity_start_date, operation, operation_date)
        VALUES ($1, $2, $3, $4, $5, $6)";
        pg_prepare($conn, "certificate_description_period_insert", $sql);
        pg_execute($conn, "certificate_description_period_insert", array(
            $this->certificate_description_period_sid, $this->certificate_type_code,
            $this->certificate_code, $this->period_validity_start_date, $operation, $operation_date
        ));

        # Insert the certificate description
        $sql = "INSERT INTO certificate_descriptions_oplog
        (certificate_description_period_sid, language_id, certificate_type_code, certificate_code, description, operation, operation_date)
        VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "certificate_description_insert", $sql);
        pg_execute($conn, "certificate_description_insert", array(
            $this->certificate_description_period_sid, "EN",
            $this->certificate_type_code, $this->certificate_code, $this->description, $operation, $operation_date
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

        $sql = "INSERT INTO certificates_oplog (certificate_code, validity_start_date,
        validity_end_date, trade_movement_code, priority_code,
        measure_component_applicable_code, origin_dest_code,
        order_number_capture_code, measure_explosion_level, certificate_series_id,
        operation, operation_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)";

        pg_prepare($conn, "create_certificate", $sql);

        $result = pg_execute($conn, "create_certificate", array(
            $this->certificate_code, $this->validity_start_date,
            $this->validity_end_date, $this->trade_movement_code, $this->priority_code,
            $this->measure_component_applicable_code, $this->origin_dest_code,
            $this->order_number_capture_code, $this->measure_explosion_level, $this->certificate_series_id,
            $operation, $operation_date
        ));


        $sql = "INSERT INTO certificate_descriptions_oplog (certificate_code, language_id, description,
        operation, operation_date) VALUES ($1, 'EN', $2, $3, $4)";

        pg_prepare($conn, "create_certificate_description", $sql);

        $result = pg_execute($conn, "create_certificate_description", array(
            $this->certificate_code, $this->description,
            $operation, $operation_date
        ));
        #echo ($result);
        #exit();
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "SELECT cd.description, validity_start_date, validity_end_date, ctd.description as certificate_type_description
        FROM certificates c, certificate_descriptions cd, certificate_type_descriptions ctd
        WHERE c.certificate_code = cd.certificate_code
        and c.certificate_type_code = ctd.certificate_type_code 
        AND c.certificate_type_code = $1 AND c.certificate_code = $2";
        //h1 ($sql);
        pg_prepare($conn, "get_certificate", $sql);
        $result = pg_execute($conn, "get_certificate", array($this->certificate_type_code, $this->certificate_code));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->description  						= $row[0];
            $this->validity_start_date					= $row[1];
            $this->validity_start_date_day   				= date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month 				= date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year  				= date('Y', strtotime($this->validity_start_date));
            $this->validity_end_date					= $row[2];
            if ($this->validity_end_date == "") {
                $this->validity_end_date_day   					= "";
                $this->validity_end_date_month 					= "";
                $this->validity_end_date_year  					= "";
            } else {
                $this->validity_end_date_day   					= date('d', strtotime($this->validity_end_date));
                $this->validity_end_date_month 					= date('m', strtotime($this->validity_end_date));
                $this->validity_end_date_year  					= date('Y', strtotime($this->validity_end_date));
            }

            $this->certificate_type_description  						= $row[3];
            $this->certificate_heading					= "Edit measure type " . $this->certificate_code;
            $this->disable_certificate_code_field		= " disabled";
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
            $this->description  						= $row[2];
            $this->validity_start_date					= $row[3];
            $this->validity_start_date_day   				= date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month 				= date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year  				= date('Y', strtotime($this->validity_start_date));
            $this->certificate_heading					= "Edit measure type " . $this->certificate_code;
            $this->disable_certificate_code_field		= " disabled";
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

    function get_latest_description()
    {
        global $conn;
        $sql = "SELECT fd.description
        FROM certificate_description_periods fdp, certificate_descriptions fd
        WHERE fd.certificate_description_period_sid = fdp.certificate_description_period_sid
        AND fd.certificate_code = $1 AND fd.certificate_type_code = $2  
        ORDER BY fdp.validity_start_date DESC LIMIT 1";

        pg_prepare($conn, "get_latest_description", $sql);
        $result = pg_execute($conn, "get_latest_description", array($this->certificate_code, $this->certificate_type_code));
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
        AND m.certificate_code = $1
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
        AND m.certificate_code = $1
        AND (	
            (r.validity_end_date > $2 AND m.validity_end_date IS NULL AND r.effective_end_date IS NULL)
            OR
            (r.effective_end_date > $2 AND m.validity_end_date IS NULL)
            OR
            (m.validity_end_date > $2 OR (m.validity_end_date IS NULL AND r.effective_end_date IS NULL AND r.validity_end_date IS NULL))
        )";
        pg_prepare($conn, "business_rule_mt3", $sql);
        $result = pg_execute($conn, "business_rule_mt3", array($this->certificate_code, $this->validity_end_date));
        if ($result) {
            if (pg_num_rows($result) > 0) {
                $succeeds = false;
            }
        }
        return ($succeeds);
    }

    public function parse($s) {
        $this->code = trim($s);
        $hyphen_pos = strpos($this->code, "-");
        if ($hyphen_pos !== -1) {
            $this->code = trim(substr($this->code, 0, $hyphen_pos - 1));
        }
        if (strlen($this->code) == 4) {
            $this->certificate_type_code = substr($this->code, 0, 1);
            $this->certificate_code = substr($this->code, 1, 3);
        }
    }
}
