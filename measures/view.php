<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures");
$error_handler = new error_handler();
$measure = new measure();
$measure->get_parameters();
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

        $control_content["measure_components"] = $measure->measure_components;
        $control_content["measure_conditions"] = $measure->measure_conditions;
        $control_content["measure_footnotes"] = $measure->measure_footnotes;
        $control_content["measure_exclusions"] = $measure->measure_exclusions;
        $control_content["versions"] = $measure->versions;
        new view_form($control_content, $measure);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>