<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("workbaskets");
$application->get_filter_options();
$application->get_workbaskets();
$application->get_workbasket_statuses();
$application->get_users();
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
        array_push($filter_content, $application->users);
        array_push($filter_content, $application->workbasket_statuses);
        //var_dump ($application->users);
        $application->search_form = new search_form($application->workbaskets, $filter_content);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>