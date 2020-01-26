<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers");
$error_handler = new error_handler();
$application->get_quota_mechanisms();
$application->get_quota_categories();
$application->get_quota_measure_types();
$application->get_quota_period_types();
$application->get_quota_origin_quota_options();

$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $_SESSION["measure_generating_regulation_id"] = get_formvar("measure_generating_regulation_id");
    $_SESSION["quota_mechanism"] = get_formvar("quota_mechanism");
    $_SESSION["quota_category"] = get_formvar("quota_category");
    $_SESSION["quota_order_number_id"] = get_formvar("quota_order_number_id");
    $_SESSION["description"] = get_formvar("description");
    $_SESSION["geographical_area_id_countries"] = get_formvar("geographical_area_id_countries");

    $quota_order_number = new quota_order_number();
    $quota_order_number->validate_form_step1();
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
        $control_content["quota_mechanism"] = $application->quota_mechanisms;
        $control_content["quota_category"] = $application->quota_categories;
        $control_content["measure_type_id"] = $application->quota_measure_types;
        $control_content["origin_quota"] = $application->quota_origin_quota_options;
        $control_content["period_type"] = $application->quota_period_types;
        new data_entry_form($control_content, $quota_order_number, $left_nav = ""); // "create_edit2.html");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>

