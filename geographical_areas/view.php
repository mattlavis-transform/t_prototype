<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("geographical_areas");
$geographical_area = new geographical_area();
$geographical_area->get_parameters();
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
        $control_content["geographical_area_descriptions"] = $geographical_area->descriptions;
        $control_content["geographical_area_memberships"] = $geographical_area->members;
        $control_content["roo_scheme_memberships"] = $geographical_area->roo_members;
        $control_content["versions"] = $geographical_area->versions;
        new view_form($control_content, $geographical_area);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>