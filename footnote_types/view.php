<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("footnote_types");
$footnote_type = new footnote_type();
$footnote_type->get_parameters();
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
        $control_content["versions"] = $footnote_type->versions;
        new view_form($control_content, $footnote_type);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>