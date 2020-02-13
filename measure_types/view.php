<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measure_types");
$measure_type = new measure_type();
$measure_type->get_parameters();
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
        $control_content["versions"] = $measure_type->versions;
        new view_form($control_content, $measure_type);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>