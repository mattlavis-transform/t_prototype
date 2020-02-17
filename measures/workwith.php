<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
pre ($_REQUEST);
$application->init("work_with_measures", "config_edit.json");
$error_handler = new error_handler();
$measure_activity = new measure_activity();
$edit_activity_option = get_formvar("edit_activity_option");
if ($edit_activity_option != "") {
    $measure_activity->execute_activity_option();
} else {
    $measure_activity->get_measure_sids();
    $measure_activity->get_activity_options();    
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
        ?>
                    <?php
                    $control_content = array();
                    $control_content["edit_activity_option"] = $measure_activity->activity_options;
                    new data_entry_form($control_content, $measure_activity, $left_nav = "");
                    ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>