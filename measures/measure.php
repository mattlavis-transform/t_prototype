<?php
class measure
{
    // Class properties and methods go here
    public function __construct()
    {
        $this->geographical_area_description = "";
        $this->geographical_area_id = "";
        $this->assigned = False;
        $this->combined_duty = "";
        $this->duty_list = array();
        $this->measure_components = array();
        $this->measure_condition_components = array();
        $this->footnote_association_measures = array();
        $this->condition_list = array();
        $this->mega_list = array();
        $this->suppress = False;
        $this->marked = False;
        $this->significant_children = False;
        $this->measure_count = 0;
        $this->measure_type_count = 0;
        $this->additional_code_id = "";
        $this->additional_code_type_id = "";
        $this->measure_heading = "Temp";
        $this->siv_component_list = array();
        $this->measure_generating_regulation_id = "";
        $this->measure_generating_regulation_role = "";
        $this->goods_nomenclature_item_id = "";
        $this->additional_code_type_id = "";
        $this->additional_code_id = "";
        $this->validity_start_date = "";
        $this->validity_end_date = "";
        $this->entry_price_string = "";
        $this->perceived_value = 0;
        $this->measure_type_id = null;
        $this->commodity_codes = null;
        $this->additional_codes = null;
        $this->duty = null;
        $this->footnote_id = null;
        $this->commodity_code_list = array();
        $this->duties_same_for_all_commodities = null;

        $this->measure_components_xml = "";
        $this->measure_excluded_geographical_areas_xml = "";
        $this->measure_conditions_xml = "";
        $this->measure_partial_temporary_stops_xml = "";
        $this->footnote_association_measures_xml = "";
        $this->footnote_list = array();
    }


    public function get_parameters($description = false)
    {
        global $application;
        global $error_handler;

        $this->quota_order_number_sid = trim(get_querystring("quota_order_number_sid"));
        $this->quota_order_number_id = trim(get_querystring("quota_order_number_id"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                if ($description == false) {
                    $ret = $this->populate_from_db();
                } else {
                    //$ret = $this->get_specific_description($this->validity_start_date);
                    $a = 1;
                }
                if (!$ret) {
                    h1("An error has occurred - no such quota order number");
                    die();
                }
            } else {
                $this->populate_from_cookies();
            }
        }
        //pre ($this);
    }


    function set_dates()
    {
        if (($this->validity_start_day == "") || ($this->validity_start_month == "") || ($this->validity_start_year == "")) {
            $this->validity_start_date = Null;
        } else {
            $this->validity_start_date    = to_date_string($this->validity_start_day,    $this->validity_start_month, $this->validity_start_year);
        }

        if (($this->validity_end_day == "") || ($this->validity_end_month == "") || ($this->validity_end_year == "")) {
            $this->validity_end_date = Null;
        } else {
            $this->validity_end_date    = to_date_string($this->validity_end_day, $this->validity_end_month, $this->validity_end_year);
        }
    }

    public function clear_cookies()
    {
        setcookie("footnote_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_type_id", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_start_year", "", time() + (86400 * 30), "/");
        setcookie("footnote_description", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_day", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_month", "", time() + (86400 * 30), "/");
        setcookie("footnote_validity_end_year", "", time() + (86400 * 30), "/");
    }

    function validate_form_core()
    {
        global $application;
        $errors = array();

        //prend($_REQUEST);


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

        # Check on the validity start date
        $valid_start_date = checkdate($this->validity_start_date_month, $this->validity_start_date_day, $this->validity_start_date_year);
        if ($valid_start_date != 1) {
            array_push($errors, "validity_start_date");
        }

        # Check on the validity end date: must either be a valid date or blank
        /*
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
        */

        $this->measure_generating_regulation_id = get_formvar("measure_generating_regulation_id", "", True);
        if ($this->measure_generating_regulation_id == "") {
            array_push($errors, "measure_generating_regulation_id");
        }

        $this->measure_type_id = get_formvar("measure_type_id", "", True);
        if ($this->measure_type_id == "") {
            array_push($errors, "measure_type_id");
        }

        $this->geographical_area_id = get_formvar("geographical_area_id_countries", "", True);
        if ($this->geographical_area_id == "") {
            array_push($errors, "geographical_area_id");
        }

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
            $url = "./create_edit_commodities.html?mode=" . $application->mode;
        }
        header("Location: " . $url);
    }


    public function set_properties(
        $measure_sid,
        $goods_nomenclature_item_id,
        $quota_order_number_id,
        $validity_start_date,
        $validity_end_date,
        $geographical_area_id,
        $measure_type_id,
        $additional_code_type_id,
        $additional_code_id,
        $regulation_id_full,
        $measure_type_description = ""
    ) {
        $this->measure_sid = $measure_sid;
        $this->goods_nomenclature_item_id = $goods_nomenclature_item_id;
        $this->quota_order_number_id = $quota_order_number_id;
        $this->validity_start_date = $validity_start_date;
        $this->validity_end_date = $validity_end_date;
        $this->geographical_area_id = $geographical_area_id;
        $this->measure_type_id = $measure_type_id;
        $this->additional_code_type_id = $additional_code_type_id;
        $this->additional_code_id = $additional_code_id;
        $this->regulation_id_full = $regulation_id_full;
        $this->measure_type_description = $measure_type_description;
    }

    /* BEGIN MEASURE FUNCTIONS */
    public function insert_measure()
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
        $this->get_new_measure_sid();

        $sql = "INSERT INTO measures_oplog (
 measure_sid,
 measure_type_id,
 geographical_area_id,
 goods_nomenclature_item_id,
 validity_start_date,
 validity_end_date,
 measure_generating_regulation_role,
 measure_generating_regulation_id,
 justification_regulation_role,
 justification_regulation_id,
 stopped_flag,
 geographical_area_sid,
 goods_nomenclature_sid,
 ordernumber,
 additional_code_type_id,
 additional_code_id,
 additional_code_sid,
 reduction_indicator,
 export_refund_nomenclature_sid, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18, $19, $20, $21)";

        pg_prepare($conn, "measure_insert", $sql);
        pg_execute($conn, "measure_insert", array(
            $this->measure_sid,
            $this->measure_type_id,
            $this->geographical_area_id,
            $this->goods_nomenclature_item_id,
            $this->validity_start_date,
            $this->validity_end_date,
            $this->measure_generating_regulation_role,
            $this->measure_generating_regulation_id,
            $this->justification_regulation_role,
            $this->justification_regulation_id,
            $this->stopped_flag,
            $this->geographical_area_sid,
            $this->goods_nomenclature_sid,
            $this->ordernumber,
            $this->additional_code_type_id,
            $this->additional_code_id,
            $this->additional_code_sid,
            $this->reduction_indicator,
            $this->export_refund_nomenclature_sid,
            $operation, $operation_date
        ));

        $url = "/measure_view.html?measure_sid=" . $this->measure_sid;
        header("Location: " . $url);
    }

    public function update_measure()
    {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $sql = "INSERT INTO measures_oplog (
 measure_sid,
 measure_type_id,
 geographical_area_id,
 goods_nomenclature_item_id,
 validity_start_date,
 validity_end_date,
 measure_generating_regulation_role,
 measure_generating_regulation_id,
 justification_regulation_role,
 justification_regulation_id,
 stopped_flag,
 geographical_area_sid,
 goods_nomenclature_sid,
 ordernumber,
 additional_code_type_id,
 additional_code_id,
 additional_code_sid,
 reduction_indicator,
 export_refund_nomenclature_sid, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18, $19, $20, $21)";

        pg_prepare($conn, "measure_update", $sql);
        pg_execute($conn, "measure_update", array(
            $this->measure_sid,
            $this->measure_type_id,
            $this->geographical_area_id,
            $this->goods_nomenclature_item_id,
            $this->validity_start_date,
            $this->validity_end_date,
            $this->measure_generating_regulation_role,
            $this->measure_generating_regulation_id,
            $this->justification_regulation_role,
            $this->justification_regulation_id,
            $this->stopped_flag,
            $this->geographical_area_sid,
            $this->goods_nomenclature_sid,
            $this->ordernumber,
            $this->additional_code_type_id,
            $this->additional_code_id,
            $this->additional_code_sid,
            $this->reduction_indicator,
            $this->export_refund_nomenclature_sid,
            $operation, $operation_date
        ));

        $url = "/measure_view.html?measure_sid=" . $this->measure_sid;
        header("Location: " . $url);
    }

    public function get_new_measure_sid()
    {
        global $conn;
        $sql = "select max(measure_sid) from measures";
        $result = pg_query($conn, $sql);
        if ($result) {
            $row = pg_fetch_row($result);
            $this->measure_sid = $row[0] + 1;
        }
    }

    /* END MEASURE FUNCTIONS */

    /* BEGIN MEASURE COMPONENT FUNCTIONS */
    public function update_component($measure_sid, $duty_expression_id, $duty_amount, $monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code)
    {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $sql = "INSERT INTO measure_components_oplog
 (measure_sid, duty_expression_id, duty_amount, monetary_unit_code,
 measurement_unit_code, measurement_unit_qualifier_code, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8)";

        pg_prepare($conn, "component_insert", $sql);
        pg_execute($conn, "component_insert", array(
            $measure_sid, $duty_expression_id, $duty_amount, $monetary_unit_code,
            $measurement_unit_code, $measurement_unit_qualifier_code, $operation, $operation_date
        ));

        return (True);
    }

    public function insert_component($measure_sid, $duty_expression_id, $duty_amount, $monetary_unit_code, $measurement_unit_code, $measurement_unit_qualifier_code)
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();

        $sql = "INSERT INTO measure_components_oplog
 (measure_sid, duty_expression_id, duty_amount, monetary_unit_code,
 measurement_unit_code, measurement_unit_qualifier_code, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8)";

        pg_prepare($conn, "component_insert", $sql);
        pg_execute($conn, "component_insert", array(
            $measure_sid, $duty_expression_id, $duty_amount, $monetary_unit_code,
            $measurement_unit_code, $measurement_unit_qualifier_code, $operation, $operation_date
        ));

        return (True);
    }


    public function insert_footnote_association_measure($measure_sid, $footnote_type_id, $footnote_id)
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();

        $sql = "INSERT INTO footnote_association_measures_oplog
 (measure_sid, footnote_type_id, footnote_id, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5)";

        pg_prepare($conn, "insert_footnote_association_measure", $sql);
        pg_execute($conn, "insert_footnote_association_measure", array($measure_sid, $footnote_type_id, $footnote_id, $operation, $operation_date));
        return (True);
    }


    public function get_measure_oplog_components()
    {
        global $conn;
        $this->measure_components = array();
        $sql = "select duty_expression_id, duty_amount, monetary_unit_code, measurement_unit_code, measurement_unit_qualifier_code
 from measure_components_oplog where measure_sid = $1 and operation = $2 order by duty_expression_id";
        $query_name = "get_measure_oplog_components_" . $this->measure_sid;
        pg_prepare($conn, $query_name, $sql);
        $result = pg_execute($conn, $query_name, array($this->measure_sid, $this->operation));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $measure_component = new measure_component();
                $measure_component->measure_sid = $this->measure_sid;
                $measure_component->duty_expression_id = $row[0];
                $measure_component->duty_amount = $row[1];
                $measure_component->monetary_unit_code = $row[2];
                $measure_component->measurement_unit_code = $row[3];
                $measure_component->measurement_unit_qualifier_code = $row[4];
                $measure_component->operation = $this->operation;
                array_push($this->measure_components, $measure_component);
            }
        }
        $this->measure_components_xml = "";
        foreach ($this->measure_components as $measure_component) {
            $this->measure_components_xml .= $measure_component->xml();
        }
    }

    public function get_measure_oplog_conditions()
    {
        global $conn;
        $this->measure_conditions = array();
        $sql = "select measure_condition_sid, measure_sid, condition_code, component_sequence_number, condition_duty_amount, condition_monetary_unit_code,
 condition_measurement_unit_code, condition_measurement_unit_qualifier_code,
 action_code, certificate_type_code, certificate_code
 from measure_conditions
 where measure_sid = $1 and operation = $2
 order by component_sequence_number";
        $query_name = "get_measure_oplog_conditions_" . $this->measure_sid;
        pg_prepare($conn, $query_name, $sql);
        $result = pg_execute($conn, $query_name, array($this->measure_sid, $this->operation));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $measure_condition = new measure_condition();
                $measure_condition->measure_sid = $this->measure_sid;
                $measure_condition->measure_condition_sid = $row[0];
                $measure_condition->condition_code = $row[2];
                $measure_condition->component_sequence_number = $row[3];
                $measure_condition->condition_duty_amount = $row[4];
                $measure_condition->condition_monetary_unit_code = $row[5];
                $measure_condition->condition_measurement_unit_code = $row[6];
                $measure_condition->condition_measurement_unit_qualifier_code = $row[7];
                $measure_condition->action_code = $row[8];
                $measure_condition->certificate_type_code = $row[9];
                $measure_condition->certificate_code = $row[10];
                $measure_condition->operation = $this->operation;
                array_push($this->measure_conditions, $measure_condition);
            }
        }
        $this->measure_conditions_xml = "";
        foreach ($this->measure_conditions as $measure_condition) {
            $this->measure_conditions_xml .= $measure_condition->xml();
        }
    }


    public function get_measure_oplog_excluded_geographical_areas()
    {
        global $conn;
        $this->measure_excluded_geographical_areas = array();
        $sql = "select excluded_geographical_area, geographical_area_sid from measure_excluded_geographical_areas_oplog where measure_sid = $1 and operation = $2
 order by excluded_geographical_area";
        $query_name = "get_measure_oplog_excluded_geographical_areas_" . $this->measure_sid;
        pg_prepare($conn, $query_name, $sql);
        $result = pg_execute($conn, $query_name, array($this->measure_sid, $this->operation));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $measure_excluded_geographical_area = new measure_excluded_geographical_area();
                $measure_excluded_geographical_area->measure_sid = $this->measure_sid;
                $measure_excluded_geographical_area->excluded_geographical_area = $row[0];
                $measure_excluded_geographical_area->geographical_area_sid = $row[1];
                $measure_excluded_geographical_area->operation = $this->operation;
                array_push($this->measure_excluded_geographical_areas, $measure_excluded_geographical_area);
            }
        }
        $this->measure_excluded_geographical_areas_xml = "";
        foreach ($this->measure_excluded_geographical_areas as $measure_excluded_geographical_area) {
            $this->measure_excluded_geographical_areas_xml .= $measure_excluded_geographical_area->xml();
        }
    }


    public function get_measure_components()
    {
        global $conn;
        $this->measure_components = array();
        $sql = "select duty_expression_id, duty_amount, monetary_unit_code, measurement_unit_code, measurement_unit_qualifier_code
 from measure_components where measure_sid = $1 order by duty_expression_id";
        pg_prepare($conn, "get_measure_components", $sql);
        $result = pg_execute($conn, "get_measure_components", array($this->measure_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $measure_component = new measure_component();
                $measure_component->measure_sid = $this->measure_sid;
                $measure_component->duty_expression_id = $row[0];
                $measure_component->duty_amount = $row[1];
                $measure_component->monetary_unit_code = $row[2];
                $measure_component->measurement_unit_code = $row[3];
                $measure_component->measurement_unit_qualifier_code = $row[4];
                array_push($this->measure_components, $measure_component);
            }
        }
    }

    public function delete_measure_components()
    {
        h1("Deleting measure components");
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();
        $i = 0;
        foreach ($this->measure_components as $measure_component) {
            $sql = "INSERT INTO measure_components_oplog
 (measure_sid, duty_expression_id, duty_amount, monetary_unit_code,
 measurement_unit_code, measurement_unit_qualifier_code, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8)";
            $query_name = "component_delete" . $i;

            pg_prepare($conn, $query_name, $sql);
            pg_execute($conn, $query_name, array(
                $measure_component->measure_sid, $measure_component->duty_expression_id,
                $measure_component->duty_amount, $measure_component->monetary_unit_code,
                $measure_component->measurement_unit_code, $measure_component->measurement_unit_qualifier_code, $operation, $operation_date
            ));
            $i += 1;
        }
    }

    public function delete_measure()
    {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();
        $i = 0;

        $sql = "insert into measures_oplog (
 measure_sid, measure_type_id, geographical_area_id, goods_nomenclature_item_id,
 validity_start_date, validity_end_date, measure_generating_regulation_role, measure_generating_regulation_id,
 justification_regulation_role, justification_regulation_id, stopped_flag, geographical_area_sid,
 goods_nomenclature_sid, ordernumber, additional_code_type_id, additional_code_id,
 additional_code_sid,
 reduction_indicator,
 export_refund_nomenclature_sid,
 operation,
 operation_date
 )
 select
 measure_sid,
 measure_type_id,
 geographical_area_id,
 goods_nomenclature_item_id,
 validity_start_date,
 validity_end_date,
 measure_generating_regulation_role,
 measure_generating_regulation_id,
 justification_regulation_role,
 justification_regulation_id,
 stopped_flag,
 geographical_area_sid,
 goods_nomenclature_sid,
 ordernumber,
 additional_code_type_id,
 additional_code_id,
 additional_code_sid,
 reduction_indicator,
 export_refund_nomenclature_sid,
 'D',
 '" . $operation_date . "'
 from measures_oplog where measure_sid=$1 order by oid desc limit 1;";

        $query_name = "measure_delete";

        pg_prepare($conn, $query_name, $sql);
        pg_execute($conn, $query_name, array($this->measure_sid));
    }

    public function get_goods_nomenclature_item_id()
    {
        global $conn;
        $sql = "select goods_nomenclature_item_id, geographical_area_id from measures where measure_sid = $1";
        pg_prepare($conn, "get_goods_nomenclature_item_id", $sql);
        $result = pg_execute($conn, "get_goods_nomenclature_item_id", array($this->measure_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->goods_nomenclature_item_id = $row[0];
            $this->geographical_area_id = $row[1];
        }
    }

    public function delete_component($measure_sid, $duty_expression_id)
    {
        // Here we need to get the latest details of the component from the database before adding in a delete record
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();

        $sql = "select duty_amount, monetary_unit_code, measurement_unit_code, measurement_unit_qualifier_code
 from measure_components
 where measure_sid = $1 and duty_expression_id = $2";
        pg_prepare($conn, "get_component", $sql);
        $result = pg_execute($conn, "get_component", array($measure_sid, $duty_expression_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $duty_amount = $row[0];
            $monetary_unit_code = $row[1];
            $measurement_unit_code = $row[2];
            $measurement_unit_qualifier_code = $row[3];
        }

        $sql = "INSERT INTO measure_components_oplog
 (measure_sid, duty_expression_id, duty_amount, monetary_unit_code,
 measurement_unit_code, measurement_unit_qualifier_code, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8)";

        pg_prepare($conn, "component_delete", $sql);
        pg_execute($conn, "component_delete", array(
            $measure_sid, $duty_expression_id, $duty_amount, $monetary_unit_code,
            $measurement_unit_code, $measurement_unit_qualifier_code, $operation, $operation_date
        ));

        return (True);
    }
    /* END MEASURE COMPONENT FUNCTIONS */

    /* BEGIN CONDITION COMPONENT FUNCTIONS */
    public function get_measure_condition_components()
    {
        global $conn;
        $this->measure_condition_components = array();
        $sql = "select mcc.measure_condition_sid, mcc.duty_expression_id, mcc.duty_amount, mcc.monetary_unit_code,
 mcc.measurement_unit_code, mcc.measurement_unit_qualifier_code
 from measure_condition_components mcc, measure_conditions mc
 where mc.measure_condition_sid = mcc.measure_condition_sid and measure_sid = $1
 order by mc.component_sequence_number, mcc.duty_expression_id;";
        pg_prepare($conn, "get_measure_condition_components", $sql);
        $result = pg_execute($conn, "get_measure_condition_components", array($this->measure_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $measure_condition_component = new measure_condition_component();
                $measure_condition_component->measure_sid = $this->measure_sid;
                $measure_condition_component->measure_condition_sid = $row[0];
                $measure_condition_component->duty_expression_id = $row[1];
                $measure_condition_component->duty_amount = $row[2];
                $measure_condition_component->monetary_unit_code = $row[3];
                $measure_condition_component->measurement_unit_code = $row[4];
                $measure_condition_component->measurement_unit_qualifier_code = $row[5];
                array_push($this->measure_condition_components, $measure_condition_component);
            }
        }
    }

    public function delete_measure_condition_components()
    {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();
        $i = 0;
        foreach ($this->measure_condition_components as $mcc) {
            $sql = "INSERT INTO measure_condition_components_oplog
 (measure_condition_sid, duty_expression_id, duty_amount, monetary_unit_code,
 measurement_unit_code, measurement_unit_qualifier_code,
 operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8)";
            $query_name = "mcc_delete" . $i;

            pg_prepare($conn, $query_name, $sql);
            pg_execute($conn, $query_name, array(
                $mcc->measure_condition_sid, $mcc->duty_expression_id, $mcc->duty_amount, $mcc->monetary_unit_code,
                $mcc->measurement_unit_code, $mcc->measurement_unit_qualifier_code,
                $operation, $operation_date
            ));
            $i += 1;
        }
    }

    /* BEGIN MEASURE CONDITION FUNCTIONS */
    public function get_measure_conditions()
    {
        global $conn;
        $this->measure_conditions = array();
        $sql = "select measure_condition_sid, condition_code, component_sequence_number, condition_duty_amount, 
 condition_monetary_unit_code, condition_measurement_unit_code, condition_measurement_unit_qualifier_code
 from measure_conditions where measure_sid = $1 order by component_sequence_number;";
        pg_prepare($conn, "get_measure_conditions", $sql);
        $result = pg_execute($conn, "get_measure_conditions", array($this->measure_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $measure_condition = new measure_condition();
                $measure_condition->measure_sid = $this->measure_sid;
                $measure_condition->measure_condition_sid = $row[0];
                $measure_condition->condition_code = $row[1];
                $measure_condition->component_sequence_number = $row[2];
                $measure_condition->condition_duty_amount = $row[3];
                $measure_condition->condition_monetary_unit_code = $row[4];
                $measure_condition->condition_measurement_unit_code = $row[5];
                $measure_condition->condition_measurement_unit_qualifier_code = $row[6];
                array_push($this->measure_conditions, $measure_condition);
            }
        }
    }

    public function delete_measure_conditions()
    {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();
        $i = 0;
        foreach ($this->measure_conditions as $mc) {
            $sql = "INSERT INTO measure_conditions_oplog
 (measure_condition_sid, measure_sid, condition_code, component_sequence_number,
 condition_duty_amount, condition_monetary_unit_code, condition_measurement_unit_code, 
 condition_measurement_unit_qualifier_code, action_code, certificate_type_code, certificate_code,
 operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13)";
            $query_name = "mc_delete" . $i;

            pg_prepare($conn, $query_name, $sql);
            pg_execute($conn, $query_name, array(
                $mc->measure_condition_sid,
                $mc->measure_sid,
                $mc->condition_code,
                $mc->component_sequence_number,
                $mc->condition_duty_amount,
                $mc->condition_monetary_unit_code,
                $mc->condition_measurement_unit_code,
                $mc->condition_measurement_unit_qualifier_code,
                $mc->action_code,
                $mc->certificate_type_code,
                $mc->certificate_code,
                $operation, $operation_date
            ));
            $i += 1;
        }
    }
    /* END MEASURE CONDITION FUNCTIONS */

    /* BEGIN PARTIAL TEMPORARY STOP FUNCTIONS */
    public function get_measure_partial_temporary_stops()
    {
        global $conn;
        $this->measure_partial_temporary_stops = array();
        $sql = "select validity_start_date, validity_end_date, partial_temporary_stop_regulation_id,
 partial_temporary_stop_regulation_officialjournal_number, partial_temporary_stop_regulation_officialjournal_page,
 abrogation_regulation_id, abrogation_regulation_officialjournal_number, abrogation_regulation_officialjournal_page
 from measure_partial_temporary_stops where measure_sid = $1;";
        pg_prepare($conn, "get_measure_partial_temporary_stops", $sql);
        $result = pg_execute($conn, "get_measure_partial_temporary_stops", array($this->measure_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $measure_partial_temporary_stop = new measure_partial_temporary_stop();
                $measure_partial_temporary_stop->measure_sid = $this->measure_sid;
                $measure_partial_temporary_stop->validity_start_date = $row[0];
                $measure_partial_temporary_stop->validity_end_date = $row[1];
                $measure_partial_temporary_stop->partial_temporary_stop_regulation_id = $row[2];
                $measure_partial_temporary_stop->partial_temporary_stop_regulation_officialjournal_number = $row[3];
                $measure_partial_temporary_stop->partial_temporary_stop_regulation_officialjournal_page = $row[4];
                $measure_partial_temporary_stop->abrogation_regulation_id = $row[5];
                $measure_partial_temporary_stop->abrogation_regulation_officialjournal_number = $row[6];
                $measure_partial_temporary_stop->abrogation_regulation_officialjournal_page = $row[7];
                array_push($this->measure_partial_temporary_stops, $measure_partial_temporary_stop);
            }
        }
    }

    public function delete_measure_partial_temporary_stops()
    {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();
        $i = 0;
        foreach ($this->measure_partial_temporary_stops as $mpts) {
            $sql = "INSERT INTO measure_partial_temporary_stops_oplog
 (measure_sid, validity_start_date, validity_end_date, partial_temporary_stop_regulation_id,
 partial_temporary_stop_regulation_officialjournal_number, partial_temporary_stop_regulation_officialjournal_page,
 abrogation_regulation_id, abrogation_regulation_officialjournal_number, abrogation_regulation_officialjournal_page,
 operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";
            $query_name = "mpts_delete" . $i;

            pg_prepare($conn, $query_name, $sql);
            pg_execute($conn, $query_name, array(
                $mpts->measure_sid, $mpts->validity_start_date, $mpts->validity_end_date, $mpts->partial_temporary_stop_regulation_id,
                $mpts->partial_temporary_stop_regulation_officialjournal_number, $mpts->partial_temporary_stop_regulation_officialjournal_page,
                $mpts->abrogation_regulation_id, $mpts->abrogation_regulation_officialjournal_number, $mpts->abrogation_regulation_officialjournal_page,
                $operation, $operation_date
            ));
            $i += 1;
        }
    }
    /* END PARTIAL TEMPORARY STOP FUNCTIONS */


    /* BEGIN FOOTNOTE ASSOCIATION FUNCTIONS */
    public function get_footnote_association_measures()
    {
        global $conn;
        $this->footnote_association_measures = array();
        $sql = "select footnote_type_id, footnote_id from footnote_association_measures where measure_sid = $1 order by footnote_type_id, footnote_id";
        pg_prepare($conn, "get_footnote_association_measures", $sql);
        $result = pg_execute($conn, "get_footnote_association_measures", array($this->measure_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $footnote_association = new footnote_association_measure();
                $footnote_association->measure_sid = $this->measure_sid;
                $footnote_association->footnote_type_id = $row[0];
                $footnote_association->footnote_id = $row[1];
                array_push($this->footnote_association_measures, $footnote_association);
            }
        }
    }

    public function delete_footnote_association_measures()
    {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();
        $i = 0;
        foreach ($this->footnote_association_measures as $footnote_association_measure) {
            $sql = "INSERT INTO footnote_association_measures_oplog
 (measure_sid, footnote_type_id, footnote_id, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5)";
            $query_name = "measure_footnote_association_delete" . $i;
            pg_prepare($conn, $query_name, $sql);
            pg_execute($conn, $query_name, array(
                $footnote_association_measure->measure_sid, $footnote_association_measure->footnote_type_id,
                $footnote_association_measure->footnote_id, $operation, $operation_date
            ));
            $i += 1;
        }
    }
    /* END FOOTNOTE ASSOCIATION FUNCTIONS */

    /* BEGIN EXCLUDED GEO AREAS FUNCTIONS */

    public function measure_excluded_geographical_area_insert($excluded_geographical_area, $geographical_area_sid)
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $operation_date = $application->get_operation_date();
        $sql = "INSERT INTO measure_excluded_geographical_areas_oplog
 (measure_sid, excluded_geographical_area, geographical_area_sid, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5)";
        pg_prepare($conn, "measure_excluded_geographical_area_insert", $sql);
        pg_execute($conn, "measure_excluded_geographical_area_insert", array(
            $this->measure_sid, $excluded_geographical_area,
            $geographical_area_sid, $operation, $operation_date
        ));
    }

    public function measure_excluded_geographical_area_delete($excluded_geographical_area)
    {
        global $conn;
        $application = new application;
        $geo = new geographical_area();
        $geo->geographical_area_id = $excluded_geographical_area;
        $geo->get_geographical_area_sid();
        $operation = "D";
        $operation_date = $application->get_operation_date();
        $sql = "INSERT INTO measure_excluded_geographical_areas_oplog
 (measure_sid, excluded_geographical_area, geographical_area_sid, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5)";
        pg_prepare($conn, "measure_excluded_geographical_area_delete", $sql);
        pg_execute($conn, "measure_excluded_geographical_area_delete", array(
            $this->measure_sid, $excluded_geographical_area,
            $geo->geographical_area_sid, $operation, $operation_date
        ));
    }


    public function get_measure_excluded_geographical_areas()
    {
        global $conn;
        $this->measure_excluded_geographical_areas = array();
        $sql = "select excluded_geographical_area, geographical_area_sid from measure_excluded_geographical_areas where measure_sid = $1 order by excluded_geographical_area";
        pg_prepare($conn, "get_measure_excluded_geographical_areas", $sql);
        $result = pg_execute($conn, "get_measure_excluded_geographical_areas", array($this->measure_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $measure_excluded_geographical_area = new measure_excluded_geographical_area();
                $measure_excluded_geographical_area->measure_sid = $this->measure_sid;
                $measure_excluded_geographical_area->excluded_geographical_area = $row[0];
                $measure_excluded_geographical_area->geographical_area_sid = $row[1];
                array_push($this->measure_excluded_geographical_areas, $measure_excluded_geographical_area);
            }
        }
    }

    public function delete_measure_excluded_geographical_areas()
    {
        global $conn;
        $application = new application;
        $operation = "D";
        $operation_date = $application->get_operation_date();
        $i = 0;
        foreach ($this->measure_excluded_geographical_areas as $measure_excluded_geographical_area) {
            $sql = "INSERT INTO measure_excluded_geographical_areas_oplog
 (measure_sid, excluded_geographical_area, geographical_area_sid, operation, operation_date)
 VALUES (
 $1, $2, $3, $4, $5)";
            $query_name = "measure_excluded_geographical_area_delete" . $i;
            pg_prepare($conn, $query_name, $sql);
            pg_execute($conn, $query_name, array(
                $measure_excluded_geographical_area->measure_sid, $measure_excluded_geographical_area->excluded_geographical_area,
                $measure_excluded_geographical_area->geographical_area_sid, $operation, $operation_date
            ));
            $i += 1;
        }
    }
    /* END EXCLUDED GEO AREAS FUNCTIONS */


    public function get_footnote_string()
    {
        $s = "";
        $footnote_count = count($this->footnote_list);
        for ($j = 0; $j < $footnote_count; $j++) {
            $f = $this->footnote_list[$j];
            $s .= $f->footnote_type_id . $f->footnote_id . ", ";
        }
        $s = trim($s);
        $s = trim($s, ",");
        $this->footnote_string = $s;
    }

    public function get_condition_string()
    {
        $s = "";
        $condition_count = count($this->condition_list);
        for ($j = 0; $j < $condition_count; $j++) {
            $mc = $this->condition_list[$j];
            $s .= $mc->condition_string . " " . $mc->action_string . ", ";
        }
        $s = trim($s);
        $s = trim($s, ",");
        $this->condition_string = $s;
    }

    public function get_mega_string()
    {
        $s = "";
        $mega_count = count($this->mega_list);
        for ($j = 0; $j < $mega_count; $j++) {
            $mega = $this->mega_list[$j];
            $s .= $mega->excluded_geographical_area . ", ";
        }
        $s = trim($s);
        $s = trim($s, ",");
        $this->mega_string = $s;
    }

    function populate_from_db()
    {
        global $conn;
        $sql = "select measure_type_id, geographical_area_id, goods_nomenclature_item_id,
 validity_start_date, validity_end_date, measure_generating_regulation_role, measure_generating_regulation_id,
 justification_regulation_id, justification_regulation_role, stopped_flag, ordernumber,
 additional_code_type_id, additional_code_id, reduction_indicator
 from measures where measure_sid = $1";
        $query_name = "get_measure_" . $this->measure_sid;
        pg_prepare($conn, $query_name, $sql);
        $result = pg_execute($conn, $query_name, array($this->measure_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->measure_type_id = $row[0];
            $this->geographical_area_id = $row[1];
            $this->goods_nomenclature_item_id = $row[2];
            $this->validity_start_date = $row[3];
            $this->validity_end_date = $row[4];
            $this->measure_generating_regulation_role = $row[5];
            $this->measure_generating_regulation_id = $row[6];
            $this->justification_regulation_id = $row[7];
            $this->justification_regulation_role = $row[8];
            $this->stopped_flag = $row[9];
            $this->ordernumber = $row[10];
            $this->additional_code_type_id = $row[11];
            $this->additional_code_id = $row[12];
            $this->reduction_indicator = $row[13];

            if ($this->validity_start_date != Null) {
                $this->validity_start_day = date('d', strtotime($this->validity_start_date));
                $this->validity_start_month = date('m', strtotime($this->validity_start_date));
                $this->validity_start_year = date('Y', strtotime($this->validity_start_date));
            } else {
                $this->validity_start_day = "";
                $this->validity_start_month = "";
                $this->validity_start_year = "";
            }

            if ($this->validity_end_date != Null) {
                $this->validity_end_day = date('d', strtotime($this->validity_end_date));
                $this->validity_end_month = date('m', strtotime($this->validity_end_date));
                $this->validity_end_year = date('Y', strtotime($this->validity_end_date));
            } else {
                $this->validity_end_day = "";
                $this->validity_end_month = "";
                $this->validity_end_year = "";
            }
            $this->heading = "Edit measure " . $this->measure_sid;
        }
    }

    function populate_from_cookies()
    {
        $this->heading = "Create new measure";
        $this->measure_sid = get_cookie("measure_sid");
        $this->validity_start_day = get_cookie("measure_type_validity_start_day");
        $this->validity_start_month = get_cookie("measure_type_validity_start_month");
        $this->validity_start_year = get_cookie("measure_type_validity_start_year");
        $this->validity_end_day = get_cookie("measure_type_validity_end_day");
        $this->validity_end_month = get_cookie("measure_type_validity_end_month");
        $this->validity_end_year = get_cookie("measure_type_validity_end_year");

        $this->measure_generating_regulation_id = get_cookie("measure_generating_regulation_id");
        $this->measure_type_id = get_cookie("measure_type_id");
        $this->goods_nomenclature_item_id = get_cookie("goods_nomenclature_item_id");
        $this->additional_code_type_id = get_cookie("additional_code_type_id");
        $this->additional_code_id = get_cookie("additional_code_id");
        $this->geographical_area_id = get_cookie("geographical_area_id");
        $this->ordernumber = get_cookie("ordernumber");
    }

    public function get_siv_specific()
    {
        $s = 0;
        if (count($this->siv_component_list) > 0) {
            $s = floatval($this->siv_component_list[0]->duty_amount);
        }
        $this->combined_duty = "<span class='entry_price'>Entry Price</span> " . number_format($s, 3) . "%";
    }

    public function combine_duties()
    {
        $this->combined_duty = "";
        $this->measure_list = array();
        $this->measure_type_list = array();
        $this->additional_code_list = array();

        foreach ($this->duty_list as $d) {
            $d->geographical_area_id = $this->geographical_area_id;
            array_push($this->measure_type_list, $d->measure_type_id);
            array_push($this->measure_list, $d->measure_sid);
            array_push($this->additional_code_list, $d->additional_code_id);
            //p($d->perceived_value . " : " . $d->goods_nomenclature_item_id);
            // $d->perceived_value = floatval($d->perceived_value);
            $d->perceived_value = str_replace(",", "", $d->perceived_value);
            //p($d->perceived_value . " : " . $d->goods_nomenclature_item_id);
            if (($d->perceived_value != "") && ($d->perceived_value != Null)) {
                $this->perceived_value += $d->perceived_value;
            }
        }

        $measure_type_list_unique = set($this->measure_type_list);
        $measure_list_unique = set($this->measure_list);
        $additional_code_list_unique = set($this->additional_code_list);

        $this->measure_count = count($measure_list_unique);
        $this->measure_type_count = count($measure_type_list_unique);
        $this->additional_code_count = count($additional_code_list_unique);

        if (($this->measure_count == 1) && ($this->measure_type_count == 1) && ($this->additional_code_count == 1)) {
            foreach ($this->duty_list as $d) {
                $this->combined_duty .= $d->duty_string . " ";
            }
        } else {
            if ($this->measure_type_count > 1) {
                if (in_array("105", $measure_type_list_unique)) {
                    foreach ($this->duty_list as $d) {
                        if ($d->measure_type_id == "105") {
                            $this->combined_duty .= $d->duty_string . " ";
                        }
                    }
                }
            } elseif ($this->additional_code_count > 1) {
                if (in_array("500", $additional_code_list_unique)) {
                    foreach ($this->duty_list as $d) {
                        if ($d->additional_code_id == "500") {
                            $this->combined_duty .= $d->duty_string . " ";
                        }
                    }
                }
                if (in_array("500", $additional_code_list_unique)) {
                    foreach ($this->duty_list as $d) {
                        if ($d->additional_code_id == "500") {
                            $this->combined_duty .= $d->duty_string . " ";
                        }
                    }
                }
            }
        }

        $this->combined_duty = str_replace(" ", " ", $this->combined_duty);
        $this->combined_duty = trim($this->combined_duty);

        # Now add in the Meursing components
        $ad = strpos($this->combined_duty, "AC");
        $sd = strpos($this->combined_duty, "SD");
        $fd = strpos($this->combined_duty, "FD");

        /*
 if (($ad) || ($sd) || ($fd)) {
 $this->combined_duty = "CAD - " . $this->combined_duty . ") 100%";
 $this->combined_duty = preg_replace("/ \+ /", " + (", $this->combined_duty, 1);
 }
 */
    }
}
