<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("regulations");
$application->get_filter_options();
$application->get_all_regulations();
$application->get_regulation_groups();
$application->get_start_dates();
$application->get_active_states();
$application->get_regulation_scopes();
$application->get_regulation_types();
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
        array_push($filter_content, $application->regulation_scopes);
        array_push($filter_content, $application->regulation_groups);
        array_push($filter_content, $application->start_dates);
        array_push($filter_content, $application->regulation_types);
        array_push($filter_content, $application->active_states);
        new search_form(
            $application->base_regulations,
            $filter_content
        );
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>