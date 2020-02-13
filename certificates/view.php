<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("certificates");
$certificate = new certificate();
$certificate->get_parameters();
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
        $control_content["certificate_descriptions"] = $certificate->descriptions;
        $control_content["versions"] = $certificate->versions;
        new view_form($control_content, $certificate);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>