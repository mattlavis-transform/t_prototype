<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("goods_nomenclatures");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $goods_nomenclature = new goods_nomenclature();
    $goods_nomenclature->validate_form();
} else {
    $goods_nomenclature = new goods_nomenclature();
    $goods_nomenclature->get_parameters();
}
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
        $control_content = array();
        $control_content["goods_nomenclature_descriptions"] = $goods_nomenclature->descriptions;
        new data_entry_form($control_content, $goods_nomenclature, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>