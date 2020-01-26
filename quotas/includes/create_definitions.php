<?php
//pre($_SESSION);
global $quota_order_number;
$quota_order_number->measure_generating_regulation_id = $_SESSION["measure_generating_regulation_id"];
$quota_order_number->quota_mechanism = $_SESSION["quota_mechanism"];
$quota_order_number->quota_category = $_SESSION["quota_category"];
$quota_order_number->quota_order_number_id = $_SESSION["quota_order_number_id"];
$quota_order_number->description = $_SESSION["description"];
$quota_order_number->geographical_area_id_countries = $_SESSION["geographical_area_id_countries"];
$quota_order_number->quota_scope = $_SESSION["quota_scope"];
$quota_order_number->quota_staging = $_SESSION["quota_staging"];
$quota_order_number->origin_quota = $_SESSION["origin_quota"];
$quota_order_number->commodity_codes = $_SESSION["commodity_codes"];
$quota_order_number->duties_same_for_all_commodities = $_SESSION["duties_same_for_all_commodities"];
//$quota_order_number->event_names = $_SESSION["event-names"];
$quota_order_number->measurement_unit_code = $_SESSION["measurement_unit_code"];
$quota_order_number->measurement_unit_qualifier_code = $_SESSION["measurement_unit_qualifier_code"];
$quota_order_number->maximum_precision = $_SESSION["maximum_precision"];
$quota_order_number->critical_threshold = $_SESSION["critical_threshold"];
$quota_order_number->period_type = $_SESSION["period_type"];
$quota_order_number->validity_start_date_day = $_SESSION["validity_start_date_day"];
$quota_order_number->validity_start_date_month = $_SESSION["validity_start_date_month"];
$quota_order_number->validity_start_date_year = $_SESSION["validity_start_date_year"];

$quota_order_number->validity_end_date_day = $_SESSION["validity_end_date_day"];
$quota_order_number->validity_end_date_month = $_SESSION["validity_end_date_month"];
$quota_order_number->validity_end_date_year = $_SESSION["validity_end_date_year"];

$quota_order_number->year_count = $_SESSION["year_count"];
$quota_order_number->introductory_period_option = $_SESSION["introductory_period_option"];


$quota_order_number->regular_validity_start_date = to_date_string($quota_order_number->validity_start_date_day, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_year);
$quota_order_number->start_part_of_year_string = to_part_of_year_string($quota_order_number->validity_start_date_day, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_year);
//$quota_order_number->end_part_of_year_string = to_part_of_year_string($quota_order_number->validity_end_date_day, $quota_order_number->validity_end_date_month, $quota_order_number->validity_end_date_year);


?>


<!-- Start error //-->
<!--
<div class="govuk-form-group govuk-form-group--error">
    <fieldset class="govuk-fieldset" role="group" aria-describedby="passport-issued-hint passport-issued-error">
        <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
            <h1 class="govuk-fieldset__heading">
                When was your passport issued?
            </h1>
        </legend>
        <span id="passport-issued-hint" class="govuk-hint">
            For example, 12 11 2007
        </span>
        <span id="passport-issued-error" class="govuk-error-message">
            <span class="govuk-visually-hidden">Error:</span> The date your passport was issued must be in the past
        </span>
        <div class="govuk-date-input" id="passport-issued">
            <div class="govuk-date-input__item">
                <div class="govuk-form-group">
                    <label class="govuk-label govuk-date-input__label" for="passport-issued-day">
                        Day
                    </label>
                    <input class="govuk-input govuk-date-input__input govuk-input--width-2 govuk-input--error" id="passport-issued-day" name="passport-issued-day" type="number" value="6" pattern="[0-9]*">
                </div>
            </div>
            <div class="govuk-date-input__item">
                <div class="govuk-form-group">
                    <label class="govuk-label govuk-date-input__label" for="passport-issued-month">
                        Month
                    </label>
                    <input class="govuk-input govuk-date-input__input govuk-input--width-2 govuk-input--error" id="passport-issued-month" name="passport-issued-month" type="number" value="3" pattern="[0-9]*">
                </div>
            </div>
            <div class="govuk-date-input__item">
                <div class="govuk-form-group">
                    <label class="govuk-label govuk-date-input__label" for="passport-issued-year">
                        Year
                    </label>
                    <input class="govuk-input govuk-date-input__input govuk-input--width-4 govuk-input--error" id="passport-issued-year" name="passport-issued-year" type="number" value="2076" pattern="[0-9]*">
                </div>
            </div>
        </div>
    </fieldset>
</div>
//-->
<!-- End error //-->

<div class="govuk-grid-row govuk-form-group--error">
    <div class="govuk-grid-column-full">
        <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
            <h1 class="govuk-fieldset__heading">
                When was your passport issued?
            </h1>
        </legend>

        <!-- Start table //-->
        <span id="passport-issued-error" class="govuk-error-message">
            <span class="govuk-visually-hidden">Error:</span> Please ensure that all data entry fields are populated
        </span>

        <table class="govuk-table govuk-table--m sticky">
            <thead class="govuk-table__head">
                <tr class="govuk-table__row">
                    <th scope="col" class="govuk-table__header">Period</th>
                    <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                    <th scope="col" class="govuk-table__header">End date</th>
                    <th scope="col" class="govuk-table__header">Initial volume</th>
                    <th scope="col" class="govuk-table__header">Unit</th>
                    <th scope="col" class="govuk-table__header">Critical?</th>
                    <th scope="col" class="govuk-table__header">&nbsp;</th>
                </tr>
            </thead>
            <tbody class="govuk-table__body">
                <?php
                if ($quota_order_number->introductory_period_option > 0) {
                ?>
                    <tr class="govuk-table__row">
                        <td colspan="6" class="govuk-table__cell vertical_align_middle govuk-table__interstitial">Introductory periods</td>
                    </tr>
                    <?php
                    for ($i = 1; $i <= $quota_order_number->introductory_period_option; $i++) {
                    ?>
                        <tr class="govuk-table__row">
                            <td class="govuk-table__cell vertical_align_middle">Introductory period <?= $i ?></td>
                            <td class="govuk-table__cell vertical_align_middle nw">
                                <input class="govuk-input govuk-input--width-2 govuk-input--error" id="validity_start_date_day_intro_period_<?= $i ?>" name="validity_start_date_day_intro_period_<?= $i ?>" type="text">
                                <input class="govuk-input govuk-input--width-2" id="validity_start_date_month_intro_period_<?= $i ?>" name="validity_start_date_month_intro_period_<?= $i ?>" type="text">
                                <input class="govuk-input govuk-input--width-4" id="validity_start_date_year_intro_period_<?= $i ?>" name="validity_start_date_year_intro_period_<?= $i ?>" type="text">
                            </td>
                            <td class="govuk-table__cell vertical_align_middle nw">
                                <input class="govuk-input govuk-input--width-2" id="validity_end_date_day_intro_period_<?= $i ?>" name="validity_end_date_day_intro_period_<?= $i ?>" type="text">
                                <input class="govuk-input govuk-input--width-2" id="validity_end_date_month_intro_period_<?= $i ?>" name="validity_end_date_month_intro_period_<?= $i ?>" type="text">
                                <input class="govuk-input govuk-input--width-4" id="validity_end_date_year_intro_period_<?= $i ?>" name="validity_end_date_year_intro_period_<?= $i ?>" type="text">
                            </td>
                            <td class="govuk-table__cell vertical_align_middle nw">
                                <input class="govuk-input govuk-input--width-5" id="volume_intro_period_<?= $i ?>" name="volume_intro_period_<?= $i ?>" type="text">
                            </td>
                            <td class="govuk-table__cell vertical_align_middle nw">KGM</td>
                            <td class="govuk-table__cell vertical_align_middle">
                                <div class="govuk-radios govuk-radios--inline">
                                    <div class="govuk-radios__item" style="margin:0px">
                                        <input class="govuk-radios__input" id="critical_intro_period_<?= $i ?>_yes" name="critical_intro_period_<?= $i ?>" type="radio" value="yes">
                                        <label class="govuk-label govuk-radios__label" for="critical_intro_period_<?= $i ?>_yes">
                                            Yes
                                        </label>
                                    </div>

                                    <div class="govuk-radios__item" style="margin:0px">
                                        <input class="govuk-radios__input" id="critical_intro_period_<?= $i ?>_yes" name="critical_intro_period_<?= $i ?>" type="radio" value="yes">
                                        <label class="govuk-label govuk-radios__label" for="critical_intro_period_<?= $i ?>_yes">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>


                <?php
                }
                ?>
                <?php
                if ($quota_order_number->period_type == "Custom") {
                    $header = "Regularly recurring annual periods, running from ";
                    $date1 = new DateTime();
                    $date2 = new DateTime();
                    $date1->setDate($quota_order_number->validity_start_date_year, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_day);
                    $date2->setDate($quota_order_number->validity_end_date_year, $quota_order_number->validity_end_date_month, $quota_order_number->validity_end_date_day);
                    $header .= $date1->format('d F') . " to " . $date2->format('d F');
                    $periods_per_year = 1;
                } elseif ($quota_order_number->period_type == "Annual") {
                    $header = "Regularly recurring annual periods, running from ";
                    $header .= $quota_order_number->start_part_of_year_string;
                    $periods_per_year = 1;
                    $period_length = 12;
                } elseif ($quota_order_number->period_type == "Bi-annual") {
                    $header = "Regularly recurring bi-annual periods, running from ";

                    $date1 = new DateTime();
                    $date2 = new DateTime();

                    $date1->setDate($quota_order_number->validity_start_date_year, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_day);
                    $date2->setDate($quota_order_number->validity_start_date_year, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_day);
                    date_add($date2, date_interval_create_from_date_string("6 months"));

                    $header .= $date1->format('d F') . " and " . $date2->format('d F');
                    $periods_per_year = 2;
                    $period_length = 6;
                } elseif ($quota_order_number->period_type == "Quarterly") {
                    $header = "Regularly recurring quarterly periods, running from ";

                    $date1 = new DateTime();
                    $date2 = new DateTime();
                    $date3 = new DateTime();
                    $date4 = new DateTime();

                    $date1->setDate($quota_order_number->validity_start_date_year, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_day);
                    $date2->setDate($quota_order_number->validity_start_date_year, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_day);
                    $date3->setDate($quota_order_number->validity_start_date_year, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_day);
                    $date4->setDate($quota_order_number->validity_start_date_year, $quota_order_number->validity_start_date_month, $quota_order_number->validity_start_date_day);
                    date_add($date2, date_interval_create_from_date_string("3 months"));
                    date_add($date3, date_interval_create_from_date_string("6 months"));
                    date_add($date4, date_interval_create_from_date_string("9 months"));

                    $header .= $date1->format('d F') . ", " . $date2->format('d F') . ", " . $date3->format('d F') . " and " . $date4->format('d F');
                    $periods_per_year = 4;
                    $period_length = 3;
                }
                ?>
                <tr class="govuk-table__row">
                    <td colspan="6" class="govuk-table__cell vertical_align_middle govuk-table__interstitial"><?= $header ?><a class="copy_definitions govuk-link" id="copy_definitions" href="#">Replicate year 1 definitions</a></td>
                </tr>
                <?php
                $my_last_year = 0;
                $max = ($quota_order_number->year_count * $periods_per_year);
                for ($i = 1; $i <= $max; $i++) {
                    $my_year = ceil($i / $periods_per_year);

                    $start_date = new DateTime();
                    $end_date = new DateTime();
                    $end_year_date = new DateTime();

                    $day = $quota_order_number->validity_start_date_day;
                    $month = $quota_order_number->validity_start_date_month;
                    $year = $quota_order_number->validity_start_date_year;

                    $start_date->setDate($year, $month, $day);
                    $end_date->setDate($year, $month, $day);
                    $end_year_date->setDate($year, $month, $day);

                    $start_format = (($i - 1) * $period_length) . " months";
                    $start_date = date_add($start_date, date_interval_create_from_date_string($start_format));
                    $period_start_date_string = $start_date->format('j M Y');
                    $period_start_date_string_hidden = $start_date->format('Y-m-d');

                    $end_format = (($i) * $period_length) . " months - 1 day";
                    $end_date = date_add($end_date, date_interval_create_from_date_string($end_format));
                    $period_end_date_string = $end_date->format('j M Y');
                    $period_end_date_string_hidden = $end_date->format('Y-m-d');

                    $end_year_format = ($my_year * 12) . " months - 1 day";
                    $end_year_date = date_add($end_year_date, date_interval_create_from_date_string($end_year_format));
                    $year_end_date_string = $end_year_date->format('j M Y');

                    //pre ($period_length);
                    //pre ($end_year_format);

                    if (($periods_per_year > 1) && ($my_year != $my_last_year)) {
                        $year_start_date_string = $period_start_date_string;
                ?>
                        <tr class="govuk-table__row">
                            <td colspan="6" class="govuk-table__cell vertical_align_middle govuk-table__interstitial2">Year <?= $my_year ?> running from <?= $year_start_date_string ?> to <?= $year_end_date_string ?></td>
                        </tr>

                    <?php
                    }
                    $my_intra_year_period = ($i % $periods_per_year);
                    if ($my_intra_year_period == 0) {
                        $my_intra_year_period = $periods_per_year;
                    }

                    if ($periods_per_year == 1) {
                        $period_string = "Year " . $i;
                    } else {
                        $period_string = "Year " . $my_year . " period " . $my_intra_year_period;
                    }

                    ?>
                    <tr class="govuk-table__row">
                        <td class="govuk-table__cell vertical_align_middle"><?= $period_string ?></td>
                        <td class="govuk-table__cell vertical_align_middle">
                            <input type="hidden" name="validity_start_date_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>" id="validity_start_date_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>" value="<?= $period_start_date_string_hidden ?>" />
                            <?= $period_start_date_string ?>
                        </td>
                        <td class="govuk-table__cell vertical_align_middle">
                            <input type="hidden" name="validity_end_date_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>" id="validity_end_date_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>" value="<?= $period_end_date_string_hidden ?>" />
                            <?= $period_end_date_string ?>
                        </td>
                        <td class="govuk-table__cell vertical_align_middle">
                            <input pattern="[0-9]{1,10}" class="govuk-input govuk-input--width-5 year_<?= $my_year ?> period_<?= $my_intra_year_period ?>" id="volume_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>" name="volume_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>" type="text">
                        </td>
                        <td class="govuk-table__cell vertical_align_middle nw">KGM</td>
                        <td class="govuk-table__cell vertical_align_middle">
                            <div class="govuk-radios govuk-radios--inline">
                                <div class="govuk-radios__item" style="margin:0px">
                                    <input class="govuk-radios__input" id="critical_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>_yes" name="critical_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>" type="radio" value="yes">
                                    <label class="govuk-label govuk-radios__label" for="critical_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>_yes">
                                        Yes
                                    </label>
                                </div>

                                <div class="govuk-radios__item" style="margin:0px">
                                    <input class="govuk-radios__input" id="critical_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>_no" name="critical_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>" type="radio" value="no">
                                    <label class="govuk-label govuk-radios__label" for="critical_year_<?= $my_year ?>_period_<?= $my_intra_year_period ?>_no">
                                        No
                                    </label>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                    $my_last_year = $my_year;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>