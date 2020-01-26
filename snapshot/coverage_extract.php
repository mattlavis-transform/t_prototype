<?php
    ini_set('max_execution_time', 1800); // 30 minutes
    $write_to_screen = true; // false;
    $write_to_screen = false;
    require ("includes/db.php");
    $scope = get_querystring("scope") . "";
    $day = get_querystring("day") . "";
    $month = get_querystring("month") . "";
    $year = get_querystring("year") . "";
    $snapshot_date = to_date_string($day, $month, $year);
    $range = get_querystring("range") . "";
    if ($range == "" ) {
        $range = "%";
    } else {
        $range .= "%";
    }

    if ($write_to_screen == false) {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $scope . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $delimiter = "\n";
        $start_string = "";
        $end_string = "";
    } else {
        $delimiter = "<br />";
        $start_string = "<pre>";
        $end_string = "</pre>";
    }

    echo ($start_string);
// Get the duties
    if ($scope == "mfn") {
        $sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id,
        mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code,
        mc.measurement_unit_qualifier_code, m.geographical_area_id
        from ml.measures_real_end_dates m left outer join measure_components mc
        on m.measure_sid = mc.measure_sid
        where m.validity_start_date <= '" . $snapshot_date . "'
        and (m.validity_end_date is null or m.validity_end_date >= '" . $snapshot_date . "')
        and measure_type_id in ('103', '105')
        order by m.goods_nomenclature_item_id, mc.duty_expression_id";
    }
    elseif ($scope == "gsp") {

    } else {
        $sql = "select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id,
        mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code,
        mc.measurement_unit_qualifier_code, m.geographical_area_id
        from ml.measures_real_end_dates m left outer join measure_components mc
        on m.measure_sid = mc.measure_sid
        where m.validity_start_date <= '" . $snapshot_date . "'
        and (m.validity_end_date is null or m.validity_end_date >= '" . $snapshot_date . "')
        and measure_type_id in ('142', '145')
        and geographical_area_id = '" . $scope . "'
        order by m.goods_nomenclature_item_id, mc.duty_expression_id
        ";
    }
    echo($sql);
    die();

    $result = pg_query($conn, $sql);
    $duties = array();
	if ($result) {
        while ($row = pg_fetch_array($result)) {
            $duty = new duty();
            $duty->measure_sid                      = $row['measure_sid'];
            $duty->goods_nomenclature_item_id       = $row['goods_nomenclature_item_id'];
            $duty->measure_type_id                  = $row['measure_type_id'];
            $duty->duty_expression_id               = $row['duty_expression_id'];
            $duty->duty_amount                      = $row['duty_amount'];
            $duty->monetary_unit_code               = $row['monetary_unit_code'];
            $duty->measurement_unit_code            = $row['measurement_unit_code'];
            $duty->measurement_unit_qualifier_code  = $row['measurement_unit_qualifier_code'];
            if ($duty->duty_expression_id == null) {
                $duty->entry_price_applies              = true;
                $duty->duty_string = "Entry price";
                h1 ("EPS");
            } else {
                $duty->get_duty_string();
            }
            $duty->geographical_area_id             = $row['geographical_area_id'];
            
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
            $measure->measure_sid                   = $duty->measure_sid;
            $measure->goods_nomenclature_item_id    = $duty->goods_nomenclature_item_id;
            $measure->measure_type_id               = $duty->measure_type_id;
            $measure->geographical_area_id          = $duty->geographical_area_id;
            array_push($measure->duty_list, $duty);
            array_push($measures, $measure);
        }
    }

    $measure_count = count($measures);
    foreach ($measures as $measure) {
        $measure->combine_duties();
    }


    /* Get the excluded geographical areas */
    $sql = "select mega.measure_sid, mega.excluded_geographical_area, mega.geographical_area_sid
    from measures m, measure_excluded_geographical_areas mega
    where m.measure_sid = mega.measure_sid 
    and measure_type_id in ('142', '145')
    and m.validity_start_date = '" . $snapshot_date . "'
    and geographical_area_id = '" . $scope . "'
    order by m.goods_nomenclature_item_id, mega.excluded_geographical_area";
    $result = pg_query($conn, $sql);
    $measure_excluded_geographical_areas = array();
	if ($result) {
        while ($row = pg_fetch_array($result)) {
            $mega = new measure_excluded_geographical_area;
            $mega->measure_sid                  = $row['measure_sid'];
            $mega->excluded_geographical_area   = $row['excluded_geographical_area'];
            $mega->geographical_area_sid        = $row['geographical_area_sid'];
            array_push($measure_excluded_geographical_areas, $mega);
        }
    }
    /* Assign megas to measures */
    $mega_count = count($measure_excluded_geographical_areas);
    for ($i = 0; $i < $mega_count; $i++ ) {
        $mega = $measure_excluded_geographical_areas[$i];
        for ($j = 0; $j < $measure_count; $j++ ) {
            $measure = $measures[$j];
            if ($measure->measure_sid == $mega->measure_sid) {
                array_push($measure->mega_list, $mega);
                break;
            }
        }
    }

// Next - SQL to get the commodity codes
    $sql = "select goods_nomenclature_item_id, producline_suffix, number_indents,
    description, leaf, significant_digits, validity_start_date, validity_end_date
    from ml.goods_nomenclature_export_new ('" . $range . "', '" . $snapshot_date . "')
    order by goods_nomenclature_item_id, producline_suffix";

    $result = pg_query($conn, $sql);
    $commodities = array();
	if ($result) {
        while ($row = pg_fetch_array($result)) {
            $commodity = new goods_nomenclature;
            $commodity->goods_nomenclature_item_id  = $row['goods_nomenclature_item_id'];
            $commodity->productline_suffix          = $row['producline_suffix'];
            $commodity->number_indents              = $row['number_indents'];
            $commodity->description                 = $row['description'];
            $commodity->leaf                        = $row['leaf'];
            $commodity->significant_digits          = $row['significant_digits'];
            $commodity->validity_start_date         = $row['validity_start_date'];
            $commodity->validity_end_date           = $row['validity_end_date'];

            if ($commodity->significant_digits != 2) {
                $commodity->number_indents += 1;
            }
            array_push($commodities, $commodity);
        }
    }

// Finally, assign the measures to the commodities
    foreach ($measures as $measure) {
        $measure->get_mega_string();
        foreach ($commodities as $commodity) {
            //$commodity->geographical_area_id = "";
            //$commodity->mega_string = "";
            if ($measure->goods_nomenclature_item_id == $commodity->goods_nomenclature_item_id) {
                if ($commodity->productline_suffix == "80") {
                    array_push($commodity->measure_list, $measure);
                    $commodity->measure_sid = $measure->measure_sid;
                    $commodity->measure_type_id = $measure->measure_type_id;
                    $commodity->geographical_area_id = $measure->geographical_area_id;
                    $commodity->mega_string = $measure->mega_string;
                    $commodity->get_measure_type_description();
                    $commodity->assigned = true;
                    break;
                }
            }
        }
    }
    foreach ($commodities as $commodity) {
        $commodity->combine_duties();
    }

// Now inherit down where appropriate
    $count = count($commodities);
    for ($i = 0; $i < $count; $i++) {
        $commodity = $commodities[$i];
        if ($commodity->assigned == true) {
            for ($j = ($i + 1); $j < $count; $j++) {
                $commodity2 = $commodities[$j];
                if ($commodity2->number_indents > $commodity->number_indents) {
                    if ($commodity2->assigned == false) {
                        if ($commodity2->productline_suffix == "80") {
                            $commodity2->combined_duty          = $commodity->combined_duty;
                            $commodity2->measure_type_id        = $commodity->measure_type_id; 
                            $commodity2->measure_type_desc      = $commodity->measure_type_desc;
                            $commodity2->geographical_area_id   = $commodity->geographical_area_id; 
                            $commodity2->mega_string            = $commodity->mega_string; 
                        }
                    } else {
                        if ($commodity2->measure_type_id != '105') {
                            break;
                        }
                    }
                } else {
                    break;
                }
            }
        }
    }

    if (count($commodities) > 0) {
        echo "Commodity code,Suffix,Indent,End-line?,Description,Assigned,Measure type,Origin,Origin exclusions,Duties";
        echo ($delimiter);
        foreach ($commodities as $commodity) {
            $match_class = "";
            if ($commodity->assigned == true) {
                $commodity->assigned_string = "Y";
            } else {
                $commodity->assigned_string = "";
            }
            $number_indents_real = $commodity->number_indents - 1;
            if ($number_indents_real == -1) {
                $number_indents_real = 0;
            }
            if ($commodity->combined_duty == 'n/a') {
                $commodity->combined_duty = "";
            }
            echo ("'" . $commodity->goods_nomenclature_item_id . "',");
            echo ("'" . $commodity->productline_suffix . "',");
            echo ($number_indents_real . ",");
            echo ("'" . yn($commodity->leaf) . "',");
            echo ("'" . $commodity->format_description2() . "',");
            echo ("'" . $commodity->assigned_string . "',");
            echo ("'" . $commodity->measure_type_id . $commodity->measure_type_desc . "',");
            //echo ("'" . short_date($commodity->validity_start_date) . "',");
            //echo ("'" . short_date($commodity->validity_end_date) . "',");
            echo ("'" . $commodity->geographical_area_id . "',");
            echo ("'" . $commodity->mega_string . "',");
            echo ("'" . $commodity->combined_duty . "'");
            echo ($delimiter);
        }
    }
    echo ($end_string);
?>
