<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("certificates");
$error_handler = new error_handler();
$application->get_certificate_types();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $certificate = new certificate();
    $certificate->validate_form();
} else {
    $certificate = new certificate();
    $certificate->get_parameters();
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
        $mt = new certificate();
        $control_content = array();
        $control_content["certificate_type_code"] = $application->certificate_types;
        $control_content["certificate_descriptions"] = $certificate->descriptions;
        new data_entry_form($control_content, $certificate, $left_nav = "");

        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>