<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measure_types");

$application->get_filter_options();
$application->get_active_states();
$application->get_measure_types();
$application->get_measure_type_series();
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
        $filter_content = array();
        array_push($filter_content, $application->measure_type_series);
        array_push($filter_content, $mt->trade_movement_codes);
        array_push($filter_content, $mt->measure_component_applicable_codes);
        array_push($filter_content, $mt->order_number_capture_codes);
        array_push($filter_content, $application->active_states);
        new search_form($application->measure_types, $filter_content);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>