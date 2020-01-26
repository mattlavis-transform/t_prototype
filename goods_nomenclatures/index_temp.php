<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("goods_nomenclatures");
$application->get_filter_options();
$application->get_goods_nomenclatures();
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
        $application->search_form = new search_form($application->goods_nomenclatures, $filter_content);
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>
</html>