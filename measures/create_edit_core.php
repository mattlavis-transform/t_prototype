<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures_core");
$error_handler = new error_handler();

// If there is no measure prototype in place, then create it
$measure_activity = new measure_activity();

$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $_SESSION["validity_start_date_day"] = get_formvar("validity_start_date_day");
    $_SESSION["validity_start_date_month"] = get_formvar("validity_start_date_month");
    $_SESSION["validity_start_date_year"] = get_formvar("validity_start_date_year");
    $_SESSION["validity_end_date_day"] = get_formvar("validity_end_date_day");
    $_SESSION["validity_end_date_month"] = get_formvar("validity_end_date_month");
    $_SESSION["validity_end_date_year"] = get_formvar("validity_end_date_year");
    $_SESSION["measure_generating_regulation_id"] = get_formvar("measure_generating_regulation_id");
    $_SESSION["measure_type_id"] = get_formvar("measure_type_id");
    $_SESSION["geographical_area_id_countries"] = get_formvar("geographical_area_id_countries");
    $measure_activity->validate_form_core();
} else {
    $measure_activity_sid = null;
    if (isset($_SESSION["measure_sid"])) {
        $measure_activity_sid = $_SESSION["measure_sid"];
        $measure_activity->measure_sid = $measure_activity_sid;
        $measure_activity->populate_core_from_db();
    } else {
        $measure_activity_sid = $application->session->workbasket->create_measure();
    }
}

?>

<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("../includes/metadata.php");
?>

<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("../includes/phase_banner.php");
        $control_content = array();
        new data_entry_form($control_content, $measure_activity, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>