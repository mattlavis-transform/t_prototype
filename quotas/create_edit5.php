<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers5");
$application->get_measurement_units($use_common = true);
$application->get_measurement_unit_qualifiers();
$application->get_quota_precisions();

$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));

if ($submitted == 1) {
    $_SESSION["measurement_unit_code"] = get_formvar("measurement_unit_code");
    $_SESSION["measurement_unit_qualifier_code"] = get_formvar("measurement_unit_qualifier_code");
    $_SESSION["maximum_precision"] = get_formvar("maximum_precision");
    $_SESSION["critical_threshold"] = get_formvar("critical_threshold");

    $quota_order_number = new quota_order_number();
    $quota_order_number->validate_form_step5();
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
        $control_content["measurement_unit_code"] = $application->measurement_units;
        $control_content["measurement_unit_qualifier_code"] = $application->measurement_unit_qualifiers;
        $control_content["maximum_precision"] = $application->quota_precisions;
        new data_entry_form($control_content, $quota_order_number, $left_nav = ""); //, "create_edit6.html");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>

