<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("quota_order_numbers");
/*
$application->get_filter_options();
$application->get_quota_measures();
$application->get_quota_mechanisms();
$application->get_quota_measure_types();
$application->get_start_dates();
$application->get_quota_categories();
*/
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

        /*
        $filter_content = array();
        array_push($filter_content, $application->quota_mechanisms);
        array_push($filter_content, $application->quota_measure_types);
        array_push($filter_content, $application->quota_categories);
        array_push($filter_content, $application->start_dates);
        $application->search_form = new complex_search_form($application->quota_measures, $filter_content);
        */
        ?>

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <?php
                    new title_control("oc", "", "", "Find and edit quotas");
                    new inset_control("Enter search criteria to find quotas. Alternatively <a class='govuk-link' href='create_edit.html'>create a new quota</a>. Please separate multiple terms with commas, semi-colons or spaces.")

                    ?>

                    <!-- Start order number row //-->
                    <div class="complex_search_row">
                        <div class="govuk-grid-column-two-thirds nopad">
                            <div class="complex_search_form complex_search_form_column1">
                                <label class="govuk-label" for="event-name">
                                    Order number
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column1a">
                                <select class="govuk-select" id="sort" name="sort">
                                    <!--<option value="">-- Unspecified --</option>//-->
                                    <option value="starts_with">starts with</option>
                                    <option value="is_one_of">is one of</option>
                                </select>
                            </div>
                            <div class="complex_search_form complex_search_form_column2">
                                <input class="govuk-input" id="event-name" name="event-name" type="text">
                            </div>
                        </div>
                    </div>
                    <!-- End order number row //-->



                    <!-- Start mechanism row //-->
                    <div class="complex_search_row">
                        <div class="govuk-grid-column-two-thirds nopad">
                            <div class="complex_search_form complex_search_form_column1">
                                <label class="govuk-label" for="event-name">
                                    Administration mechanism
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column1a">
                                <label class="govuk-label" for="event-name">

                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column2">
                                <div class="govuk-radios govuk-radios--inline">

                                    <div class="govuk-checkboxes__item">
                                        <input class="govuk-checkboxes__input" id="waste" name="waste" type="checkbox" value="carcasses">
                                        <label class="govuk-label govuk-checkboxes__label" for="waste">
                                            First come, first served quotas
                                        </label>
                                    </div>
                                    <div class="govuk-checkboxes__item">
                                        <input class="govuk-checkboxes__input" id="waste-2" name="waste" type="checkbox" value="mines">
                                        <label class="govuk-label govuk-checkboxes__label" for="waste-2">
                                            Licensed quotas
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End mechanism row //-->



                    <!-- Start quota category row //-->
                    <div class="complex_search_row">
                        <div class="govuk-grid-column-two-thirds nopad">
                            <div class="complex_search_form complex_search_form_column1">
                                <label class="govuk-label" for="event-name">
                                    Quota category
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column1a">
                                <label class="govuk-label" for="event-name">

                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column2">

                                <div class="govuk-checkboxes__item">
                                    <input class="govuk-checkboxes__input" id="waste" name="waste" type="checkbox" value="carcasses">
                                    <label class="govuk-label govuk-checkboxes__label" for="waste">
                                        WTO quota
                                    </label>
                                </div>
                                <div class="govuk-checkboxes__item">
                                    <input class="govuk-checkboxes__input" id="waste-2" name="waste" type="checkbox" value="mines">
                                    <label class="govuk-label govuk-checkboxes__label" for="waste-2">
                                        ATQ (Autonomous tariff quota)
                                    </label>
                                </div>
                                <div class="govuk-checkboxes__item">
                                    <input class="govuk-checkboxes__input" id="waste-2" name="waste" type="checkbox" value="mines">
                                    <label class="govuk-label govuk-checkboxes__label" for="waste-2">
                                        Preferential tariff rate quota
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End quota categpry row //-->




                    <!-- Start description row //-->
                    <div class="complex_search_row">
                        <div class="govuk-grid-column-two-thirds nopad">
                            <div class="complex_search_form complex_search_form_column1">
                                <label class="govuk-label" for="event-name">
                                    Description
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column1a">
                                <label class="govuk-label" for="event-name">
                                    contains
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column2">
                                <input class="govuk-input" id="event-name" name="event-name" type="text">
                            </div>
                        </div>
                    </div>
                    <!-- End description row //-->


                    <!-- Start origin row //-->
                    <div class="complex_search_row">
                        <div class="govuk-grid-column-two-thirds nopad">
                            <div class="complex_search_form complex_search_form_column1">
                                <label class="govuk-label" for="event-name">
                                    Origin(s)
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column1a">
                                <label class="govuk-label" for="event-name">
                                    is one of
                                </label>
                            </div>

                            <div class="complex_search_form complex_search_form_column2">
                                <input class="govuk-input" id="event-name" name="event-name" type="text">
                            </div>
                        </div>
                    </div>
                    <!-- End origin row //-->


                    <!-- Start commodity code row //-->
                    <div class="complex_search_row">
                        <div class="govuk-grid-column-two-thirds nopad">
                            <div class="complex_search_form complex_search_form_column1">
                                <label class="govuk-label" for="event-name">
                                    Commodity code
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column1a">
                                <select class="govuk-select" id="sort" name="sort">
                                    <!--<option value="">-- Unspecified --</option>//-->
                                    <option value="starts_with">starts with</option>
                                    <option value="is_one_of">is one of</option>
                                </select>
                            </div>
                            <div class="complex_search_form complex_search_form_column2">
                                <input class="govuk-input" id="event-name" name="event-name" type="text">
                            </div>
                        </div>
                    </div>
                    <!-- End commodity code row //-->

                    <!-- Start measure type row //-->
                    <div class="complex_search_row">
                        <div class="govuk-grid-column-two-thirds nopad">
                            <div class="complex_search_form complex_search_form_column1">
                                <label class="govuk-label" for="event-name">
                                    Measure type
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column1a">
                                <label class="govuk-label" for="event-name">
                                    is
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column2">
                                <select class="govuk-select" id="sort" name="sort">
                                    <option value="0">-- unspecified --</option>
                                    <option value="122">122 Non-preferential tariff quota</option>
                                    <option value="123">123 Non-preferential tariff quota under authorised use</option>
                                    <option value="143">143 Preferential tariff quota</option>
                                    <option value="146">146 Preferential tariff quota under authorised use </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- End measure type row //-->


                    <!-- Start origin quota row //-->
                    <div class="complex_search_row">
                        <div class="govuk-grid-column-two-thirds nopad">
                            <div class="complex_search_form complex_search_form_column1">
                                <label class="govuk-label" for="event-name">
                                    Origin quota?
                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column1a">
                                <label class="govuk-label" for="event-name">

                                </label>
                            </div>
                            <div class="complex_search_form complex_search_form_column2">

                                <div class="govuk-checkboxes__item">
                                    <input class="govuk-checkboxes__input" id="waste" name="waste" type="checkbox" value="carcasses">
                                    <label class="govuk-label govuk-checkboxes__label" for="waste">
                                        Yes
                                    </label>
                                </div>
                                <div class="govuk-checkboxes__item">
                                    <input class="govuk-checkboxes__input" id="waste-2" name="waste" type="checkbox" value="mines">
                                    <label class="govuk-label govuk-checkboxes__label" for="waste-2">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End origin quota row //-->


                    <div class="govuk-!-margin-top-2">
                        <?php
                        new button_control("Search", "search", "primary", true, "");
                        new button_control("Clear form", "clear_button", "text", true, "x");
                        new button_control("Cancel", "cancel", "text", false, "/");
                        ?>
                    </div>


                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>