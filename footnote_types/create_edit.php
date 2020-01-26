<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("footnote_types");

$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $footnote_type = new footnote_type();
    $footnote_type->validate_form();
} else {
    $footnote_type = new footnote_type();
    $footnote_type->get_parameters();
    $application->get_footnote_application_codes();
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
        $control_content["application_code"] = $application->footnote_application_codes;
        new data_entry_form($control_content, $footnote_type, $left_nav = "");

        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>