<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures_commodities");
$application->get_duties_same_for_all_commodities();

$error_handler = new error_handler();

$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $measure_prototype = new measure_prototype();
    $measure_prototype->validate_form_commodities();
} else {
    $measure_prototype = new measure_prototype();
    $measure_prototype->get_sid();
    $measure_prototype->populate_commodity_form();
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
        $control_content["duties_same_for_all_commodities"] = $application->duties_same_for_all_commodities;
        new data_entry_form($control_content, $measure_prototype, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>