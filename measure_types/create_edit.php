<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measure_types");

$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $measure_type = new measure_type();
    $measure_type->validate_form();
} else {
    $measure_type = new measure_type();
    $measure_type->get_parameters();
    $application->get_measure_type_series();
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
        $mt = new measure_type();
        $control_content = array();

        $control_content["measure_type_series_id"] = $application->measure_type_series;
        $control_content["trade_movement_code"] = $mt->trade_movement_codes;
        $control_content["measure_component_applicable_code"] = $mt->measure_component_applicable_codes;
        $control_content["order_number_capture_code"] = $mt->order_number_capture_codes;

        new data_entry_form($control_content, $measure_type, $left_nav = "");

        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>