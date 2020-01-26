<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers4");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));

/*
pre ($_REQUEST);
pre ($_SESSION);
*/

if ($submitted == 1) {
    $_SESSION["duties"] = get_formvar("duties");

    $quota_order_number = new quota_order_number();
    $quota_order_number->validate_form_step4();
} else {
    $quota_order_number = new quota_order_number();
    $quota_order_number->get_parameters();
}

$quota_order_number = new quota_order_number;
$quota_order_number->duties_same_for_all_commodities = get_formvar("duties_same_for_all_commodities");
$quota_order_number->commodity_codes = $_SESSION["commodity_codes"];
$quota_order_number->get_commodity_code_array();


//$quota_order_number = new quota_order_number();
//$quota_order_number->get_parameters();

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
        new data_entry_form($control_content, $quota_order_number, $left_nav = ""); //, "create_edit5.html");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>

