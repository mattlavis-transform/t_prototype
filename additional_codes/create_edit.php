<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("additional_codes");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
$application->get_additional_code_types();
if ($submitted == 1) {
    $additional_code = new additional_code();
    $additional_code->validate_form();
} else {
    $additional_code = new additional_code();
    $additional_code->get_parameters();
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
        $control_content["additional_code_type_id"] = $application->additional_code_types;
        $control_content["additional_code_descriptions"] = $additional_code->descriptions;
        new data_entry_form($control_content, $additional_code, $left_nav = "");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>