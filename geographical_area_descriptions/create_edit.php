<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("geographical_area_descriptions");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $geographical_area = new geographical_area();
    $geographical_area->validate_form();
} else {
    $geographical_area = new geographical_area();
    $geographical_area->get_parameters($description = true);
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
        <form action="geographical_area_description_confirmation.html">
            <?php
            require("../includes/phase_banner.php");
            $control_content = array();
            new data_entry_form($control_content, $geographical_area, $left_nav = "");

            ?>
        </form>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>