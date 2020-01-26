<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("footnotes");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $footnote = new footnote();
    $footnote->validate_form();
} else {
    $footnote = new footnote();
    $footnote->get_parameters();
    $application->get_footnote_types();
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
        <?php
        require("../includes/phase_banner.php");
        $control_content = array();
        
        $control_content["footnote_type_id"] = $application->footnote_types;
        $control_content["footnote_descriptions"] = $footnote->descriptions;
        if ($footnote->application_code_description == "Measure-related footnote"){
            $control_content["footnote_assignments"] = $footnote->footnote_assignments;
        } elseif ($footnote->application_code_description == "Nomenclature-related footnote"){
            $control_content["footnote_assignments"] = $footnote->footnote_assignments;
        }
        new data_entry_form($control_content, $footnote, $left_nav = "");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>