<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("additional_code_types");
$additional_code_type = new additional_code_type();
$additional_code_type->get_parameters();
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
        $control_content["measure_types"] = $additional_code_type->measure_types;
        $control_content["versions"] = $additional_code_type->versions;
        new view_form($control_content, $additional_code_type);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>