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
                    <a class="govuk-breadcrumbs__link" href="/geographical_areas">Geographical areas</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">New geographical area</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-two-thirds">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Create a new geographical area</h1>
                    <!-- End main title //-->

                    <form action="">

                        <!-- Start radios - vertical with hints //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" aria-describedby="sign-in-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">What type of geographical area are you creating?</h1>
                                </legend>
                                <span id="sign-in-hint" class="govuk-hint">
                                    You’ll need an account to prove your identity and complete your Self Assessment.
                                </span>
                                <div class="govuk-radios">
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="sign-in" name="sign-in" type="radio" value="government-gateway" aria-describedby="sign-in-item-hint">
                                        <label class="govuk-label govuk-radios__label govuk-label--s" for="sign-in">
                                            A country
                                        </label>
                                        <span id="sign-in-item-hint" class="govuk-hint govuk-radios__hint">
                                            This will have two-letter ISO code. You can add countries to geographical area groups, but a country cannot itself be a group.
                                        </span>
                                    </div>
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="sign-in-2" name="sign-in" type="radio" value="govuk-verify" aria-describedby="sign-in-2-item-hint">
                                        <label class="govuk-label govuk-radios__label govuk-label--s" for="sign-in-2">
                                            A region
                                        </label>
                                        <span id="sign-in-2-item-hint" class="govuk-hint govuk-radios__hint">
                                            Use this only in exceptional cases, to represent a geographical entity that is not a country. Functionally, a region is the same as a country.
                                        </span>
                                    </div>
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="sign-in-2" name="sign-in" type="radio" value="govuk-verify" aria-describedby="sign-in-2-item-hint">
                                        <label class="govuk-label govuk-radios__label govuk-label--s" for="sign-in-2">
                                            A group
                                        </label>
                                        <span id="sign-in-2-item-hint" class="govuk-hint govuk-radios__hint">
                                            Create a group when you want to reference multiple countries and/or regions together. A group must have four-character (letters and/or numbers) code.
                                        </span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- End radios - vertical with hints //-->


                        <!-- Start geographical area ID //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="event-name">
                                What code will identify this area?
                            </label>
                            <span id="sign-in-2-item-hint" class="govuk-hint">
                                This must be unique and either a two-letter ISO code (for a country or region) or a string of four
                                letters and/or numbers (for a group).</span>
                            <input class="govuk-input govuk-input--width-10" size="4" maxlength="4" id="event-name" name="event-name" type="text">
                        </div>
                        <!-- End geographical area ID //-->

                        <!-- Start geographical area ID //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="event-name">
                                What is the area's description?
                            </label>
                            <span id="sign-in-2-item-hint" class="govuk-hint">
                                This will be the name of the country or region, but for groups you should use something descriptive that will allow others to easily identify it.</span>
                            <input class="govuk-input" id="event-name" name="event-name" type="text">
                        </div>
                        <!-- End geographical area ID //-->

                        <!-- Start date input //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" role="group" aria-describedby="validity_start_date-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">When is the area's start date?</h1>
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


                        <!-- Start date input //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" role="group" aria-describedby="validity_end_date-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">When is the area's end date?</h1>
                                </legend>
                                <span id="validity_end_date-hint" class="govuk-hint">
                                    This is optional and should usually be left unset (open-ended) unless you know the area is only needed for a limited time.
                                </span>
                                <div class="govuk-date-input" id="validity_end_date">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_end_date-day">
                                                Day
                                            </label>
                                            <input class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="v-day" name="validity_end_date-day" type="text" pattern="[0-9]{1,2}">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_end_date-month">
                                                Month
                                            </label>
                                            <input class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="validity_end_date-month" name="validity_end_date-month" type="text" pattern="[0-9]{1,2}">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_end_date-year">
                                                Year
                                            </label>
                                            <input class="govuk-input govuk-date-input__input govuk-input--width-4" size="4" maxlength="4" id="validity_end_date-year" name="validity_end_date-year" type="text" pattern="[0-9]{2,4}">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- End date input //-->

                        <!-- Start geographical area ID //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="event-name">
                                Configure memberships
                            </label>
                            <span id="sign-in-2-item-hint" class="govuk-hint">
                                If the geographical area type is country or region, optionally use this section to add it to one or more existing groups. If it is a group, optionally use this section to add countries and/or regions to it. Added items will be shown in the list below. Click an item to change its join or leave date.</span>
                            <input class="govuk-input" id="event-name" name="event-name" type="text">
                        </div>
                        <!-- End geographical area ID //-->

                        <!-- Start table //-->
                        <table class="govuk-table">
                            <caption class="govuk-table__caption">Geographical area memberships</caption>
                            <thead class="govuk-table__head">
                                <tr class="govuk-table__row">
                                    <th scope="col" class="govuk-table__header">ID</th>
                                    <th scope="col" class="govuk-table__header">Description</th>
                                    <th scope="col" class="govuk-table__header">Join date</th>
                                    <th scope="col" class="govuk-table__header">Leave date</th>
                                    <th scope="col" class="govuk-table__header">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="govuk-table__body">
                            <tr class="govuk-table__row">
                                    <th scope="row" class="govuk-table__header">1011</th>
                                    <td class="govuk-table__cell">Erga Omnes</td>
                                    <td class="govuk-table__cell">&nbsp;</td>
                                    <td class="govuk-table__cell">&nbsp;</td>
                                    <td class="govuk-table__cell"><a href="#">Remove</a></td>
                                </tr>
                                <tr class="govuk-table__row">
                                    <th scope="row" class="govuk-table__header">1008</th>
                                    <td class="govuk-table__cell">All third countries</td>
                                    <td class="govuk-table__cell">&nbsp;</td>
                                    <td class="govuk-table__cell">&nbsp;</td>
                                    <td class="govuk-table__cell"><a href="#">Remove</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="govuk-button" data-module="govuk-button">Add membership</button>
                        <!-- End table //-->

                        <hr id="results" />


                        <!-- Start button //-->
                        <button class="govuk-button" data-module="govuk-button">Submit for approval</button>
                        <button class="govuk-button" data-module="govuk-button">Save progress</button>
                        <a href="/" class="textual_button">Cancel</a>
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