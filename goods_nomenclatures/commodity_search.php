<!-- Start inset text //-->
<div class="govuk-inset-text">
    <p>You are currently viewing the goods classification for December 30th, 2019</p>
    <!-- Start details component //-->
    <details class="govuk-details" data-module="govuk-details">
        <summary class="govuk-details__summary">
            <span class="govuk-details__summary-text">
                Change date
            </span>
        </summary>
        <div class="govuk-details__text govuk-details__text-noborder">
            <div class="govuk-date-input">
                <div class="govuk-date-input__item">
                    <div class="govuk-form-group">
                        <label class="govuk-label govuk-date-input__label" for="commodity_date_day">Day</label>
                        <input value="" required class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="commodity_date_day" name="commodity_date_day" type="text" pattern="[0-9]{1,2}">
                    </div>
                </div>
                <div class="govuk-date-input__item">
                    <div class="govuk-form-group">
                        <label class="govuk-label govuk-date-input__label" for="commodity_date_month">Month</label>
                        <input value="" required class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="commodity_date_month" name="commodity_date_month" type="text" pattern="[0-9]{1,2}">
                    </div>
                </div>
                <div class="govuk-date-input__item">
                    <div class="govuk-form-group">
                        <label class="govuk-label govuk-date-input__label" for="commodity_date_year">Year</label>
                        <input value="" required class=" govuk-input govuk-date-input__input govuk-input--width-4" id="commodity_date_year" name="commodity_date_year" type="text" pattern="[0-9]{2,4}">
                    </div>
                </div>
            </div>
            <!-- Start button //-->
            <button style="margin-top:1em" class="govuk-button" data-module="govuk-button">Set date</button>
            <!-- End button //-->

        </div>
    </details>
    <!-- End details component //-->
</div>
<!-- End inset text //-->
<form action="search.html" method="post">
    <!-- Start text input //-->
    <div class="govuk-form-group" style="margin-bottom:0px">
        <label class="govuk-label b" for="filter_goods_nomenclatures_freetext">Enter commodity code you want to work with</label>
        <input class="govuk-input" id="filter_goods_nomenclatures_freetext" name="filter_goods_nomenclatures_freetext" type="text" maxlength="10" size="10" style="width:25%">
        <button class="govuk-button" style="padding:9px 8px" data-module="govuk-button">Search</button>
    </div>
    <!-- End text input //-->
</form>