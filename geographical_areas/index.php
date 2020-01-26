<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("geographical_areas");

$application->get_filter_options();
$application->get_active_states();
$application->get_geographical_areas();
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
        array_push($filter_content, $application->geographical_codes);
        array_push($filter_content, $application->active_states);
        new search_form(
            $application->geographical_areas,
            $filter_content
        );
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>