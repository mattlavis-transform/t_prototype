<!DOCTYPE html>
<html lang="en" class="govuk-template">

<head>
    <meta charset="utf-8" />
    <title>GOV.UK - The best place to find government services and information</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0b0c0c" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" sizes="16x16 32x32 48x48" href="/assets/images/favicon.ico" type="image/x-icon" />
    <link rel="mask-icon" href="/assets/images/govuk-mask-icon.svg" color="#0b0c0c">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/govuk-apple-touch-icon-180x180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/assets/images/govuk-apple-touch-icon-167x167.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/images/govuk-apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" href="/assets/images/govuk-apple-touch-icon.png">

    <!--[if !IE 8]><!-->
    <link href="/css/govuk-frontend-3.4.0.min.css" rel="stylesheet" />
    <link href="/css/application.css" rel="stylesheet" />
    <!--<![endif]-->
    <script src="/js/jquery-3.4.1.min.js"></script>

    <!--[if IE 8]>
    <link href="/css/govuk-frontend-ie8-3.4.0.min.css" rel="stylesheet" />
  <![endif]-->

    <!--[if lt IE 9]>
    <script src="/html5-shiv/html5shiv.js"></script>
  <![endif]-->

    <meta property="og:image" content="/assets/images/govuk-opengraph-image.png">
</head>

<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("../includes/phase_banner.php");
        ?>

        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/geographical_areas">Footnotes</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">New footnote</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-two-thirds">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Create a new footnote</h1>
                    <!-- End main title //-->

                    <form action="">

                        <!-- Start select //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="sort">What type of footnote are you creating?</label>
                            <select class="govuk-select" id="sort" name="sort">
                                <option value="01">01 - UK tax type, to distinguish which applies by tax type when several Excise measures on commodity</option>
                                <option value="02">02 - UK complex calculation of excise duty (small brewery beer and cigarettes rate calculation)</option>
                                <option value="03">03 - UK VAT rate, to distinguish which applies when multiple rates on same commodity</option>
                                <option value="04">04 - UK footnotes_oplog on prohibitions and restrictions</option>
                                <option value="CA">CA - Additional nomenclature - CADD</option>
                                <option value="CD">CD - Conditions</option>
                                <option value="CG">CG - Cultural goods</option>
                                <option value="CO">CO - Conditions</option>
                                <option value="DU">DU - Dual use goods</option>
                                <option value="EU">EU - End use</option>
                                <option value="IS">IS - Invasive alien species</option>
                                <option value="MG">MG - Military goods and technologies</option>
                                <option value="MH">MH - Meursing table</option>
                                <option value="MX">MX - Export refund measure</option>
                                <option value="NC">NC - Combined Nomenclature</option>
                                <option value="NM">NM - CN measure</option>
                                <option value="NX">NX - Export Refund Nomenclature</option>
                                <option value="OZ">OZ - Ozone-depleting substances</option>
                                <option value="PB">PB - Publication</option>
                                <option value="PN">PN - See annex</option>
                                <option value="TM">TM - Taric Measure</option>
                                <option value="TN">TN - Taric Nomenclature</option>
                                <option value="TP">TP - Dynamic footnote</option>
                                <option value="TR">TR - Torture and repression</option>
                                <option value="WR">WR - Wine reference</option>

                            </select>
                        </div>
                        <!-- End select //-->

                        <!-- Start character count //-->
                        <div class="govuk-character-count" data-module="govuk-character-count" data-maxlength="3000">
                            <div class="govuk-form-group">
                                <label class="govuk-label--m" for="with-hint">What is the footnote description?</label>
                                <span id="with-hint-hint" class="govuk-hint">
                                    Description cannot be left blank and must comprise at least one word.

                                    Reminder: for data-protection reasons, do not include anyone's personal information here, such as names, email addresses or phone numbers.
                                </span>
                                <textarea class="govuk-textarea govuk-js-character-count" id="with-hint" maxlength="3000" name="with-hint" rows="5" aria-describedby="with-hint-info with-hint-hint"></textarea>
                            </div>

                            <span id="with-hint-info" class="govuk-hint govuk-character-count__message" aria-live="polite">
                                You can enter up to 200 characters
                            </span>
                        </div>
                        <!-- End character count //-->



                        <!-- Start date input //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" role="group" aria-describedby="validity_start_date-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">When is the footnote's start date?</h1>
                                </legend>
                                <div class="govuk-date-input" id="validity_start_date">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_start_date-day">
                                                Day
                                            </label>
                                            <input required class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="validity_start_date-day" name="validity_start_date-day" type="text" pattern="[0-9]{1,2}">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_start_date-month">
                                                Month
                                            </label>
                                            <input required class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="validity_start_date-month" name="validity_start_date-month" type="text" pattern="[0-9]{1,2}">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_start_date-year">
                                                Year
                                            </label>
                                            <input required class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_date-year" name="validity_start_date-year" type="text" pattern="[0-9]{2,4}">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- End date input //-->



                        <!-- Start button //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" role="group" aria-describedby="validity_start_date-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">Finish</h1>
                                </legend>
                                <button class="govuk-button" data-module="govuk-button">Submit for approval</button>
                                <a href="/" class="textual_button">Cancel</a>
                            </fieldset>
                        </div>
                        <!-- End button //-->
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