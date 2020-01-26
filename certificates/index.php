<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("certificates");
$application->get_filter_options();
$application->get_active_states();
$application->get_certificates();
$application->get_certificate_types();
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
        array_push($filter_content, $application->certificate_types);
        array_push($filter_content, $application->active_states);
        new search_form($application->certificates, $filter_content);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>