<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures");
$application->get_filter_options();
$application->get_start_dates();
$application->get_measures();
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
        array_push($filter_content, $application->start_dates);
        $application->search_form = new search_form($application->quota_measures, $filter_content);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>