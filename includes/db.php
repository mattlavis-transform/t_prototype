<?php
require("p.php");
$http_host = strtolower($_SERVER["HTTP_HOST"]);
date_default_timezone_set("Europe/London");
require(dirname(__FILE__) . "../../classes/application.php");
require(dirname(__FILE__) . "../../workbaskets/workbasket.php");
require(dirname(__FILE__) . "../../classes/user.php");
require(dirname(__FILE__) . "../../session/session.php");
require(dirname(__FILE__) . "../../controls/controls.php");
require(dirname(__FILE__) . "../../classes/simple_object.php");
require(dirname(__FILE__) . "../../classes/reusable.php");
require(dirname(__FILE__) . "../../classes/error_handler.php");
require(dirname(__FILE__) . "../../duty_expressions/duty_expression.php");
require(dirname(__FILE__) . "../../measure_type_series/measure_type_series.php");
require(dirname(__FILE__) . "../../classes/monetary_exchange_rate.php");
require(dirname(__FILE__) . "../../measure_types/measure_type.php");
require(dirname(__FILE__) . "../../footnote_types/footnote_type.php");
require(dirname(__FILE__) . "../../footnotes/footnote.php");
require(dirname(__FILE__) . "../../certificate_types/certificate_type.php");
require(dirname(__FILE__) . "../../certificates/certificate.php");
require(dirname(__FILE__) . "../../goods_nomenclatures/section.php");
require(dirname(__FILE__) . "../../goods_nomenclatures/goods_nomenclature.php");
require(dirname(__FILE__) . "../../goods_nomenclatures/footnote_association_goods_nomenclature.php");
require(dirname(__FILE__) . "../../classes/measurement.php");
require(dirname(__FILE__) . "../../measurement_units/measurement_unit.php");
require(dirname(__FILE__) . "../../measurement_unit_qualifiers/measurement_unit_qualifier.php");
require(dirname(__FILE__) . "../../measures/measure.php");
require(dirname(__FILE__) . "../../classes/measure_condition.php");
require(dirname(__FILE__) . "../../classes/measure_condition_component.php");
require(dirname(__FILE__) . "../../classes/measure_component.php");
require(dirname(__FILE__) . "../../classes/measure_partial_temporary_stop.php");
require(dirname(__FILE__) . "../../classes/duty.php");
require(dirname(__FILE__) . "../../classes/siv_component.php");
require(dirname(__FILE__) . "../../geographical_areas/geographical_area.php");
require(dirname(__FILE__) . "../../classes/quota_definition.php");
require(dirname(__FILE__) . "../../quotas/quota_order_number.php");
require(dirname(__FILE__) . "../../classes/quota_order_number_origin.php");
require(dirname(__FILE__) . "../../classes/quota_order_number_origin_exclusion.php");
require(dirname(__FILE__) . "../../regulations/base_regulation.php");
require(dirname(__FILE__) . "../../regulation_groups/regulation_group.php");
require(dirname(__FILE__) . "../../classes/quota_association.php");
require(dirname(__FILE__) . "../../classes/quota_blocking_period.php");
require(dirname(__FILE__) . "../../classes/quota_suspension_period.php");
require(dirname(__FILE__) . "../../additional_codes/additional_code.php");
require(dirname(__FILE__) . "../../additional_code_types/additional_code_type.php");
require(dirname(__FILE__) . "../../classes/measure_excluded_geographical_area.php");
require(dirname(__FILE__) . "../../footnotes/footnote_association_measure.php");
require(dirname(__FILE__) . "../../measure_condition_codes/measure_condition_code.php");
require(dirname(__FILE__) . "../../measure_actions/measure_action.php");
require(dirname(__FILE__) . "../../classes/snapshot.php");
require(dirname(__FILE__) . "../../classes/description.php");
require(dirname(__FILE__) . "../../classes/member.php");
require(dirname(__FILE__) . "../../classes/cryptor.php");
require(dirname(__FILE__) . "../../classes/link.php");
require(dirname(__FILE__) . "../../rules_of_origin_schemes/rules_of_origin_scheme.php");

/* Extract classes */
require(dirname(__FILE__) . "../../classes/extract.php");

require(dirname(__FILE__) . "../../classes/extract/extract_measures.php");
require(dirname(__FILE__) . "../../classes/extract/extract_measure_components.php");
require(dirname(__FILE__) . "../../classes/extract/extract_measure_conditions.php");
require(dirname(__FILE__) . "../../classes/extract/extract_measure_condition_components.php");
require(dirname(__FILE__) . "../../classes/extract/extract_measure_excluded_geographical_areas.php");
require(dirname(__FILE__) . "../../classes/extract/extract_measure_partial_temporary_stops.php");
require(dirname(__FILE__) . "../../classes/extract/extract_footnote_association_measures.php");

require(dirname(__FILE__) . "../../classes/extract/extract_geographical_area_descriptions.php");
require(dirname(__FILE__) . "../../classes/extract/extract_geographical_area_memberships.php");
require(dirname(__FILE__) . "../../classes/extract/extract_measure_types.php");
require(dirname(__FILE__) . "../../classes/extract/extract_base_regulations.php");
require(dirname(__FILE__) . "../../classes/extract/extract_quota_definitions.php");
require(dirname(__FILE__) . "../../classes/extract/extract_footnotes.php");
require(dirname(__FILE__) . "../../classes/extract/extract_certificates.php");

/* Prototype classes */
require(dirname(__FILE__) . "../../measures/measure_activity.php");



if (isset($_COOKIE["showing"])) {
    $scope = $_COOKIE["showing"];
} else {
    $scope = "Now";
}

$critical_date = "2020-01-31";
$critical_date_plus_one = "2020-02-01";

$msg = "All data displayed uses the <strong>" . $dbase . "</strong> database";

$pagesize = 100;
$conn = pg_connect("host=127.0.0.1 port=5432 dbname=" . $dbase . " user=postgres password=" . $pwd);
$page = intval(get_querystring("page"));
if ($page == 0) {
    $page = 1;
}

function db_to_date($var)
{
    if ($var != "") {
        $var2 = DateTime::createFromFormat('Y-m-d', $var);
    } else {
        $var2 = "";
    }
    return ($var2);
}

function string_to_date($var)
{
    if ($var != "") {
        $var2 = DateTime::createFromFormat('Y-m-d H:i:s', $var)->format('Y-m-d');
    } else {
        $var2 = "";
    }
    return ($var2);
}

function string_to_time($var)
{
    if ($var != "") {
        $var2 = DateTime::createFromFormat('Y-m-d H:i:s', $var)->format('d M y H:i');
    } else {
        $var2 = "";
    }
    return ($var2);
}

function get_checked($key, $value)
{
    if ($key == $value) {
        return (" checked");
    } else {
        return ("");
    }
}

function get_checked_array($key, $value)
{
    if (is_array($key)) {
        $checked = false;
        foreach ($key as $key_item) {
            if (strval($key_item) == strval($value)) {
                $checked = true;
                break;
            }
        }
        if ($checked == true) {
            return (" checked");
        } else {
            return ("");
        }
    } else {
        if ($key == $value) {
            return (" checked");
        } else {
            return ("");
        }
    }
}

function get_cookie($key)
{
    if (isset($_COOKIE[$key])) {
        $s = $_COOKIE[$key];
        return ($_COOKIE[$key]);
        /*
 if ($s == "0") {
 return ("");
 } else {
 return ($_COOKIE[$key]);
 }
 */
    } else {
        return ("");
    }
}

function get_formvar($key, $prefix = "", $store_cookie = False)
{
    $s = "";
    $prefix = "";
    if (isset($_REQUEST[$key])) {
        $s = trim($_REQUEST[$key]);
    }
    if ($s == "") {
        $s = Null;
    }

    if ($store_cookie) {
        setcookie($prefix . $key, $s, time() + (86400 * 30), "/");
    }

    return ($s);
}

function get_querystring($key)
{
    if (isset($_GET[$key])) {
        return (trim($_GET[$key]));
    }
}

function geographical_code($id)
{
    switch ($id) {
        case "0":
            return ("Country");
            break;
        case "1":
            return ("Area group");
            break;
        case "2":
            return ("Region");
            break;
    }
}

function trade_movement_code($id)
{
    switch ($id) {
        case "0":
            return ("Used for import measures");
            break;
        case "1":
            return ("Used for export measures");
            break;
        case "2":
            return ("Used for import or export measures");
            break;
    }
}

function measure_component_applicable_code($id)
{
    switch ($id) {
        case "0":
            return ("Permitted");
            break;
        case "1":
            return ("Mandatory");
            break;
        case "2":
            return ("Not permitted");
            break;
    }
}

function order_number_capture_code($id)
{
    switch ($id) {
        case "1":
            return ("Mandatory");
            break;
        case "2":
            return ("Not permitted");
            break;
    }
}

function origin_dest_code($id)
{
    switch ($id) {
        case "0":
            return ("Origin");
            break;
        case "1":
            return ("Destination");
            break;
    }
}

function footnote_type_application_code($id)
{
    switch ($id) {
        case "1":
            return ("CN nomenclature");
            break;
        case "2":
            return ("TARIC nomenclature");
            break;
        case "3":
            return ("Export refund nomenclature");
            break;
        case "4":
            return ("Wine reference nomenclature");
            break;
        case "5":
            return ("Additional codes");
            break;
        case "6":
            return ("CN measures");
            break;
        case "7":
            return ("Other measures");
            break;
        case "8":
            return ("Meursing Heading");
            break;
        case "9":
            return ("Dynamic footnote");
            break;
    }
}

function yn($var)
{
    $var = intval($var);
    if ($var == 0) {
        return ("N");
    } else {
        return ("Y");
    }
}

function yn2($var)
{
    $var = intval($var);
    if ($var == 0) {
        return ("");
    } else {
        return ("Y");
    }
}
function yn3($var)
{
    $var = intval($var);
    if ($var == 0) {
        return ("No");
    } else {
        return ("Yes");
    }
}

function standardise($var)
{
    $pos = strpos($var, "-");
    if ($pos == false) {
        return ($var);
    } else {
        $d2 = date("d/m/Y", strtotime($var));
        #h1 ($pos);
        return ($d2);
    }
}

function rowclass($validity_start_date, $validity_end_date)
{
    #h1 ($validity_end_date);
    $validity_start_date = standardise($validity_start_date);
    $validity_end_date = standardise($validity_end_date);

    $rowclass = "";
    if (($validity_start_date == "31/10/2019") || ($validity_start_date == "01/11/2019")) {
        $rowclass = "uk";
    } elseif (($validity_start_date == "01/01/2019") && ($validity_end_date == NULL)) {
        $rowclass = "starts2019";
    } elseif ($validity_end_date != "-") {
        /*
 $d2 = date("Y-m-d", strtotime($validity_end_date));
 if (is_in_future($validity_end_date)) {
 $rowclass = "";
 } else {
 $rowclass = "dead";
 }
 */
        $today = date("d/m/Y");
        $d_today = strtotime($today);
        $d_ved = strtotime($validity_end_date);
        $diff = $d_ved - $d_today;
        if ($diff < 0) {
            #$rowclass = "dead";
        }
    }

    return ($rowclass);
}

function is_in_future($var)
{
    #q ($var);
    $var2 = DateTime::createFromFormat('d/m/Y', $var);
    $diff = time() - strtotime($var);

    if ($diff > 0) {
        return (false);
    } else {
        return (true);
    }
}

function title_case($s)
{
    $s = ucwords(strtolower($s));
    $s = str_replace(" And ", " and ", $s);
    $s = str_replace(" The ", " the ", $s);
    $s = str_replace(" In ", " in ", $s);
    $s = str_replace(" Of ", " of ", $s);
    $s = str_replace(" An ", " an ", $s);
    $s = str_replace(" On ", " on ", $s);
    $s = str_replace(" Or ", " or ", $s);
    $s = str_replace(" Not ", " not ", $s);
    return ($s);
}

function string_to_filtered_list($s)
{
    $s = str_replace("\r\n", ",", $s);
    $s = str_replace("\n\r", ",", $s);
    $s = str_replace("\n", ",", $s);
    $s = str_replace("\t", ",", $s);
    $s = str_replace("\r", ",", $s);
    $s = str_replace(" ", ",", $s);
    $s = str_replace(".", ",", $s);

    $s_exploded = explode(",", $s);
    $s_exploded = array_filter($s_exploded);
    return ($s_exploded);
}

function set($data)
{
    return array_map("unserialize", array_unique(array_map("serialize", $data)));
}

function get_measure($measure_sid)
{
    global $conn;
    $measure = new measure;
    $measure->set_properties($measure_sid, "", "", "", "", "", "", "", "", "");

    $sql = "SELECT mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code,
 ded.description as duty_expression_description, mud.description as measurement_unit_description, muqd.description as measurement_unit_qualifier_description
 FROM duty_expression_descriptions ded, measurement_unit_qualifier_descriptions muqd RIGHT OUTER JOIN 
 measure_components mc ON mc.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code
 LEFT OUTER JOIN measurement_unit_descriptions mud ON mc.measurement_unit_code = mud.measurement_unit_code
 WHERE measure_sid = " . $measure_sid . " AND ded.duty_expression_id = mc.duty_expression_id ORDER BY duty_expression_id";
    $result = pg_query($conn, $sql);
    if ($result) {
        while ($row = pg_fetch_array($result)) {
            $duty_expression_id = $row['duty_expression_id'];
            $duty_amount = $row['duty_amount'];
            $monetary_unit_code = $row['monetary_unit_code'];
            $measurement_unit_code = $row['measurement_unit_code'];
            $measurement_unit_qualifier_code = $row['measurement_unit_qualifier_code'];
            $measurement_unit_description = $row['measurement_unit_description'];
            $measurement_unit_qualifier_description = $row['measurement_unit_qualifier_description'];

            // These may need to be populated later
            $goods_nomenclature_item_id = "";
            $additional_code_type_id = "";
            $additional_code_id = "";
            $measure_type_id = "";
            $quota_order_number_id = "";
            $geographical_area_id = "";
            $validity_start_date = "";
            $validity_end_date = "";

            $d = new duty;
            $d->set_properties(
                $goods_nomenclature_item_id,
                $additional_code_type_id,
                $additional_code_id,
                $measure_type_id,
                $duty_expression_id,
                $duty_amount,
                $monetary_unit_code,
                $measurement_unit_code,
                $measurement_unit_qualifier_code,
                $measure_sid,
                $quota_order_number_id,
                $geographical_area_id,
                $validity_start_date,
                $validity_end_date
            );

            array_push($measure->duty_list, $d);
        }
    }
    $measure->combine_duties();
    #echo ("Combined duty: " . $measure->combined_duty);
    return ($measure);
}
function update_type($i)
{
    switch ($i) {
        case "1":
            return ("1 Update");
            break;
        case "2":
            return ("2 Delete");
            break;
        case "3":
            return ("3 Insert");
            break;
    }
}

function xml_item($item, $value)
{
    print('<tr class="govuk-table__row"><td class="govuk-table__cell">' . $item . '</td><td class="govuk-table__cell b">' . $value . '</td></tr>');
}

function xml_head($type, $i)
{
    print("<h3>$type record $i</h3>");
    print('<table class="govuk-table" cellspacing="0">');
    print('<tr class="govuk-table__row"><th class="govuk-table__header" style="width:15%">Property</th><th class="govuk-table__header" style="width:85%">Value</th></tr>');
}

function xml_foot()
{
    print("</table>");
    print('<p class="back_to_top"><a href="#top">Back to top</a></p>');
}

function xml_count($id, $display, $xpath, $xml)
{
    $nodes = $xml->xpath($xpath);
    $cnt = count($nodes);
    if ($cnt > 0) {
        print('<li><a href="#' . $id . '">' . $display . ' (' . $cnt . ' instances)</a></li>');
    }
}
function pre($data)
{
    print '<pre>' . print_r($data, true) . '</pre>';
}

function prend($data)
{
    print '<pre>' . print_r($data, true) . '</pre>';
    die();
}

function prex($data)
{
    $data = str_replace('&', '&amp;', $data);
    $data = str_replace('<', '&lt;', $data);
    $data = str_replace('>', '&gt;', $data);
    print '<pre>' . print_r($data, true) . '</pre>';
}

function to_date($day, $month, $year)
{
    $day = str_pad($day, 2, '0', STR_PAD_LEFT);
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);
    $date = $year . "-" . $month . "-" . $day;
    $d = DateTime::createFromFormat('Y-m-d', $date);
    #echo ($d);
    return ($d);
}

function to_date_string($day, $month, $year)
{
    $day = str_pad($day, 2, '0', STR_PAD_LEFT);
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);
    if (strlen($year) == 2) {
        if (intval($year) > 70) {
            $year = "19" . $year;
        } else {
            $year = "20" . $year;
        }
    }
    $date = $year . "-" . $month . "-" . $day;
    return ($date);
}

function to_part_of_year_string($day, $month, $year)
{
    $date = new DateTime();
    $date->setDate($year, $month, $day);
    $ret =  $date->format('d F');

    return ($ret);
}

function p($s)
{
    echo ("<p>" . $s . "</p>");
}

function h1($s)
{
    echo ("<h1>" . $s . "</h1>");
}

function h1end($s)
{
    echo ("<h1>" . $s . "</h1>");
    die();
}

function h2($s)
{
    echo ("<h2>" . $s . "</h2>");
}

function h3($s)
{
    echo ("<h3>" . $s . "</h3>");
}

function q($s)
{
    echo ("<p>" . $s . "</p>");
    exit();
}

function short_date($s, $keep_spaces = false)
{
    if ($s == "") {
        $s2 = "-";
    } else {
        $s2 = date("d M y", strtotime($s));
        if (!$keep_spaces) {
            $s2 = str_replace(" ", "&nbsp;", $s2);
        }
    }
    return ($s2);
}

function short_date_rev($s, $use_nulls = false)
{
    if ($s == "") {
        if ($use_nulls) {
            $s2 = "";
        } else {
            $s2 = "-";
        }
    } else {
        $s2 = date("Y-m-d", strtotime($s));
    }
    return ($s2);
}

function xml_date($s)
{
    if ($s == "") {
        $s2 = "";
    } else {
        $s2 = date("Y-m-d", strtotime($s));
    }
    return ($s2);
}

function dm($s)
{
    if ($s == "") {
        $s2 = "-";
    } else {
        $s2 = date("d/m", strtotime($s));
    }
    return ($s2);
}

function vshort_date($s)
{
    if ($s == "") {
        $s2 = "-";
    } else {
        $s2 = date("d/m/y", strtotime($s));
    }
    return ($s2);
}

function standardise_goods_nomenclature_item_id($goods_nomenclature_item_id)
{
    $goods_nomenclature_item_id = str_replace(" ", "", $goods_nomenclature_item_id);
    if (strlen($goods_nomenclature_item_id) < 10) {
        $goods_nomenclature_item_id .= str_repeat("0", 10 - strlen($goods_nomenclature_item_id));
    }
    return ($goods_nomenclature_item_id);
}

function format_goods_nomenclature_item_id($s)
{
    $s2 = "";
    $len = strlen($s);
    switch ($len) {
        case 10:
            $s2 = "<span class='rpad mauve'>" . substr($s, 0, 4) . "</span><span class='rpad blue'>" . substr($s, 4, 2) . "</span><span class='rpad blue'>" . substr($s, 6, 2) . "</span><span class='rpad green'>" . substr($s, 8, 2) . "</span>";
            break;
        case 8:
            $s2 = "<span class='rpad mauve'>" . substr($s, 0, 4) . "</span><span class='rpad blue'>" . substr($s, 4, 2) . "</span><span class='rpad blue'>" . substr($s, 6, 2) . "</span>";
            break;
        case 6:
            $s2 = "<span class='rpad mauve'>" . substr($s, 0, 4) . "</span><span class='rpad blue'>" . substr($s, 4, 2) . "</span>";
            break;
        case 4:
            $s2 = "<span class='rpad mauve'>" . substr($s, 0, 4) . "</span>";
            break;
        case 2:
            $s2 = "<span class='rpad mauve'>" . substr($s, 0, 2) . "</span>";
            break;
    }
    return ($s2);
}

function bool_to_int($var)
{
    if (($var == "t") || ($var == true)) {
        return (1);
    } else {
        return (0);
    }
}

function duty_format($var)
{
    if ($var == Null) {
        return ("");
    } else {
        return (number_format($var, 3));
    }
}

function reduce($var)
{
    if (substr($var, -8) == "00000000") {
        return (substr($var, 0, 2));
    } elseif (substr($var, -6) == "000000") {
        return (substr($var, 0, 4));
    } elseif (substr($var, -4) == "0000") {
        return (substr($var, 0, 6));
    } elseif (substr($var, -2) == "00") {
        return (substr($var, 0, 8));
    } else {
        return ($var);
    }
}

function explode_string($var)
{
    $array = explode(",", $var);
    $s = "";
    foreach ($array as $type) {
        $s .= "'" . $type . "', ";
    }
    $s = trim($s);
    $s = trim($s, ",");
    return ($s);
}

function get_operation($s)
{
    switch ($s) {
        case "U":
            #h1 ("here");
            $s2 = "1";
            break;
        case "D":
            $s2 = "2";
            break;
        case "C":
            $s2 = "3";
            break;
    }
    return ($s2);
}

function php_set($data)
{
    return array_map("unserialize", array_unique(array_map("serialize", $data)));
}

function trunc($s, $len)
{
    if (strlen($s) > $len) {
        return (substr($s, 0, $len)) . "...";
    } else {
        return ($s);
    }
}
function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}
function to_required_string($s)
{
    if ($s == true) {
        return ("required");
    } else {
        return (" ");
    }
}

function string_before($s, $term) {
    $term_pos = strpos($s, "-");
    if ($term_pos !== -1) {
        $s = substr($s, 0, $term_pos - 1);
    }
    return (trim($s));
}

function put_spaces_round_slashes($s) {
    $s = str_replace("/", " / ", $s);
    $s = str_replace("  ", " ", $s);
    return ($s);
}

function underscore($s) {
    $s = str_replace(' ', '_', $s);
    return ($s);
}

function db_execute($sql, $array) {
    global $conn;
    $stmt = "stmt_" . uniqid();
    pg_prepare($conn, $stmt, $sql);
    pg_execute($conn, $stmt, $array);
}
?>