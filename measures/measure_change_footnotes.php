<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("change_footnotes", "config_edit.json");
$measure_activity = new measure_activity();
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
} else {
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
                    //pre($_REQUEST);
                    $control_content = array();
                    $control_content["remove_existing_footnotes"] = $application->get_yes_no();
                    new data_entry_form($control_content, $measure_activity, $left_nav = "");
                    ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>