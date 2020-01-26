<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("rules_of_origin_schemes");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $roo = new rules_of_origin_scheme();
    $roo->validate_form();
} else {
    $roo = new rules_of_origin_scheme();
    $roo->get_parameters();
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
        new data_entry_form($control_content, $roo, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>