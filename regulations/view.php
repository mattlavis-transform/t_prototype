<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("regulations");
$base_regulation = new base_regulation();
$base_regulation->get_parameters();
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
        //prend ($measure_type);
        new view_form($control_content, $base_regulation);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>