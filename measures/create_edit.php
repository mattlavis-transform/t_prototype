<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures_activity");
$error_handler = new error_handler();

// If there is no measure activity in place, then create it
$measure_activity = new measure_activity();

$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $a = 1;
    $_SESSION["actvity_name"] = get_formvar("activity_name");
    $measure_activity->validate_form_activity_name();
} else {
    $measure_sid = null;
    if (isset($_SESSION["measure_sid"])) {
        $measure_sid = $_SESSION["measure_sid"];
        $measure_activity->measure_activity_sid = $measure_activity_sid;
        $measure_activity->populate_activity_from_db();
    } else {
        //$measure_sid = $application->session->workbasket->create_measure();
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