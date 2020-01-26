<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers7");
$error_handler = new error_handler();
$submitted = intval(get_formvar("submitted"));

//pre ($_REQUEST);

if ($submitted == 1) {
    $_SESSION["period_type"] = get_formvar("period_type");
    $_SESSION["validity_start_date_day"] = get_formvar("validity_start_date_day");
    $_SESSION["validity_start_date_month"] = get_formvar("validity_start_date_month");
    $_SESSION["validity_start_date_year"] = get_formvar("validity_start_date_year");
    $_SESSION["validity_end_date_day"] = get_formvar("validity_end_date_day");
    $_SESSION["validity_end_date_month"] = get_formvar("validity_end_date_month");
    $_SESSION["validity_end_date_year"] = get_formvar("validity_end_date_year");
    $_SESSION["year_count"] = get_formvar("year_count");
    $_SESSION["introductory_period_option"] = get_formvar("introductory_period_option");
}

$quota_order_number = new quota_order_number;
$quota_order_number->duties_same_for_all_commodities = get_formvar("duties_same_for_all_commodities");
$quota_order_number->commodity_codes = get_formvar("commodity_codes");
$quota_order_number->get_commodity_code_array();

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
        new data_entry_form($control_content, $quota_order_number, $left_nav = "", "create_edit8.html");
        ?>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>

