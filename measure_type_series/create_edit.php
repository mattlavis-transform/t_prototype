<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measure_type_series");

$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
$application->get_measure_type_combinations();
if ($submitted == 1) {
    h1 ($submitted);
    $measure_type_series = new measure_type_series();
    $measure_type_series->validate_form();
} else {
    $measure_type_series = new measure_type_series();
    $measure_type_series->get_parameters();
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
        $control_content["measure_type_combination"] = $application->measure_type_combinations;
        new data_entry_form($control_content, $measure_type_series, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>