<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("geographical_areas");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $geographical_area = new geographical_area();
    $geographical_area->validate_form();
} else {
    $geographical_area = new geographical_area();
    $geographical_area->get_parameters();
    $application->get_geographical_codes();
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

        $control_content["geographical_code"] = $application->geographical_codes;
        $control_content["geographical_area_descriptions"] = $geographical_area->descriptions;
        $control_content["geographical_area_memberships"] = $geographical_area->members;
        $control_content["roo_scheme_memberships"] = $geographical_area->roo_members;
        new data_entry_form($control_content, $geographical_area, $left_nav = "");

        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>