<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("rules_of_origin_schemes");
$roo = new rules_of_origin_scheme();
$roo->rules_of_origin_scheme_sid = get_querystring("rules_of_origin_scheme_sid");
$roo->get_rules();
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
        new roo_rules_table_control($roo->roo_rows);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>