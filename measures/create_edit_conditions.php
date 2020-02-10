<?php
require(dirname(__FILE__) . "../../includes/db.php");
//prend ($_REQUEST);
$application = new application;
$application->init("measures_conditions");
$error_handler = new error_handler();

$submitted = intval(get_formvar("submitted"));
$action = get_querystring("action");
if ($submitted == 1) {
    $measure = new measure();
    $measure->get_sid();
    $add_condition = get_formvar("add_condition");
    if ($add_condition == 'add_condition') {
        //$measure->add_condition();
    }
} else {
    $measure = new measure();
    $measure->get_sid();
    if ($action == "remove_condition") {
        //$measure->delete_condition();
    }
    $measure->populate_conditions_form();
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
        new data_entry_form($control_content, $measure, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>