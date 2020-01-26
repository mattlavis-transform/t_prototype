<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("additional_codes");
$application->get_filter_options();
$application->get_additional_codes();
$application->get_active_states();
$application->get_additional_code_types();
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

        $filter_content = array();
        array_push($filter_content, $application->additional_code_types);
        array_push($filter_content, $application->start_dates);
        array_push($filter_content, $application->active_states);
        $application->search_form = new search_form($application->additional_codes, $filter_content);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>