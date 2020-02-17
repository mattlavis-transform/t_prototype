<?php
class snapshot
{
	// Class properties and methods go here
    public $scope = "";
    public $range = "";
    public $format = "html";
    public $form_submitted = false;
    public $day_start = "";
    public $month_start = "";
    public $year_start = "";
    public $day_end = "";
    public $month_end = "";
    public $year_end = "";
    public $geographical_area_description = "";
    public $depth = 8;
    public $omit_duties = 0;

    public function __construct() {
        $this->error_handler = new error_handler;
	}

	public function get_parameters() {
        if (!empty($_REQUEST)) {
            $this->form_submitted = true;
            $this->get_start_date();
            $this->get_end_date();
            $this->get_scope();
            $this->get_format();
            $this->get_range();
            $this->set_delimiters();
            if (($this->form_submitted == true) && (count($this->error_handler->error_list) == 0)) {
                $this->get_data();
                if ($this->format == "csv") {
                    $this->simple_write_csv();
                }
                elseif ($this->format == "json") {
                    $this->simple_write_json();
                }
            }
        }
    }

    private function simple_write_csv() {
        echo "Commodity code,Suffix,Indent,End-line?,Description,Assigned,Measure type,Origin,Origin exclusions,Duties,EPS,Start,End";
        echo ($this->delimiter);
        foreach ($this->commodities as $commodity) {
            if (count($commodity->measure_list) == 0) {
                echo ("'" . $commodity->goods_nomenclature_item_id . "',");
                echo ("'" . $commodity->productline_suffix . "',");
                echo ($commodity->number_indents . ",");
                echo ("'" . $commodity->leaf . "',");
                echo ("'" . $commodity->format_description2() . "',");
                echo ("'" . yn($commodity->assigned) . "',");
                echo ("'',");
                echo ("'',");
                echo ("'',");
                echo ("''");
                echo ($this->delimiter);
            } else {
                foreach ($commodity->measure_list as $measure) {
                    echo ("'" . $commodity->goods_nomenclature_item_id . "',");
                    echo ("'" . $commodity->productline_suffix . "',");
                    echo ($commodity->number_indents . ",");
                    echo ("'" . $commodity->leaf . "',");
                    echo ("'" . $commodity->format_description2() . "',");
                    echo ("'" . yn($commodity->assigned) . "',");
                    echo ("'" . $measure->measure_type_id . " " . $measure->measure_type_description . "',");
                    echo ("'" . $measure->geographical_area_id . "',");
                    echo ("'" . "" . "',");
                    //echo ("'" . "$measure->mega_string" . "',");
                    echo ("'" . str_replace("  ", " ", $measure->combined_duty) . "',");
                    echo ("'" . $measure->entry_price_string . "',");
                    echo ("'" . $measure->validity_start_date . "',");
                    echo ("'" . $measure->validity_end_date . "'");

                    echo ($this->delimiter);
                }
            }
            ob_flush();
            flush();
        }
        die();
    }

    private function simple_write_json() {
        //h1 ("simple_write_json");
        $pairs = array();
        
        $commodity_count = count($this->commodities);
        //h1 ($commodity_count);
        $commodity_index = 0;
        foreach ($this->commodities as $commodity) {
            $commodity_index += 1;
            if (($commodity->productline_suffix == "80") and ($commodity->significant_digits <= $this->depth)) {
                $description = $this->filter_for_json($commodity->description);
                $friendly_description = $this->filter_for_json($commodity->friendly_description);
                if (!$this->omit_duties) {
                    if (count($commodity->measure_list) == 0) {
                        if ($commodity->combined_duty == "") {
                            $commodity->combined_duty = "n/a";
                        }

                        if ($commodity->significant_digits == 8) {
                            if ($commodity->combined_duty == "n/a") {
                                if ($commodity->leaf == "Y") {
                                    $commodity->combined_duty = "leaf - no third country duty";
                                } else {
                                    $commodity->combined_duty = "not leaf - lookup required";
                                    $duty_list = array();
                                    $duty_string_list = array();
                                    for ($i = $commodity_index; $i < $commodity_count; $i++) {
                                        $next_commodity = $this->commodities[$i];
                                        // Stop if the indent is less than or equal to the current indent
                                        if ($next_commodity->number_indents <= $commodity->number_indents) {
                                            break;
                                        }

                                        // Loop through the child codes of the CN8 and create an array of their duty strings and perceived values
                                        if (count($next_commodity->measure_list) > 0){
                                            if ($next_commodity->measure_list[0]->combined_duty != "") {
                                                $temp_duty = new temp_object();
                                                $temp_duty->perceived_value = $next_commodity->measure_list[0]->perceived_value;
                                                $temp_duty->duty_string = $next_commodity->measure_list[0]->combined_duty;
                                                array_push($duty_list, $temp_duty);
                                                array_push($duty_string_list, $next_commodity->measure_list[0]->combined_duty);
                                            }
                                        }
                                    }
                                    $duty_count = count($duty_list);
                                    $duty_set = php_set($duty_string_list);

                                    if (count($duty_set) > 1) {
                                        $commodity->highest_duty_string = "";
                                        $commodity->highest_perceived_value = 0;
                                        for ($i = 0; $i < $duty_count; $i++) {
                                            $my_duty = $duty_list[$i];
                                            if ($my_duty->perceived_value > $commodity->highest_perceived_value) {
                                                $commodity->highest_perceived_value = $my_duty->perceived_value;
                                                $commodity->highest_duty_string = $my_duty->duty_string;
                                            }
                                        }
                                        $commodity->combined_duty = "" . $commodity->highest_duty_string; // . " (max value)";

                                    } else {
                                        if ($duty_count > 0) {
                                            $commodity->combined_duty = $duty_list[0]->duty_string; // . " (all child values are the same)";
                                        } else {
                                            $commodity->combined_duty = "An error has occurred";
                                        }
                                    }
                                }
                            }
                        }
                        if ($commodity->combined_duty != "leaf - no third country duty") {
                            $pair = new temp_object();
                            $pair->node = $commodity->node;
                            $pair->commodity_description = substr($commodity->goods_nomenclature_item_id, 0, 8) . ' - ' . $friendly_description;
                            $pair->duty_string = $commodity->combined_duty;
                            array_push($pairs, $pair);
                        }
                    } else {
                        $measure_count = count($commodity->measure_list);
                        $measure_index = 0;
                        foreach ($commodity->measure_list as $measure) {
                            $measure_index += 1;
                            $pair = new temp_object();
                            $pair->node = $commodity->node;
                            $pair->commodity_description = substr($commodity->goods_nomenclature_item_id, 0, 8) . ' - ' . $friendly_description;
                            $pair->duty_string = $measure->combined_duty;
                            array_push($pairs, $pair);
                        }
                    }
                } else {
                    $pair = new temp_object();
                    $pair->node = $commodity->node;
                    $pair->commodity_description = substr($commodity->goods_nomenclature_item_id, 0, 8) . ' - ' . $friendly_description;
                    $pair->duty_string = "";
                    array_push($pairs, $pair);
                }
            }
            ob_flush();
            flush();
        }

        //h1 (count($pairs));
        if ($this->omit_duties) {
            $index = 0;


            for ($x = count($pairs) - 1; $x >= 0; $x--) {
                $pair = $pairs[$x];
                //h1 ($pair->node . ":" . strlen($pair->node) . ":" . $this->depth);
                if (strlen($pair->node) != $this->depth) {
                    array_splice($pairs, $x, 1);
                }
            }
            /*
            foreach ($pairs as $pair) {
                //h1 ($pair->node . ":" . strlen($pair->node) . ":" . $this->depth);
                if (strlen($pair->node) != $this->depth) {
                    array_splice($pairs, $index, 1);
                }
                $index += 1;
            }*/
        }
        //h1 (count($pairs));

        $pair_count = count($pairs);
        $pair_index = 0;
        if ($pair_count > 0) {
            echo ("[" . $this->delimiter);
            foreach ($pairs as $pair) {
                $pair_index += 1;
                echo ('  {' . $this->delimiter);
                if ($this->omit_duties == 1) {
                    echo ('    "text": "' . $pair->commodity_description . '"' . $this->delimiter);
                } else {
                    echo ('    "text": "' . $pair->commodity_description . '",' . $this->delimiter);
                    echo ('    "bound": "' . $pair->duty_string . '"' . $this->delimiter);
                }
                echo ('  }');
                if ($pair_index != $pair_count) {
                    echo (',');
                }
                echo ($this->delimiter);
            }
            echo ("]" . $this->delimiter);
        }
        echo($this->end_string);
        die();
    }

    private function filter_for_json($s) {
        $s = str_replace('"', "'", $s);
        $s = str_replace('-', "-", $s);
        $s = str_replace('<br>', " ", $s);
        $s = str_replace('<p>', " ", $s);
        $s = str_replace('<p/>', " ", $s);
        $s = str_replace('•', " ", $s);
        $s = str_replace(chr(9), " ", $s);
        $s = str_replace(chr(10), " ", $s);
        $s = str_replace(chr(11), " ", $s);
        $s = str_replace(chr(13), " ", $s);
        $s = str_replace("\n", " ", $s);
        $s = str_replace("\r", " ", $s);
        $s = str_replace(array("\n", "\r"), '', $s);
        
        $s = str_replace("  ", " ", $s);
        $s = str_replace(PHP_EOL, '', $s);
        $s = trim(preg_replace('/\s\s+/', ' ', $s));
        //$s = preg_replace('/\s+/', '', $s); 
        $s = preg_replace('!\s+!', ' ', $s);
        $s = strtr($s,"\n\r","  ");
        $s = stripcslashes($s);
        $s = trim($s);
        return ($s);
    }

    private function get_start_date() {
        // Get snapshot day start
        $this->day_start = intval(get_querystring("day_start") . "");
        $this->month_start = intval(get_querystring("month_start") . "");
        $this->year_start = intval(get_querystring("year_start") . "");
        $this->snapshot_date_start = to_date_string($this->day_start, $this->month_start, $this->year_start);
        $valid_date_start = checkdate($this->month_start, $this->day_start, $this->year_start);
        if ($valid_date_start != 1) {
            array_push($this->error_handler->error_list, "snapshot_date_start");
        }
	}

	private function get_end_date() {
        // Get snapshot day end
        $this->day_end = intval(get_querystring("day_end") . "");
        $this->month_end = intval(get_querystring("month_end") . "");
        $this->year_end = intval(get_querystring("year_end") . "");
        if (($this->day_end != "") && ($this->month_end != "") && ($this->year_end != "")) {
            $this->snapshot_date_end = to_date_string($this->day_end, $this->month_end, $this->year_end);
            $valid_date_end = checkdate($this->month_end, $this->day_end, $this->year_end);
            if ($valid_date_end != 1) {
                array_push($this->error_handler->error_list, "snapshot_date_end");
            }
        } else {
            $this->snapshot_date_end = $this->snapshot_date_start;
        }
    }

	private function get_range() {
        // Get commodity range
        $this->range = get_querystring("range") . "";
        $this->get_geographical_area_description();
        /*
        if ($this->range == "" ) {
            if ($this->format == "html") {
                array_push($this->error_handler->error_list, "commodity_range");
            } else {
                $this->range = "";
            }
        } else {
            $this->get_geographical_area_description();
        }
        */
	}

	private function get_geographical_area_description() {
        global $conn;
        $sql = "select description from ml.ml_geographical_areas mga where geographical_area_id = $1;";
        pg_prepare($conn, "get_description", $sql);
        $result = pg_execute($conn, "get_description", array($this->scope));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->geographical_area_description = $row[0];
        }
	}

	private function get_scope() {
	    // Get scope
        $this->scope = strtoupper(get_querystring("scope") . "");
        if ($this->scope == "" ) {
            if ($this->format == "html") {
                array_push($this->error_handler->error_list, "scope");
            }
        }
    }

	private function get_format() {
        // Get format, JSON, CSV or screen
        $fmt = get_querystring("fmt");

        if ($fmt == "") {
            array_push($this->error_handler->error_list, "snapshot_format");
            return;
        }

        if ($fmt == "csv") {
            $this->format = "csv";
        }
        elseif ($fmt == "json") {
            $this->format = "json";
        }
        else {
            $this->format = "html";
        }

        if ($this->format == "json") {
            // Depth
            $this->depth = get_querystring("depth");
            if ($this->depth == "") {
                $this->depth = 8;
            } else {
                $this->depth = intval($this->depth);
            }

            // Omit duties or not
            if ($this->depth < 8) {
                $this->omit_duties = 1;
            } else {
                $this->omit_duties = get_querystring("omit_duties");
                if ($this->omit_duties == "") {
                    $this->omit_duties = 0;
                } else {
                    $this->omit_duties = intval($this->omit_duties);
                }
            }
        } else {
            $this->depth = 10;
            $this->omit_duties = 0;
        }
    }

    public function set_delimiters(){
        // If writing to CSV, do we want to actually write the CSV to screen instead
        if ($this->format != "html") {
            $this->write_to_screen = get_querystring("wts");
            if ($this->write_to_screen == false) {
                header("Content-type: text/" . $this->format);
                header("Content-Disposition: attachment; filename=" . $this->scope . "_" . $this->range . "_" . $this->snapshot_date_start . "_to_" . $this->snapshot_date_end . "." . $this->format);
                header("Pragma: no-cache");
                header("Expires: 0");
                $this->delimiter = "\n";
                $this->start_string = "";
                $this->end_string = "";
            } else {
                $this->delimiter = "<br />";
                $this->start_string = "<pre>";
                $this->end_string = "</pre>";
            }
            echo ($this->start_string);
        }
    }

    public function get_data() {
        global $conn;

        $range_len = strlen($this->range);
        if ($range_len != 0) {
            $range_clause = " and left(m.goods_nomenclature_item_id, " . $range_len . ") = '" . $this->range . "' ";
        } else {
            $range_clause = "";
        }

        if ($this->omit_duties == 0) {
            // Get the measure condition components (for Entry Price)
            if (($this->scope == "mfn") || ($this->scope == "1011")) {
                $sql = "select m.measure_sid, mc.measure_condition_sid, mcc.duty_expression_id, mcc.duty_amount,
                mcc.monetary_unit_code, mcc.measurement_unit_code, mcc.measurement_unit_qualifier_code
                from ml.measures_real_end_dates m, measure_conditions mc, measure_condition_components mcc
                where m.measure_sid = mc.measure_sid
                and mc.measure_condition_sid = mcc.measure_condition_sid
                and m.validity_start_date <= '" . $this->snapshot_date_end . "'
                and (m.validity_end_date is null or m.validity_end_date >= '" . $this->snapshot_date_start . "')
                and mc.component_sequence_number = 1
                and mcc.duty_expression_id = '01'
                and m.measure_type_id in ('103', '105')" . $range_clause .
                " order by m.measure_sid, mcc.duty_expression_id, mcc.duty_amount";
            } else {
                $sql = "select m.measure_sid, mc.measure_condition_sid, mcc.duty_expression_id, mcc.duty_amount,
                mcc.monetary_unit_code, mcc.measurement_unit_code, mcc.measurement_unit_qualifier_code
                from ml.measures_real_end_dates m, measure_conditions mc, measure_condition_components mcc
                where m.measure_sid = mc.measure_sid
                and mc.measure_condition_sid = mcc.measure_condition_sid
                and m.validity_start_date <= '" . $this->snapshot_date_end . "'
                and (m.validity_end_date is null or m.validity_end_date >= '" . $this->snapshot_date_start . "')
                and mc.component_sequence_number = 1
                and mcc.duty_expression_id = '01'
                and m.measure_type_id in ('142', '145')" . $range_clause .
                "and geographical_area_id = '" . $this->scope . "' " .
                " order by m.measure_sid, mcc.duty_expression_id, mcc.duty_amount";
            }
            $result = pg_query($conn, $sql);
            $measure_condition_components = array();
            if ($result) {
                while ($row = pg_fetch_array($result)) {
                    $mcc = new measurement_unit_qualifier();
                    $mcc->measure_sid = $row['measure_sid'];
                    $mcc->duty_expression_id = $row['duty_expression_id'];
                    $mcc->duty_amount = $row['duty_amount'];
                    $mcc->monetary_unit_code = $row['monetary_unit_code'];
                    $mcc->measurement_unit_code = $row['measurement_unit_code'];
                    $mcc->measurement_unit_qualifier_code = $row['measurement_unit_qualifier_code'];
                    array_push($measure_condition_components, $mcc);
                }
            }

            // Get the duties / measure components
            if (($this->scope == "mfn") || ($this->scope == "1011")) {
                $sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id, mc.duty_expression_id,
                mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code,
                m.validity_start_date, m.validity_end_date, mtd.description as measure_type_description
                from measure_type_descriptions mtd, ml.measures_real_end_dates m
                left outer join measure_components mc
                on m.measure_sid = mc.measure_sid
                where m.measure_type_id = mtd.measure_type_id
                and m.validity_start_date <= '" . $this->snapshot_date_end . "'
                and (m.validity_end_date is null or m.validity_end_date >= '" . $this->snapshot_date_start . "')
                and m.measure_type_id in ('103', '105') " . $range_clause .
                " order by m.goods_nomenclature_item_id, m.validity_start_date, mc.duty_expression_id;";
            } else {
                $sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id, mc.duty_expression_id,
                mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code,
                m.validity_start_date, m.validity_end_date, mtd.description as measure_type_description
                from measure_type_descriptions mtd, ml.measures_real_end_dates m
                left outer join measure_components mc
                on m.measure_sid = mc.measure_sid
                where m.measure_type_id = mtd.measure_type_id
                and m.validity_start_date <= '" . $this->snapshot_date_end . "' and (m.validity_end_date is null or m.validity_end_date >= '" . $this->snapshot_date_start . "')
                and m.measure_type_id in ('142', '145') " . $range_clause .
                "and geographical_area_id = '" . $this->scope . "'
                order by m.goods_nomenclature_item_id, m.validity_start_date, mc.duty_expression_id";
            }

            $result = pg_query($conn, $sql);
            $duties = array();
            if ($result) {
                while ($row = pg_fetch_array($result)) {
                    $duty = new duty();
                    $duty->measure_condition_components     = array();
                    $duty->measure_sid                      = $row['measure_sid'];
                    $duty->goods_nomenclature_item_id       = $row['goods_nomenclature_item_id'];
                    $duty->measure_type_id                  = $row['measure_type_id'];
                    $duty->duty_expression_id               = $row['duty_expression_id'];
                    $duty->duty_amount                      = $row['duty_amount'];
                    $duty->monetary_unit_code               = $row['monetary_unit_code'];
                    $duty->measurement_unit_code            = $row['measurement_unit_code'];
                    $duty->measurement_unit_qualifier_code  = $row['measurement_unit_qualifier_code'];
                    $duty->measure_type_description         = $row['measure_type_description'];
                    $duty->validity_start_date              = $row['validity_start_date'];
                    $duty->validity_end_date                = $row['validity_end_date'];
                    $duty->geographical_area_id             = $this->scope;

                    if ($duty->duty_expression_id == Null) {
                        $duty->entry_price_applies              = true;
                        foreach ($measure_condition_components as $mcc) {
                            if ($mcc->measure_sid == $duty->measure_sid) {
                                array_push($duty->measure_condition_components, $mcc);
                            }
                        }
                        $duty->duty_string = "";
                        foreach ($duty->measure_condition_components as $mcc) {
                            $duty->duty_string .= number_format($mcc->duty_amount, 3) . "%";
                            $duty->entry_price_string = "Y";
                        }
                    } else {
                        $duty->get_duty_string(1);
                    }
                    array_push($duties, $duty);
                }
            }

            // Form the duties into measure objects
            $measures = array();
            foreach ($duties as $duty) {
                $measure_sid = $duty->measure_sid;
                $matched = false;
                foreach ($measures as $measure) {
                    $measure_sid2 = $measure->measure_sid;
                    if ($measure_sid == $measure_sid2) {
                        array_push($measure->duty_list, $duty);
                        $matched = true;
                        break;
                    }
                }
                if ($matched == false) {
                    $measure = new measure();
                    $measure->measure_sid               = $duty->measure_sid;
                    $measure->goods_nomenclature_item_id = $duty->goods_nomenclature_item_id;
                    $measure->measure_type_id           = $duty->measure_type_id;
                    $measure->measure_type_description  = $duty->measure_type_description;
                    $measure->validity_start_date       = $duty->validity_start_date;
                    $measure->validity_end_date         = $duty->validity_end_date;
                    $measure->geographical_area_id      = $duty->geographical_area_id;
                    $measure->entry_price_string        = $duty->entry_price_string;
                    array_push($measure->duty_list, $duty);
                    array_push($measures, $measure);
                }
            }

            foreach ($measures as $measure) {
                $measure->combine_duties();
            }
        }

        // Next - SQL to get the commodity codes
        //h1 ("getting commodity codes");
        if ($this->depth < 8) {
            $sql = "select goods_nomenclature_item_id, producline_suffix, number_indents,
            description, leaf, significant_digits, validity_start_date, validity_end_date, node
            from ml.goods_nomenclature_export_new ('" . $this->range . "%', '" . $this->snapshot_date_start . "')
            where significant_digits <= " . $this->depth . "
            order by goods_nomenclature_item_id, producline_suffix;";    
        } else {
            $sql = "select goods_nomenclature_item_id, producline_suffix, number_indents,
            description, leaf, significant_digits, validity_start_date, validity_end_date, node
            from ml.goods_nomenclature_export_new ('" . $this->range . "%', '" . $this->snapshot_date_start . "')
            order by goods_nomenclature_item_id, producline_suffix;";
        }

        //prend ($sql);

        $result = pg_query($conn, $sql);
        $this->commodities = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $commodity	= new goods_nomenclature;
                $commodity->goods_nomenclature_item_id  = $row['goods_nomenclature_item_id'];
                $commodity->productline_suffix          = $row['producline_suffix'];
                $commodity->number_indents              = $row['number_indents'];
                $commodity->description                 = $row['description'];
                $commodity->leaf                        = yn($row['leaf']);
                $commodity->significant_digits          = $row['significant_digits'];
                $commodity->node                        = $row['node'];
                $commodity->validity_start_date         = $row['validity_start_date'];
                $commodity->validity_end_date           = $row['validity_end_date'];
                $commodity->node                        = $row['node'];
                if ($commodity->significant_digits != 2) {
                    $commodity->number_indents += 1;
                }
                array_push($this->commodities, $commodity);
            }
        }

        // Then get the friendly names
        //h2 ("My friendlies");
        $sql = "select goods_nomenclature_item_id, node, description from ml.commodity_friendly_names where node like '" . $this->range . "%' order by 1";
        $result = pg_query($conn, $sql);
        $this->friendly_names = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->friendly_names[$row['node']] = $row['description'];
                //h2 ($row['node'] . ":" . $row['description']);
            }
        }

        // Assign the friendlies to the commodities
        //h2 ("My commodities");
        foreach ($this->commodities as $commodity) {
            if ($commodity->significant_digits < 10) {
                if ($commodity->goods_nomenclature_item_id <= "9800000000") {
                    if (array_key_exists($commodity->node, $this->friendly_names)){ 
                        $commodity->friendly_description = $this->friendly_names[$commodity->node];
                    } else {
                        $commodity->friendly_description = "";
                    }
                } else {
                    $commodity->friendly_description = $commodity->description;
                }
            }
            //h2 ($commodity->node . ":" .$commodity->friendly_description);
        }

        if ($this->omit_duties == 0) {
            // Finally, assign the measures to the commodities
            foreach ($measures as $measure) {
                foreach ($this->commodities as $commodity) {
                    if ($measure->goods_nomenclature_item_id == $commodity->goods_nomenclature_item_id) {
                        if ($commodity->productline_suffix == "80") {
                            array_push($commodity->measure_list, $measure);
                            $commodity->measure_sid = $measure->measure_sid;
                            $commodity->assigned = true;
                            break;
                        }
                    }
                }
            }

            // Now inherit down where appropriate
            // Now, inheritance needs to take account of dates
            $commodity_count = count($this->commodities);
            for ($i = 0; $i < $commodity_count; $i++) {
                $commodity = $this->commodities[$i];
                if ($commodity->assigned == true) {
                    for ($j = ($i + 1); $j < $commodity_count; $j++) {
                        $commodity2 = $this->commodities[$j];
                        if ($commodity2->number_indents > $commodity->number_indents) {
                            if ($commodity2->assigned == false) {
                                if ($commodity2->productline_suffix == "80") {
                                    $commodity2->measure_list = $commodity->measure_list;
                                    foreach ($commodity2->measure_list as $measure) {
                                        $measure->goods_nomenclature_item_id = $commodity2->goods_nomenclature_item_id;
                                    }
                                }
                            }
                        } else {
                            break;
                        }
                    }
                }
            }
        }
    }
}

class temp_object {
    public $perceived_value = 0;
    public $commodity_description = "";
    public $duty_string = "";
    public $node = "";
}