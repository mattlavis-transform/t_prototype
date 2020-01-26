<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("footnotes");
$application->get_filter_options();
$application->get_footnotes();
$application->get_active_states();
$application->get_footnote_types();
$application->get_start_dates();

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

        $mt = new measure_type();
        $filter_content = array();
        array_push($filter_content, $application->footnote_types);
        array_push($filter_content, $application->start_dates);
        array_push($filter_content, $application->active_states);
        new search_form($application->footnotes, $filter_content);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>