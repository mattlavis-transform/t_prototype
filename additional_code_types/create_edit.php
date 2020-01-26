<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("additional_code_types");
$application->get_additional_code_application_codes();
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $additional_code_type = new additional_code_type();
    $additional_code_type->validate_form();
} else {
    $additional_code_type = new additional_code_type();
    $additional_code_type->get_parameters();
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
        $control_content["measure_types"] = $additional_code_type->measure_types;
        $control_content["application_code"] = $application->additional_code_application_codes;
        new data_entry_form($control_content, $additional_code_type, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>