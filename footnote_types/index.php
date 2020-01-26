<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("footnote_types");

$application->get_filter_options();
$application->get_footnote_types();
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
        new search_form($application->footnote_types, $filter_content);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>