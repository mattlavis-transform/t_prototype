<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("regulations");

$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $base_regulation = new base_regulation();
    $base_regulation->validate_form();
} else {
    $base_regulation = new base_regulation();
    $base_regulation->get_parameters();
    $application->get_regulation_groups();
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
        $control_content["regulation_group_id"] = $application->regulation_groups;
        new data_entry_form($control_content, $base_regulation, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>