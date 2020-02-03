<?php
class goods_nomenclature
{
    // Class properties and methods go here
    public $goods_nomenclature_item_id = "";
    public $goods_nomenclature_sid = null;
    public $productline_suffix = "";
    public $measure_type_desc = "";
    public $node = "";
    public $friendly_description = "";
    public $highest = 0;
    public $avg = 0;
    public $descriptions = array();
    public $footnotes = array();
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $description = "";
    public $duty_prototype = null;
    public $indent = null;


    public function __construct()
    {
        $this->measure_list = array();
        $this->measure_type_id = "";
        $this->combined_duty = "";
        $this->assigned = false;
        $this->ar_hierarchies = array();
        $this->exists = true;
        $this->geographical_area_id = "";
        $this->mega_string = "";
    }


    public function validate_form()
    {
        return (false);
    }

    public function trim_zeroes($s)
    {
        if (substr($s, -8) == "00000000") {
            $s = substr($s, 0, 2);
        } elseif (substr($s, -6) == "000000") {
            $s = substr($s, 0, 4);
        } elseif (substr($s, -4) == "0000") {
            $s = substr($s, 0, 6);
        } elseif (substr($s, -2) == "00") {
            $s = substr($s, 0, 8);
        }
        return ($s);
    }

    public function populate()
    {
        global $conn;
        $sql = "select goods_nomenclature_sid, goods_nomenclature_item_id, producline_suffix, number_indents, chapter, description, node, leaf, significant_digits
        from ml.goods_nomenclature_export_new($1, '2019-01-01') limit 1;";
        pg_prepare($conn, "populate", $sql);
        $result = pg_execute($conn, "populate", array($this->goods_nomenclature_item_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $this->number_indents = $row["number_indents"];
                $this->description = $row["description"];
                $this->chapter = $row["chapter"];
                $this->description = $row["description"];
                $this->node = $row["node"];
                $this->leaf = $row["leaf"];
                $this->significant_digits = $row["significant_digits"];
            }
        }
    }

    public function productline_suffix_display()
    {
        if ($this->productline_suffix == "80") {
            return ("-");
        } else {
            return ($this->productline_suffix);
        }
    }

    public function description_display()
    {
        $s = $this->description;
        $s = $this->format_description($s);
        return ($s);
    }

    public function get_children($parent, $depth)
    {
        global $conn;
        $this->headings = array();
        $trimmed_parent = $this->trim_zeroes($parent) . "%";
        switch ($depth) {
            case 0:
                $depth_string = " where number_indents = 0 and significant_digits = 2";
                break;
            case 1:
                $depth_string = " where number_indents = 0";
                break;
            case 2:
                $depth_string = " where number_indents = 1";
                break;
            default:
                $depth_string = "";
                break;
        }
        $sql = "select goods_nomenclature_sid, goods_nomenclature_item_id, producline_suffix, number_indents, description, chapter, node, leaf, significant_digits
        from ml.goods_nomenclature_export_new($1, '2019-01-01') " . $depth_string . " order by 2, 3;";
        pg_prepare($conn, "get_headings", $sql);
        $result = pg_execute($conn, "get_headings", array($trimmed_parent));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $heading = new goods_nomenclature();
                $heading->goods_nomenclature_sid = $row["goods_nomenclature_sid"];
                $heading->goods_nomenclature_item_id = $row["goods_nomenclature_item_id"];
                $heading->productline_suffix = $row["producline_suffix"];
                $heading->number_indents = $row["number_indents"];
                $heading->description = $row["description"];
                $heading->chapter = $row["chapter"];
                $heading->node = $row["node"];
                $heading->leaf = $row["leaf"];
                $heading->significant_digits = $row["significant_digits"];

                array_push($this->headings, $heading);
            }
        }
    }

    public function get_parameters($description = false)
    {
        global $application;
        global $error_handler;

        $this->goods_nomenclature_sid = trim(get_querystring("goods_nomenclature_sid"));
        $this->goods_nomenclature_item_id = trim(get_querystring("goods_nomenclature_item_id"));

        if (empty($_GET)) {
            $this->clear_cookies();
        } elseif ($application->mode == "insert") {
            $this->populate_from_cookies();
        } else {
            if (empty($error_handler->error_string)) {
                $ret = $this->populate_from_db();
                if (!$ret) {
                    h1("An error has occurred - no such commodity code");
                    die();
                }
            } else {
                $this->populate_from_cookies();
            }
        }
    }


    function populate_from_db()
    {
        global $conn;
        if ($this->goods_nomenclature_sid == null ) {
            $sql = "select DISTINCT ON (gn.goods_nomenclature_sid)
            gn.goods_nomenclature_item_id, gn.producline_suffix, gn.statistical_indicator,
            gn.validity_start_date, gn.validity_end_date, gnd.description, gn.goods_nomenclature_sid
            from goods_nomenclatures gn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
            where gndp.goods_nomenclature_description_period_sid = gnd.goods_nomenclature_description_period_sid
            and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
            and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
            and gn.goods_nomenclature_item_id = $1 and gn.producline_suffix = $2
            ORDER BY gn.goods_nomenclature_sid, gndp.validity_start_date desc;";
            pg_prepare($conn, "get_goods_nomenclature", $sql);
            $result = pg_execute($conn, "get_goods_nomenclature", array($this->goods_nomenclature_item_id, $this->productline_suffix));
    
        } else {
            $sql = "select DISTINCT ON (gn.goods_nomenclature_sid)
            gn.goods_nomenclature_item_id, gn.producline_suffix, gn.statistical_indicator,
            gn.validity_start_date, gn.validity_end_date, gnd.description, gn.goods_nomenclature_sid
            from goods_nomenclatures gn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
            where gndp.goods_nomenclature_description_period_sid = gnd.goods_nomenclature_description_period_sid
            and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
            and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
            and gn.goods_nomenclature_sid = $1
            ORDER BY gn.goods_nomenclature_sid, gndp.validity_start_date desc;";
            pg_prepare($conn, "get_goods_nomenclature", $sql);
            $result = pg_execute($conn, "get_goods_nomenclature", array($this->goods_nomenclature_sid));
    
        }

        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->goods_nomenclature_item_id = $row[0];
            $this->producline_suffix = $row[1];
            $this->statistical_indicator = $row[2];
            $this->validity_start_date = $row[3];
            $this->validity_end_date = $row[4];
            $this->description = $row[5];
            $this->goods_nomenclature_sid = $row[6];

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
            //$this->get_descriptions();
            return (true);
        } else {
            return (false);
        }
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



    public function get_goods_nomenclature_sid()
    {
        global $conn;
        $sql = "select goods_nomenclature_sid from goods_nomenclatures
 where goods_nomenclature_item_id = $1 and producline_suffix = '80'
 order by validity_start_date desc limit 1";
        pg_prepare($conn, "get_goods_nomenclature_sid", $sql);
        $result = pg_execute($conn, "get_goods_nomenclature_sid", array($this->goods_nomenclature_item_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->goods_nomenclature_sid = $row[0];
        } else {
            $this->goods_nomenclature_sid = Null;
        }

        return ($this->goods_nomenclature_sid);
    }



    public function get_measure_type_description()
    {
        switch ($this->measure_type_id) {
            case "142":
                $this->measure_type_desc = " - Preference";
                break;
            case "143":
                $this->measure_type_desc = " - AU Preference";
                break;
            case "145":
                $this->measure_type_desc = " - Quota";
                break;
            case "146":
                $this->measure_type_desc = " - AU Quota";
                break;
        }
    }

    public function combine_duties()
    {
        if ($this->productline_suffix != "80") {
            $this->combined_duty = "n/a";
        } else {
            $this->combined_duty = "";
            foreach ($this->measure_list as $measure) {
                $this->combined_duty .= $measure->combined_duty;
            }
        }
    }


    public function set_properties($goods_nomenclature_item_id, $productline_suffix, $description, $number_indents, $leaf, $direction = "both")
    {
        global $conn;
        $this->goods_nomenclature_item_id = $goods_nomenclature_item_id;
        $this->productline_suffix = $productline_suffix;
        $this->number_indents = $number_indents;
        $this->description = $description;
        $this->leaf = $leaf;

        // Do an initial check that this exisst in the database
        $sql = "select goods_nomenclature_sid from goods_nomenclatures
        where goods_nomenclature_item_id = '" . $this->goods_nomenclature_item_id . "' and producline_suffix = '" . $this->productline_suffix . "'";
        $result = pg_query($conn, $sql);
        if ($result) {
            if (pg_num_rows($result) == 0) {
                $this->exists = false;
            }
        }

        if ($this->exists == true) {
            if ($number_indents == "") {
                //q("getting the hierarchy");
                $this->get_hierarchy($direction);
            }
        }
    }

    public function get_hierarchy($direction = "both")
    {
        global $conn, $critical_date;
        $stem = substr($this->goods_nomenclature_item_id, 0, 2);
        $sql = "SELECT goods_nomenclature_item_id, producline_suffix as productline_suffix, number_indents,
 description, leaf FROM ml.goods_nomenclature_export_new('" . $stem . "%', '" . $critical_date . "')
 ORDER BY goods_nomenclature_item_id, producline_suffix";

        $result = pg_query($conn, $sql);
        if ($result) {
            $ar_goods_nomenclatures[] = new goods_nomenclature;
            $ar_hierarchies[] = new goods_nomenclature;

            while ($row = pg_fetch_array($result)) {
                $goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
                $productline_suffix = $row['productline_suffix'];
                $number_indents = $row['number_indents'];
                $description = $row['description'];
                $leaf = $row['leaf'];
                $gn = new goods_nomenclature;
                $gn->set_properties($goods_nomenclature_item_id, $productline_suffix, $description, $number_indents, $leaf);
                $gn->deal_with_double_zeroes();
                array_push($ar_goods_nomenclatures, $gn);
            }

            // Get the data from the loop to cover the current item
            foreach ($ar_goods_nomenclatures as $gn) {
                if (($gn->goods_nomenclature_item_id == $this->goods_nomenclature_item_id) && ($gn->productline_suffix == $this->productline_suffix)) {
                    $this->number_indents = $gn->number_indents;
                    $this->description = $gn->description;
                    $this->leaf = $gn->leaf;
                    break;
                }
            }

            $record_count = sizeof($ar_goods_nomenclatures);
            $my_indent = 999;
            for ($i = 0; $i < $record_count; $i++) {
                if (($ar_goods_nomenclatures[$i]->goods_nomenclature_item_id == $this->goods_nomenclature_item_id) &&
                    ($ar_goods_nomenclatures[$i]->productline_suffix == $this->productline_suffix)
                ) {
                    $my_index = $i;
                    $my_indent = $ar_goods_nomenclatures[$i]->number_indents;
                    break;
                }
            }
            if ($my_indent == 999) {
                print("Error, commodity not found - " . $this->goods_nomenclature_item_id . " <br />");
                return;
            }
            // Kludge to deal with the chapter level records, which have a "0" indent, the same as their children
            if ($my_indent == 0) {
                if (substr($this->goods_nomenclature_item_id, 2, 10) == "00000000") {
                    $my_indent = -1;
                }
            }
            // Search UP the tree from my_index to find parent codes
            if ($direction != "down") {
                $temp_indent = $my_indent;
                for ($i = $my_index; $i > 0; $i--) {
                    $t = $ar_goods_nomenclatures[$i];
                    if (($t->number_indents < $temp_indent) || (($t->goods_nomenclature_item_id == $this->goods_nomenclature_item_id) &&
                        ($t->productline_suffix == $this->productline_suffix))) {
                        array_push($ar_hierarchies, $t);
                        $temp_indent = $t->number_indents;
                    }
                }
            } else {
                array_push($ar_hierarchies, $this);
            }
            // Reverse the hierarchy, so that the 'current' hierarchical item sits at the bottom
            $ar_hierarchies = array_reverse($ar_hierarchies);
            $hier_count = sizeof($ar_hierarchies);
            if ($hier_count > 0) {
                array_pop($ar_hierarchies);
            }
            // Remove the empty item accidentally created when the array was initialised
            $hier_count = sizeof($ar_hierarchies);


            // Search DOWN the tree from my_index to find child codes
            $temp_indent = $my_indent;
            for ($i = $my_index + 1; $i < $record_count; $i++) {
                $t = $ar_goods_nomenclatures[$i];
                if ($t->number_indents <= $my_indent) {
                    break;
                } else {
                    #echo ($t->goods_nomenclature_item_id . "|" . $t->productline_suffix . "<br />");
                    array_push($ar_hierarchies, $t);
                }
            }
            $this->ar_hierarchies = $ar_hierarchies;
        }
    }

    private function deal_with_double_zeroes()
    {
        if ($this->number_indents == 0) {
            if (substr($this->goods_nomenclature_item_id, 2, 10) == "00000000") {
                $this->number_indents = -1;
            }
        }
    }

    function populate_from_cookies()
    {
        $this->validity_start_date_day = get_cookie("goods_nomenclature_validity_start_date_day");
        $this->validity_start_date_month = get_cookie("goods_nomenclature_validity_start_date_month");
        $this->validity_start_date_year = get_cookie("goods_nomenclature_validity_start_date_year");
        $this->validity_end_date_day = get_cookie("goods_nomenclature_validity_end_date_day");
        $this->validity_end_date_month = get_cookie("goods_nomenclature_validity_end_date_month");
        $this->validity_end_date_year = get_cookie("goods_nomenclature_validity_end_date_year");
        $this->description = get_cookie("goods_nomenclature_description");
        $this->heading = "Create new goods_nomenclature";
        $this->disable_goods_nomenclature_item_id_field = "";
    }


    public function leaf_string()
    {
        if ($this->leaf == "1") {
            return ("Y");
        } else {
            return ("");
        }
    }

    function get_latest_description()
    {
        global $conn;
        $sql = "SELECT gnd.description
 FROM goods_nomenclature_description_periods gndp, goods_nomenclature_descriptions gnd
 WHERE gnd.goods_nomenclature_description_period_sid = gndp.goods_nomenclature_description_period_sid
 AND gnd.goods_nomenclature_item_id = $1 AND gnd.productline_suffix = $2 AND gnd.goods_nomenclature_sid = $3 
 ORDER BY gndp.validity_start_date DESC LIMIT 1";

        pg_prepare($conn, "get_latest_description", $sql);
        $result = pg_execute($conn, "get_latest_description", array(
            $this->goods_nomenclature_item_id,
            $this->productline_suffix, $this->goods_nomenclature_sid
        ));
        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[0];
        }
    }

    function get_start_date()
    {
        global $conn;
        $sql = "SELECT validity_start_date FROM goods_nomenclatures
 WHERE goods_nomenclature_item_id = $1 AND producline_suffix = $2 ORDER BY operation_date DESC LIMIT 1";
        pg_prepare($conn, "get_validity_start_date", $sql);
        $result = pg_execute($conn, "get_validity_start_date", array($this->goods_nomenclature_item_id, $this->productline_suffix));

        if ($result) {
            $row = pg_fetch_row($result);
            $d = $row[0];
            return (DateTime::createFromFormat('Y-m-d H:i:s', $d)->format('Y-m-d'));
        } else {
            return ("");
        }
    }

    function update_description($goods_nomenclature_item_id, $productline_suffix, $goods_nomenclature_sid, $validity_start_date, $description, $goods_nomenclature_description_period_sid)
    {
        global $conn;
        $application = new application;
        $operation = "U";
        $operation_date = $application->get_operation_date();

        $this->goods_nomenclature_item_id = $goods_nomenclature_item_id;
        $this->goods_nomenclature_sid = $goods_nomenclature_sid;
        $this->productline_suffix = $productline_suffix;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->goods_nomenclature_description_period_sid = $goods_nomenclature_description_period_sid;

        #$this->f_validity_start_date = $this->get_start_date();
        #$this->get_missing_commodity_details();

        # Insert the goods_nomenclature description period
        $sql = "INSERT INTO goods_nomenclature_description_periods_oplog
 (goods_nomenclature_description_period_sid, productline_suffix, goods_nomenclature_item_id, goods_nomenclature_sid,
 validity_start_date, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "goods_nomenclature_description_period_insert", $sql);
        pg_execute($conn, "goods_nomenclature_description_period_insert", array(
            $this->goods_nomenclature_description_period_sid,
            $this->productline_suffix, $this->goods_nomenclature_item_id, $this->goods_nomenclature_sid,
            $this->validity_start_date, $operation, $operation_date
        ));

        # Insert the goods_nomenclature description
        $sql = "INSERT INTO goods_nomenclature_descriptions_oplog
 (goods_nomenclature_description_period_sid, language_id, productline_suffix, goods_nomenclature_item_id, goods_nomenclature_sid,
 description, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
        pg_prepare($conn, "goods_nomenclature_description_insert", $sql);
        pg_execute($conn, "goods_nomenclature_description_insert", array(
            $this->goods_nomenclature_description_period_sid, "EN",
            $this->productline_suffix, $this->goods_nomenclature_item_id, $this->goods_nomenclature_sid, $this->description, $operation, $operation_date
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

        # Insert the goods_nomenclature description period
        $sql = "INSERT INTO goods_nomenclature_description_periods_oplog
 (goods_nomenclature_description_period_sid, productline_suffix, goods_nomenclature_item_id, goods_nomenclature_sid, validity_start_date, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "goods_nomenclature_description_period_insert", $sql);
        pg_execute($conn, "goods_nomenclature_description_period_insert", array(
            $this->goods_nomenclature_description_period_sid, $this->productline_suffix,
            $this->goods_nomenclature_item_id, $this->goods_nomenclature_sid, $this->period_validity_start_date, $operation, $operation_date
        ));

        # Insert the goods_nomenclature description
        $sql = "INSERT INTO goods_nomenclature_descriptions_oplog
 (goods_nomenclature_description_period_sid, language_id, productline_suffix, goods_nomenclature_item_id, goods_nomenclature_sid, description, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
        pg_prepare($conn, "goods_nomenclature_description_insert", $sql);
        pg_execute($conn, "goods_nomenclature_description_insert", array(
            $this->goods_nomenclature_description_period_sid, "EN",
            $this->productline_suffix, $this->goods_nomenclature_item_id, $this->goods_nomenclature_sid, $this->description, $operation, $operation_date
        ));
        return (True);
    }


    function insert_description($goods_nomenclature_item_id, $productline_suffix, $goods_nomenclature_sid, $validity_start_date, $description)
    {
        global $conn;
        $application = new application;
        $operation = "C";
        $goods_nomenclature_description_period_sid = $application->get_next_goods_nomenclature_description_period();
        $operation_date = $application->get_operation_date();

        $this->goods_nomenclature_item_id = $goods_nomenclature_item_id;
        $this->goods_nomenclature_sid = $goods_nomenclature_sid;
        $this->productline_suffix = $productline_suffix;
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
        $this->goods_nomenclature_description_period_sid = $goods_nomenclature_description_period_sid;

        $this->f_validity_start_date = $this->get_start_date();
        #$this->get_missing_commodity_details();

        # Insert the goods_nomenclature description period
        $sql = "INSERT INTO goods_nomenclature_description_periods_oplog
 (goods_nomenclature_description_period_sid, productline_suffix, goods_nomenclature_item_id,
 goods_nomenclature_sid, validity_start_date, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7)";
        pg_prepare($conn, "goods_nomenclature_description_period_insert", $sql);
        pg_execute($conn, "goods_nomenclature_description_period_insert", array(
            $this->goods_nomenclature_description_period_sid, $this->productline_suffix,
            $this->goods_nomenclature_item_id, $this->goods_nomenclature_sid, $this->validity_start_date, $operation, $operation_date
        ));

        # Insert the goods_nomenclature description
        $sql = "INSERT INTO goods_nomenclature_descriptions_oplog
 (goods_nomenclature_description_period_sid, language_id, productline_suffix, goods_nomenclature_item_id, goods_nomenclature_sid,
 description, operation, operation_date)
 VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
        pg_prepare($conn, "goods_nomenclature_description_insert", $sql);
        pg_execute($conn, "goods_nomenclature_description_insert", array(
            $this->goods_nomenclature_description_period_sid, "EN",
            $this->productline_suffix, $this->goods_nomenclature_item_id, $this->goods_nomenclature_sid, $this->description, $operation, $operation_date
        ));
        return (True);
    }

    function format_description()
    {
        $s = $this->description;
        // preg_replace($pattern, $replacement, $string);
        $s = str_replace("\t", "", $s);
        $s = preg_replace('/\s+/', ' ', $s);
        $s = str_replace("<br>", "<br />", $s);
        for ($i = 0; $i < 5; $i++) {
            $s = str_replace("<br /> ", "<br />", $s);
        }
        for ($i = 0; $i < 5; $i++) {
            $s = str_replace("<br /><br />", "<br />", $s);
        }
        return ($s);
    }

    function format_description2()
    {
        $s = $this->description;
        $s = str_replace("\t", "", $s);
        $s = preg_replace('/\s+/', ' ', $s);
        $s = str_replace("<br>", "<br />", $s);
        for ($i = 0; $i < 5; $i++) {
            $s = str_replace("<br /> ", "<br />", $s);
        }
        for ($i = 0; $i < 5; $i++) {
            $s = str_replace("<br /><br />", "<br />", $s);
        }
        $s = str_replace("<br />", " ", $s);
        $s = str_replace("<p/>", " ", $s);
        $s = str_replace("<p />", " ", $s);
        $s = str_replace("<p>", " ", $s);
        $s = str_replace("</p>", " ", $s);
        $s = str_replace("\n", " ", $s);
        $s = str_replace("\r", " ", $s);
        $s = str_replace(" ", " ", $s);
        $s = str_replace("'", "`", $s);
        return ($s);
    }

    function get_missing_details()
    {
        global $conn;
        $sql = "SELECT description, gndp.validity_start_date as period_validity_start_date,
 gn.validity_start_date as validity_start_date, gn.goods_nomenclature_sid
 FROM goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp, goods_nomenclatures gn
 WHERE gnd.goods_nomenclature_description_period_sid = gndp.goods_nomenclature_description_period_sid
 AND gn.goods_nomenclature_item_id = gnd.goods_nomenclature_item_id
 AND gn.producline_suffix = gnd.productline_suffix
 AND gn.goods_nomenclature_item_id = gndp.goods_nomenclature_item_id
 AND gn.producline_suffix = gndp.productline_suffix
 AND gnd.goods_nomenclature_description_period_sid = $1";
        pg_prepare($conn, "get_missing_details", $sql);
        $result = pg_execute($conn, "get_missing_details", array($this->goods_nomenclature_description_period_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[0];
            $this->period_validity_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $row[1])->format('Y-m-d');
            $this->validity_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $row[2])->format('Y-m-d');
            $this->goods_nomenclature_sid = $row[3];
        } else {
            $this->description = "";
            $this->period_validity_start_date = "";
            $this->validity_start_date = "";
            $this->goods_nomenclature_sid = -1;
        }
    }

    function get_missing_commodity_details()
    {
        global $conn;
        $sql = "SELECT gn.validity_start_date as validity_start_date, gn.goods_nomenclature_sid FROM goods_nomenclatures gn
 WHERE gn.goods_nomenclature_item_id = $1 AND gn.producline_suffix = $2";
        pg_prepare($conn, "get_missing_details", $sql);
        $result = pg_execute($conn, "get_missing_details", array($this->goods_nomenclature_item_id, $this->productline_suffix));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->item_validity_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $row[0])->format('Y-m-d');
            $this->goods_nomenclature_sid = $row[1];
        } else {
            $this->item_validity_start_date = "";
            $this->goods_nomenclature_sid = -1;
        }
    }

    function get_description_from_db()
    {
        global $conn;
        $sql = "SELECT fd.goods_nomenclature_item_id, fd.productline_suffix, fd.description, fdp.validity_start_date
 FROM goods_nomenclature_description_periods fdp, goods_nomenclature_descriptions fd
 WHERE fd.goods_nomenclature_description_period_sid = fdp.goods_nomenclature_description_period_sid
 AND fd.goods_nomenclature_description_period_sid = $1 ";

        pg_prepare($conn, "get_goods_nomenclature_description", $sql);
        $result = pg_execute($conn, "get_goods_nomenclature_description", array($this->goods_nomenclature_description_period_sid));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->description = $row[2];
            $this->validity_start_date = $row[3];
            $this->validity_start_date_day = date('d', strtotime($this->validity_start_date));
            $this->validity_start_date_month = date('m', strtotime($this->validity_start_date));
            $this->validity_start_date_year = date('Y', strtotime($this->validity_start_date));
            $this->goods_nomenclature_heading = "Edit measure type " . $this->productline_suffix;
            $this->disable_productline_suffix_field = " disabled";
        }
    }


    public function clear_cookies()
    {
        setcookie("goods_nomenclature_item_id", "", time() + (86400 * 30), "/");
        setcookie("productline_suffix", "", time() + (86400 * 30), "/");
        setcookie("goods_nomenclature_description", "", time() + (86400 * 30), "/");
        setcookie("goods_nomenclature_validity_start_date_day", "", time() + (86400 * 30), "/");
        setcookie("goods_nomenclature_validity_start_date_month", "", time() + (86400 * 30), "/");
        setcookie("goods_nomenclature_validity_start_date_year", "", time() + (86400 * 30), "/");
        setcookie("goods_nomenclature_description", "", time() + (86400 * 30), "/");
        setcookie("goods_nomenclature_validity_end_date_day", "", time() + (86400 * 30), "/");
        setcookie("goods_nomenclature_validity_end_date_month", "", time() + (86400 * 30), "/");
        setcookie("goods_nomenclature_validity_end_date_year", "", time() + (86400 * 30), "/");
    }

    function get_footnotes() {
        global $conn;
        $sql = "select fagn.footnote_type, fagn.footnote_id, f.description, fagn.validity_start_date, fagn.validity_end_date
        from footnote_association_goods_nomenclatures fagn, ml.ml_footnotes f
        where fagn.footnote_type = f.footnote_type_id and fagn.footnote_id = f.footnote_id
        and goods_nomenclature_sid = $1 order by 1,2;";
        pg_prepare($conn, "get_footnotes", $sql);
        $result = pg_execute($conn, "get_footnotes", array($this->goods_nomenclature_sid));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $temp = new footnote_association_goods_nomenclature;
                $temp->footnote_type_id = $row["footnote_type"];
                $temp->footnote_id = $row["footnote_id"];
                $temp->description = $row["description"];
                $temp->validity_start_date = $row["validity_start_date"];
                $temp->validity_end_date = $row["validity_end_date"];
                array_push($this->footnotes, $temp);
            }
        }
    }
}
