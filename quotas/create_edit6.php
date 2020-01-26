<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers6");
$error_handler = new error_handler();
$application->get_quota_period_types();
$application->get_quota_introductory_period_options();

$submitted = intval(get_formvar("submitted"));


if ($submitted == 1) {
    $_SESSION["measurement_unit_code"] = get_formvar("measurement_unit_code");
    $_SESSION["measurement_unit_qualifier_code"] = get_formvar("measurement_unit_qualifier_code");
    $_SESSION["maximum_precision"] = get_formvar("maximum_precision");
    $_SESSION["critical_threshold"] = get_formvar("critical_threshold");
}



if ($submitted == 1) {
    $_SESSION["period_type"] = get_formvar("period_type");
    $_SESSION["validity_start_date_day"] = get_formvar("validity_start_date_day");
    $_SESSION["validity_start_date_month"] = get_formvar("validity_start_date_month");
    $_SESSION["validity_start_date_year"] = get_formvar("validity_start_date_year");
    $_SESSION["validity_end_date_day"] = get_formvar("validity_end_date_day");
    $_SESSION["validity_end_date_month"] = get_formvar("validity_end_date_month");
    $_SESSION["validity_end_date_year"] = get_formvar("validity_end_date_year");
    $_SESSION["year_count"] = get_formvar("year_count");
    $_SESSION["introductory_period_option"] = get_formvar("introductory_period_option");

    $quota_order_number = new quota_order_number();
    $quota_order_number->validate_form_step6();
} else {
    $quota_order_number = new quota_order_number();
    $quota_order_number->get_parameters();
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
        $control_content["period_type"] = $application->quota_period_types;
        $control_content["introductory_period_option"] = $application->quota_introductory_period_options;
        new data_entry_form($control_content, $quota_order_number, $left_nav = ""); // , "create_edit7.html");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>

