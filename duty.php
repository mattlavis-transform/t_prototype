<?php
require(dirname(__FILE__) . "/includes/db.php");
$application = new application;
$application->clear_filter_cookies();


$basic = ['01', '04', '19', '20'];
$min = ['15'];
$minus = ['02'];
$max = ['17', '35'];

// Basic
$basic_duties = array();
foreach ($basic as $item) {
    $temp = new duty();
    $temp->set($item);
    array_push($basic_duties, $temp);
}
// Max
$max_duties = array();
foreach ($max as $item) {
    $temp = new duty();
    $temp->set($item);
    array_push($max_duties, $temp);
}
// Min
$min_duties = array();
foreach ($min as $item) {
    $temp = new duty();
    $temp->set($item);
    array_push($min_duties, $temp);
}

$duty = $_POST["duty"];
$measure_type = $_POST["measure_type"];
$duty2 = trim(strtoupper($duty));

// Check if this is just a supplementary unit
if ($measure_type == 0) {
    // Check if the final % sign has just been omitted for ad valorems
    if ((strpos($duty, "%") === false) && (strpos($duty, "EUR") === false)) {
        $duty2 .= "%";
    }
}

// Pluralise and stanardise weights of measure
$duty2 = str_replace("100 ITEMS", "HUNDRED ITEMS", $duty2);
$my_array = array('WATT', 'TONNE', 'TON', 'GRAM', 'KILOGRAMME', 'KILOGRAM', 'KILO', 'METRE');
foreach ($my_array as $item) {
    $duty2 = str_replace($item . "S", $item, $duty2);

}
$duty2 = preg_replace('/TON$/', 'TONNE', $duty2);
$duty2 = preg_replace('/TON /', 'TONNE ', $duty2);

// replace the measurement units
$sql = "select measurement_unit_code, upper(description) as description from measurement_unit_descriptions mud
where measurement_unit_code not in ('KGM') order by 1 desc;";
$stmt = "stmt_1";
pg_prepare($conn, $stmt, $sql);
$result = pg_execute($conn, $stmt, array());
if ($result) {
    $row_count = pg_num_rows($result);
    if (($row_count > 0) && (pg_num_rows($result))) {
        while ($row = pg_fetch_array($result)) {
            $duty2 = str_replace($row['description'], ' ' . $row['measurement_unit_code'] . ' ', $duty2);
        }
    }
}

// replace the measurement unit qualifiers
$sql = "select measurement_unit_qualifier_code, upper(description) as description from measurement_unit_qualifier_descriptions mud
order by 1 desc;";
$stmt = "stmt_2";
pg_prepare($conn, $stmt, $sql);
$result = pg_execute($conn, $stmt, array());
if ($result) {
    $row_count = pg_num_rows($result);
    if (($row_count > 0) && (pg_num_rows($result))) {
        while ($row = pg_fetch_array($result)) {
            $duty2 = str_replace($row['description'], ' ' . $row['measurement_unit_qualifier_code'] . ' ', $duty2);
        }
    }
}

$duty2 = str_replace("CARATS", " CTM ", $duty2);
$duty2 = str_replace("WATTS", " WAT ", $duty2);
$duty2 = str_replace("", " ", $duty2);
$duty2 = str_replace("", " ", $duty2);
$duty2 = str_replace("", " ", $duty2);
$duty2 = str_replace("", " ", $duty2);
$duty2 = str_replace("", " ", $duty2);

$duty2 = str_replace("\t", " ", $duty2);
$duty2 = str_replace("  ", " ", $duty2);
$duty2 = str_replace("KG", "KGM", $duty2);
$duty2 = str_replace("KGMM", "KGM", $duty2);
$duty2 = str_replace("HL", "HLT", $duty2);
$duty2 = str_replace("HLTT", "HLT", $duty2);
$duty2 = str_replace("100 KGM", "DTN", $duty2);
$duty2 = str_replace("1000 KGM", "TNE", $duty2);
$duty2 = str_replace("MAX ", "| MAX ", $duty2);
$duty2 = str_replace("MIN ", "? MIN ", $duty2);
$duty2 = str_replace("+ ", "+ PLUS ", $duty2);
$duty2 = str_replace("- ", "- MINUS ", $duty2);
$duty2 = str_replace("/ ", "/", $duty2);
$duty2 = str_replace(" /", "/", $duty2);
$duty2 = str_replace("/", " ", $duty2);


$duty2 = str_replace("% VOL HLT", " ASV X ", $duty2);
$duty2 = str_replace("  ", " ", $duty2);


$parts = preg_split("/[\+\-\|\?]/", $duty2);
$out = "";
$out2 = "";
$i = 0;
$my_duty_expressions = array();
if ($measure_type == 0) {
    foreach ($parts as $part) {
        $my_duty_expression = new duty();
        $part = trim($part);
        $out .= $part . "\n";

        $part = str_replace("PLUS ACR", "MEURSING ACR", $part);
        $part = str_replace("PLUS AC", "MEURSING AC", $part);
        $part = str_replace("PLUS SDR", "MEURSING SDR", $part);
        $part = str_replace("PLUS SD", "MEURSING SD", $part);
        $part = str_replace("PLUS FDR", "MEURSING FDR", $part);
        $part = str_replace("PLUS FD", "MEURSING FD", $part);

        if (($i == 0) || (strpos($part, "PLUS") > -1)) {
            $part = str_replace("PLUS ", "", $part);
            // This is a basic duty expression
            foreach ($basic_duties as $item) {
                if ($item->used == false) {
                    $my_duty_expression->duty_expression_id = $item->duty_expression_id;
                    $item->used = true;
                    if (strpos($part, "%")) { // Ad valorem
                        $part = str_replace("%", "", $part);
                        $my_duty_expression->duty_amount = round($part, 3);
                        $my_duty_expression->monetary_unit_code = null;
                        $my_duty_expression->measurement_unit_code = null;
                        $my_duty_expression->measurement_unit_qualifier_code = null;
                    } elseif (strpos($part, "EUR")) {
                        $component_parts = explode(" ", $part);
                        $my_duty_expression->duty_amount = $component_parts[0];
                        $my_duty_expression->monetary_unit_code = $component_parts[1];
                        $my_duty_expression->measurement_unit_code = $component_parts[2];
                        if (count($component_parts) > 3) {
                            $my_duty_expression->measurement_unit_qualifier_code = $component_parts[3];
                        }
                        //$my_duty_expression->measurement_unit_qualifier_code = $component_parts[3];
                    }
                    break;
                }
            }
        } elseif ((strpos($part, "MAX") > -1)) {
            $part = str_replace("MAX ", "", $part);
            // This is a basic duty expression
            foreach ($max_duties as $item) {
                if ($item->used == false) {
                    $my_duty_expression->duty_expression_id = $item->duty_expression_id;
                    $item->used = true;
                    if (strpos($part, "%")) { // Ad valorem
                        $part = str_replace("%", "", $part);
                        $my_duty_expression->duty_amount = round($part, 3);
                        $my_duty_expression->monetary_unit_code = null;
                        $my_duty_expression->measurement_unit_code = null;
                        $my_duty_expression->measurement_unit_qualifier_code = null;
                    } else {
                        if (strpos($part, "EUR")) {
                            $component_parts = explode(" ", $part);
                            $my_duty_expression->duty_amount = $component_parts[0];
                            $my_duty_expression->monetary_unit_code = $component_parts[1];
                            $my_duty_expression->measurement_unit_code = $component_parts[2];
                            //$my_duty_expression->measurement_unit_qualifier_code = $component_parts[3];
                        }
                    }
                    break;
                }
            }
        } elseif ((strpos($part, "MIN") > -1)) {
            $part = str_replace("MIN ", "", $part);
            // This is a basic duty expression
            foreach ($min_duties as $item) {
                if ($item->used == false) {
                    $my_duty_expression->duty_expression_id = $item->duty_expression_id;
                    $item->used = true;
                    if (strpos($part, "%")) { // Ad valorem
                        $part = str_replace("%", "", $part);
                        $my_duty_expression->duty_amount = round($part, 3);
                        $my_duty_expression->monetary_unit_code = null;
                        $my_duty_expression->measurement_unit_code = null;
                        $my_duty_expression->measurement_unit_qualifier_code = null;
                    } else {
                        if (strpos($part, "EUR")) {
                            $component_parts = explode(" ", $part);
                            $my_duty_expression->duty_amount = $component_parts[0];
                            $my_duty_expression->monetary_unit_code = $component_parts[1];
                            $my_duty_expression->measurement_unit_code = $component_parts[2];
                            //$my_duty_expression->measurement_unit_qualifier_code = $component_parts[3];
                        }
                    }
                    break;
                }
            }
        } elseif ((strpos($part, "MEURSING") > -1)) {
            $part = str_replace("MEURSING ", "", $part);
            $my_duty_expression->duty_amount = null;
            $my_duty_expression->monetary_unit_code = null;
            $my_duty_expression->measurement_unit_code = null;
            $my_duty_expression->measurement_unit_qualifier_code = null;
            switch ($part) {
                case "AC(R)":
                    $my_duty_expression->duty_expression_id = "14";
                    break;
                case "AC":
                    $my_duty_expression->duty_expression_id = "12";
                    break;
                case "SD(R)":
                    $my_duty_expression->duty_expression_id = "25";
                    break;
                case "SD":
                    $my_duty_expression->duty_expression_id = "21";
                    break;
                case "FD(R)":
                    $my_duty_expression->duty_expression_id = "29";
                    break;
                case "FD":
                    $my_duty_expression->duty_expression_id = "27";
                    break;
            }
        }
        $my_duty_expression->validate($i);
        array_push($my_duty_expressions, $my_duty_expression);
        $i++;
    }
} else {
    // For supplementary units only


    if (count($parts) != 1) {
        return;
    } else {
        $part = $parts[0];
        $my_duty_expression = new duty();
        $my_duty_expression->duty_expression_id = "99";
        $my_duty_expression->duty_amount = null;
        $my_duty_expression->monetary_unit_code = null;
        $component_parts = explode(" ", $part);
        $my_duty_expression->measurement_unit_code = $component_parts[0];
        if (count($component_parts) > 1) {
            $my_duty_expression->measurement_unit_qualifier_code = $component_parts[1];
        }
        $my_duty_expression->validate($i);
    }
    $out = "TBC";
    array_push($my_duty_expressions, $my_duty_expression);
}

// Finally write the strings
$out2 = "";
foreach ($my_duty_expressions as $my_duty_expression) {
    $out2 .= "<tr class='govuk-table__row'>";
    $out2 .= "<td class='govuk-table__cell'>" . $my_duty_expression->duty_expression_id . "</td>";
    $out2 .= "<td class='govuk-table__cell'>" . $my_duty_expression->duty_amount . "</td>";
    $out2 .= "<td class='govuk-table__cell'>" . $my_duty_expression->monetary_unit_code . "</td>";
    $out2 .= "<td class='govuk-table__cell'>" . $my_duty_expression->measurement_unit_code . "</td>";
    $out2 .= "<td class='govuk-table__cell'>" . $my_duty_expression->measurement_unit_qualifier_code . "</td>";
    $out2 .= "<td class='govuk-table__cell c'>" . $my_duty_expression->validity . "</td>";
    $out2 .= "</tr>";
}


?>
<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("includes/metadata.php");
?>

<body class="govuk-template__body">
    <?php
    require("includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("includes/phase_banner.php");
        ?>


        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Duties</h1>
                    <!-- End main title //-->
                </div>
            </div>
            <form action="" method="post">
                <div class="govuk-grid-row">
                    <!-- Start column one //-->
                    <div class="govuk-grid-column-three-quarters">
                        <!-- Start text input //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label" for="duty">
                                Measure type
                            </label>
                            <select class="govuk-select" id="measure_type" name="measure_type">
                                <option value="0" <?php if ($measure_type == "0") {
                                                        echo ("selected");
                                                    } ?> selected>Anything but supplementary units</option>
                                <option value="1" <?php if ($measure_type == "1") {
                                                        echo ("selected");
                                                    } ?>>Supplementary unit</option>
                            </select>
                        </div>
                        <!-- End text input //-->
                        <!-- Start text input //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label" for="duty">
                                Duty
                            </label>
                            <input class="govuk-input duty" id="duty" name="duty" type="text" value="<?= $duty ?>">
                        </div>
                        <!-- End text input //-->

                        <!-- Start button //-->
                        <button class="govuk-button" data-module="govuk-button">Parse duty</button>
                        <!-- End button //-->

                        <!-- Start text area //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label" for="more-detail">
                                Parts
                            </label>
                            <!--
                            <span id="more-detail-hint" class="govuk-hint">
                                Do not include personal or financial information, like your National Insurance number or credit card details.
                            </span>
                            //-->
                            <textarea class="govuk-textarea" id="more-detail" name="more-detail" rows="5" aria-describedby="more-detail-hint"><?= $out ?></textarea>
                        </div>
                        <!-- End text area //-->

                        <table class="govuk-table">
                            <thead class="govuk-table__head">
                                <tr class="govuk-table__row">
                                    <th scope="col" class="govuk-table__header">Duty expression</th>
                                    <th scope="col" class="govuk-table__header">Duty amount</th>
                                    <th scope="col" class="govuk-table__header">Monetary unit</th>
                                    <th scope="col" class="govuk-table__header">Measurement unit</th>
                                    <th scope="col" class="govuk-table__header">Measurement unit qualifier</th>
                                    <th scope="col" class="govuk-table__header c">Validity</th>
                                </tr>
                            </thead>
                            <tbody class="govuk-table__body">
                                <?= $out2 ?>
                            </tbody>
                        </table>
                        <p class="govuk-body">Validity is worked out, up to a total of 15, adding the following numbers if successful:</p>
                        <ul class="govuk-list">
                            <li>1 - Check that the duty expression is valid</li>
                            <li>2 - Check that the measurement unit is valid</li>
                            <li>4 - Check that the measurement qualifier unit is valid</li>
                            <li>8 - Check the combo is okay</li>
                        </ul>

                    </div>
                    <!-- End column one //-->


                </div>
            </form>

        </main>
    </div>
    <?php
    require("includes/footer.php");
    ?>

</body>

</html>