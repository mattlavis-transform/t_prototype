<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("footnotes");
$error_handler = new error_handler();
$footnote = new footnote();
$footnote->get_parameters();
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
        $control_content["footnote_descriptions"] = $footnote->descriptions;
        //prend ($footnote->footnote_assignments);
        
        if ($footnote->application_code_description == "Measure-related footnote"){
            $control_content["footnote_assignments"] = $footnote->footnote_assignments;
        } elseif ($footnote->application_code_description == "Nomenclature-related footnote"){
            $control_content["footnote_assignments"] = $footnote->footnote_assignments;
        }
        $control_content["application_code_description"] = $footnote->application_code_description;
        $control_content["versions"] = $footnote->versions;

        new view_form($control_content, $footnote);
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>