<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers2");
$application->get_quota_origin_quota_options();
$error_handler = new error_handler();

$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $_SESSION["quota_scope"] = get_formvar("quota_scope");
    $_SESSION["quota_staging"] = get_formvar("quota_staging");
    $_SESSION["origin_quota"] = get_formvar("origin_quota");

    $quota_order_number = new quota_order_number();
    $quota_order_number->validate_form_step2();
} else {
    $quota_order_number = new quota_order_number();
    $quota_order_number->get_parameters();
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
        $control_content["origin_quota"] = $application->quota_origin_quota_options;
        new data_entry_form($control_content, $quota_order_number, $left_nav = ""); //, "create_edit3.html");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>