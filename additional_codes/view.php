<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("additional_codes");
$additional_code = new additional_code();
$additional_code->get_parameters();
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
        $control_content["additional_code_descriptions"] = $additional_code->descriptions;
        $control_content["versions"] = $additional_code->versions;
        new view_form($control_content, $additional_code);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>