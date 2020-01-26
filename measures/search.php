<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures");
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
                    <form method="post" action="/measures/index.html">
                        <?php
                        new title_control("oc", "", "", "Find and edit measures");
                        new inset_control("Enter search criteria to find measures. Alternatively <a href='create_edit.html'>create new measures</a>.")

                        ?>

                        <!-- Start measure SIDs row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="measure_sid">
                                        Measure SID
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <label class="govuk-label" for="measure_sid">
                                        is one of
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input class="govuk-input govuk-!-width-one-half" id="measure_sid" name="measure_sid" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End measure SIDs row //-->


                        <!-- Start commodity code row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="goods_nomenclature_item_id">
                                        Commodity code
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <select class="govuk-select" id="goods_nomenclature_item_id_operator" name="goods_nomenclature_item_id_operator">
                                        <!--<option value="">-- Unspecified --</option>//-->
                                        <option value="starts_with">starts with</option>
                                        <option value="is_one_of">is one of</option>
                                    </select>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input class="govuk-input" id="goods_nomenclature_item_id" name="goods_nomenclature_item_id" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End commodity code row //-->


                        <!-- Start additional code row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="additional_code">
                                        Additional code
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <select class="govuk-select" id="additional_code_operator" name="additional_code_operator">
                                        <!--<option value="">-- Unspecified --</option>//-->
                                        <option value="starts_with">starts with</option>
                                        <option value="is_one_of">is one of</option>
                                    </select>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input class="govuk-input" id="additional_code" name="additional_code" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End additional code row //-->


                        <!-- Start regulation row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="measure_generating_regulation_id">
                                        Regulation
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <select class="govuk-select" id="measure_generating_regulation_id_operator" name="measure_generating_regulation_id_operator">
                                        <option value="is">is</option>
                                        <option value="is not">is not</option>
                                    </select>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input class="govuk-input" id="measure_generating_regulation_id" name="measure_generating_regulation_id" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End regulation row //-->


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
                                    <input size="100" maxlength="100" class="govuk-input tt-input" id="measure_type_id" type="text" />
                                </div>
                            </div>
                        </div>
                        <!-- End measure type row //-->



                        <!-- Start origin row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="event-name">
                                        Origin
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <label class="govuk-label" for="event-name">
                                        is
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input size="100" maxlength="100" class="govuk-input tt-input" id="geographical_area_id" name="geographical_area_id" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End origin row //-->

                        <!-- Start footnotes row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="event-name">
                                        Footnote
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <label class="govuk-label" for="event-name">
                                        is
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                <input size="100" maxlength="100" class="govuk-input tt-input" id="footnote" name="footnote" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- End footnotes row //-->


                        <!-- Start start date row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="event-name">
                                        Start date
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <select class="govuk-select" id="sort" name="sort">
                                        <option value="is">is</option>
                                        <option value="is after">is after</option>
                                        <option value="is before">is before</option>
                                    </select>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input size="2" maxlength="2" class="govuk-input govuk-date-input__input govuk-input--width-2" id="passport-issued-day" name="passport-issued-day" type="number" pattern="[0-9]*">
                                    <input size="2" maxlength="2" class="govuk-input govuk-date-input__input govuk-input--width-2" id="passport-issued-day" name="passport-issued-day" type="number" pattern="[0-9]*">
                                    <input size="4" maxlength="4" class="govuk-input govuk-date-input__input govuk-input--width-4" id="passport-issued-year" name="passport-issued-year" type="number" pattern="[0-9]*">
                                </div>
                            </div>
                        </div>
                        <!-- End start date row //-->

                        <!-- Start start date row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="event-name">
                                        End date
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <select class="govuk-select" id="sort" name="sort">
                                        <option value="is">is</option>
                                        <option value="is after">is after</option>
                                        <option value="is before">is before</option>
                                        <option value="is unspecified">is unspecified</option>
                                        <option value="is after or unspecified">is after or unspecified</option>
                                    </select>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input size="2" maxlength="2" class="govuk-input govuk-date-input__input govuk-input--width-2" id="passport-issued-day" name="passport-issued-day" type="number" pattern="[0-9]*">
                                    <input size="2" maxlength="2" class="govuk-input govuk-date-input__input govuk-input--width-2" id="passport-issued-day" name="passport-issued-day" type="number" pattern="[0-9]*">
                                    <input size="4" maxlength="4" class="govuk-input govuk-date-input__input govuk-input--width-4" id="passport-issued-year" name="passport-issued-year" type="number" pattern="[0-9]*">
                                </div>
                            </div>
                        </div>
                        <!-- End end date row //-->

                        <!-- Start creation date row //-->
                        <div class="complex_search_row">
                            <div class="govuk-grid-column-two-thirds nopad">
                                <div class="complex_search_form complex_search_form_column1">
                                    <label class="govuk-label" for="event-name">
                                        Creation date
                                    </label>
                                </div>
                                <div class="complex_search_form complex_search_form_column1a">
                                    <select class="govuk-select" id="sort" name="sort">
                                        <option value="is">is</option>
                                        <option value="is after">is after</option>
                                        <option value="is before">is before</option>
                                    </select>
                                </div>
                                <div class="complex_search_form complex_search_form_column2">
                                    <input size="2" maxlength="2" class="govuk-input govuk-date-input__input govuk-input--width-2" id="passport-issued-day" name="passport-issued-day" type="number" pattern="[0-9]*">
                                    <input size="2" maxlength="2" class="govuk-input govuk-date-input__input govuk-input--width-2" id="passport-issued-day" name="passport-issued-day" type="number" pattern="[0-9]*">
                                    <input size="4" maxlength="4" class="govuk-input govuk-date-input__input govuk-input--width-4" id="passport-issued-year" name="passport-issued-year" type="number" pattern="[0-9]*">
                                </div>
                            </div>
                        </div>
                        <!-- End creation    date row //-->

                        <div class="govuk-!-margin-top-4">
                            <?php
                            new button_control("Search", "search", "primary", true, "");
                            new button_control("Clear form", "clear_button", "text", true, "x");
                            new button_control("Cancel", "cancel", "text", false, "/");
                            ?>
                        </div>
                        <input type="hidden" name="submitted" id="submitted" value="1" />
                    </form>

                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>