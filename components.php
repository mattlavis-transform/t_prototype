<?php
require("includes/db.php");
?>
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
    require("includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("includes/phase_banner.php");
        ?>

        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="#">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="#">Passports, travel and living abroad</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Travel abroad</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Page title</h1>
                    <!-- End main title //-->

                    <!-- Start warning //-->
                    <div class="govuk-warning-text">
                        <span class="govuk-warning-text__icon" aria-hidden="true">!</span>
                        <strong class="govuk-warning-text__text">
                            <span class="govuk-warning-text__assistive">Warning</span>
                            You can be fined up to £5,000 if you do not register.
                        </strong>
                    </div>
                    <!-- End warning //-->


                    <!-- Start task list //-->
                    <div class="govuk-grid-row">
                        <div class="govuk-grid-column-two-thirds">
                            <h1 class="govuk-heading-xl">
                                Service name goes here
                            </h1>

                            <ol class="app-task-list">
                                <li>
                                    <h2 class="app-task-list__section">
                                        <span class="app-task-list__section-number">1. </span> Check before you start
                                    </h2>
                                    <ul class="app-task-list__items">
                                        <li class="app-task-list__item">
                                            <span class="app-task-list__task-name">
                                                <a href="#" aria-describedby="eligibility-completed">
                                                    Check eligibility
                                                </a>
                                            </span>
                                            <strong class="govuk-tag app-task-list__task-completed" id="eligibility-completed">Completed</strong>
                                        </li>
                                        <li class="app-task-list__item">
                                            <span class="app-task-list__task-name">
                                                <a href="#" aria-describedby="read-declaration-completed">
                                                    Read declaration
                                                </a>
                                            </span>
                                            <strong class="govuk-tag app-task-list__task-completed" id="read-declaration-completed">Completed</strong>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <h2 class="app-task-list__section">
                                        <span class="app-task-list__section-number">2. </span> Prepare application
                                    </h2>
                                    <ul class="app-task-list__items">
                                        <li class="app-task-list__item">
                                            <span class="app-task-list__task-name">
                                                <a href="#" aria-describedby="company-information-completed">
                                                    Company information
                                                </a>
                                            </span>
                                            <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                        </li>
                                        <li class="app-task-list__item">
                                            <span class="app-task-list__task-name">
                                                <a href="#" aria-describedby="contact-details-completed">
                                                    Your contact details
                                                </a>
                                            </span>
                                            <strong class="govuk-tag app-task-list__task-completed" id="contact-details-completed">Completed</strong>
                                        </li>
                                        <li class="app-task-list__item">
                                            <span class="app-task-list__task-name">
                                                <a href="#">
                                                    List convictions
                                                </a>
                                            </span>
                                        </li>
                                        <li class="app-task-list__item">
                                            <span class="app-task-list__task-name">
                                                <a href="#">
                                                    Provide financial evidence
                                                </a>
                                            </span>
                                        </li>
                                        <li class="app-task-list__item">
                                            <span class="app-task-list__task-name">
                                                <a href="#" aria-describedby="medical-information-completed">
                                                    Give medical information
                                                </a>
                                            </span>
                                            <strong class="govuk-tag app-task-list__task-completed" id="medical-information-completed">Completed</strong>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <h2 class="app-task-list__section">
                                        <span class="app-task-list__section-number">3. </span> Apply
                                    </h2>
                                    <ul class="app-task-list__items">
                                        <li class="app-task-list__item">
                                            <span class="app-task-list__task-name">
                                                <a href="#">
                                                    Submit and pay
                                                </a>
                                            </span>
                                        </li>
                                    </ul>
                                </li>
                            </ol>

                        </div>
                    </div>
                    <!-- End task list //-->



                    <!-- Start details component //-->
                    <details class="govuk-details" data-module="govuk-details">
                        <summary class="govuk-details__summary">
                            <span class="govuk-details__summary-text">
                                Help with nationality
                            </span>
                        </summary>
                        <div class="govuk-details__text">
                            We need to know your nationality so we can work out which elections you’re entitled to vote in. If you cannot provide your nationality, you’ll have to send copies of identity documents through the post.
                        </div>
                    </details>
                    <!-- End details component //-->

                    <!-- Start inset text //-->
                    <div class="govuk-inset-text">
                        It can take up to 8 weeks to register a lasting power of attorney if there are no mistakes in the application.
                    </div>
                    <!-- End inset text //-->

                    <!-- Start panel //-->
                    <div class="govuk-panel govuk-panel--confirmation">
                        <h1 class="govuk-panel__title">
                            Application complete
                        </h1>
                        <div class="govuk-panel__body">
                            Your reference number<br><strong>HDJ2123F</strong>
                        </div>
                    </div>
                    <!-- End panel //-->

                    <!-- Start error summary //-->
                    <div class="govuk-error-summary" aria-labelledby="error-summary-title" role="alert" tabindex="-1" data-module="govuk-error-summary">
                        <h2 class="govuk-error-summary__title" id="error-summary-title">
                            There is a problem
                        </h2>
                        <div class="govuk-error-summary__body">
                            <ul class="govuk-list govuk-error-summary__list">
                                <li>
                                    <a href="#passport-issued-error">The date your passport was issued must be in the past</a>
                                </li>
                                <li>
                                    <a href="#postcode-error">Enter a postcode, like AA1 1AA</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End error summary //-->


                    <!-- Start select //-->
                    <div class="govuk-form-group">
                        <label class="govuk-label" for="sort">
                            Sort by
                        </label>
                        <select class="govuk-select" id="sort" name="sort">
                            <option value="published">Recently published</option>
                            <option value="updated" selected>Recently updated</option>
                            <option value="views">Most views</option>
                            <option value="comments">Most comments</option>
                        </select>
                    </div>
                    <!-- End select //-->


                    <!-- Start text input //-->
                    <div class="govuk-form-group">
                        <label class="govuk-label" for="event-name">
                            Event name
                        </label>
                        <input class="govuk-input" id="event-name" name="event-name" type="text">
                    </div>
                    <!-- End text input //-->

                    <!-- Start date input //-->
                    <div class="govuk-form-group">
                        <fieldset class="govuk-fieldset" role="group" aria-describedby="passport-issued-hint">
                            <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                <h1 class="govuk-fieldset__heading">
                                    When was your passport issued?
                                </h1>
                            </legend>
                            <span id="passport-issued-hint" class="govuk-hint">
                                For example, 12 11 2007
                            </span>
                            <div class="govuk-date-input" id="passport-issued">
                                <div class="govuk-date-input__item">
                                    <div class="govuk-form-group">
                                        <label class="govuk-label govuk-date-input__label" for="passport-issued-day">
                                            Day
                                        </label>
                                        <input class="govuk-input govuk-date-input__input govuk-input--width-2" id="passport-issued-day" name="passport-issued-day" type="number" pattern="[0-9]*">
                                    </div>
                                </div>
                                <div class="govuk-date-input__item">
                                    <div class="govuk-form-group">
                                        <label class="govuk-label govuk-date-input__label" for="passport-issued-month">
                                            Month
                                        </label>
                                        <input class="govuk-input govuk-date-input__input govuk-input--width-2" id="passport-issued-month" name="passport-issued-month" type="number" pattern="[0-9]*">
                                    </div>
                                </div>
                                <div class="govuk-date-input__item">
                                    <div class="govuk-form-group">
                                        <label class="govuk-label govuk-date-input__label" for="passport-issued-year">
                                            Year
                                        </label>
                                        <input class="govuk-input govuk-date-input__input govuk-input--width-4" id="passport-issued-year" name="passport-issued-year" type="number" pattern="[0-9]*">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <!-- End date input //-->


                    <!-- Start error //-->
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
                    <!-- End error //-->


                    <!-- Start fieldset //-->
                    <fieldset class="govuk-fieldset">
                        <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                            <h1 class="govuk-fieldset__heading">
                                What is your address?
                            </h1>
                        </legend>

                        <div class="govuk-form-group">
                            <label class="govuk-label" for="address-line-1">
                                Building and street <span class="govuk-visually-hidden">line 1 of 2</span>
                            </label>
                            <input class="govuk-input govuk-!-width-one-half" id="address-line-1" name="address-line-1" type="text" size="4" maxlength="15">
                        </div>

                        <div class="govuk-form-group">
                            <label class="govuk-label" for="address-line-2">
                                <span class="govuk-visually-hidden">Building and street line 2 of 2</span>
                            </label>
                            <input class="govuk-input govuk-!-width-one-half" id="address-line-2" name="address-line-2" type="text">
                        </div>

                        <div class="govuk-form-group">
                            <label class="govuk-label" for="address-town">
                                Town or city
                            </label>
                            <input class="govuk-input govuk-!-width-two-thirds" id="address-town" name="address-town" type="text">
                        </div>

                        <div class="govuk-form-group">
                            <label class="govuk-label" for="address-county">
                                County
                            </label>
                            <input class="govuk-input govuk-!-width-two-thirds" id="address-county" name="address-county" type="text">
                        </div>

                        <div class="govuk-form-group">
                            <label class="govuk-label" for="address-postcode">
                                Postcode
                            </label>
                            <input class="govuk-input govuk-input--width-10" id="address-postcode" name="address-postcode" type="text">
                        </div>
                    </fieldset>
                    <!-- End fieldset //-->


                    <!-- Start checkboxes //-->
                    <div class="govuk-form-group">
                        <fieldset class="govuk-fieldset" aria-describedby="waste-hint">
                            <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                <h1 class="govuk-fieldset__heading">
                                    Which types of waste do you transport?
                                </h1>
                            </legend>
                            <span id="waste-hint" class="govuk-hint">
                                Select all that apply.
                            </span>
                            <div class="govuk-checkboxes">
                                <div class="govuk-checkboxes__item">
                                    <input class="govuk-checkboxes__input" id="waste" name="waste" type="checkbox" value="carcasses">
                                    <label class="govuk-label govuk-checkboxes__label" for="waste">
                                        Waste from animal carcasses
                                    </label>
                                </div>
                                <div class="govuk-checkboxes__item">
                                    <input class="govuk-checkboxes__input" id="waste-2" name="waste" type="checkbox" value="mines">
                                    <label class="govuk-label govuk-checkboxes__label" for="waste-2">
                                        Waste from mines or quarries
                                    </label>
                                </div>
                                <div class="govuk-checkboxes__item">
                                    <input class="govuk-checkboxes__input" id="waste-3" name="waste" type="checkbox" value="farm">
                                    <label class="govuk-label govuk-checkboxes__label" for="waste-3">
                                        Farm or agricultural waste
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <!-- End checkboxes //-->


                    <!-- Start radios //-->
                    <div class="govuk-form-group">
                        <fieldset class="govuk-fieldset" aria-describedby="changed-name-hint">
                            <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                <h1 class="govuk-fieldset__heading">
                                    Have you changed your name?
                                </h1>
                            </legend>
                            <span id="changed-name-hint" class="govuk-hint">
                                This includes changing your last name or spelling your name differently.
                            </span>
                            <div class="govuk-radios govuk-radios--inline">
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input" id="changed-name" name="changed-name" type="radio" value="yes">
                                    <label class="govuk-label govuk-radios__label" for="changed-name">
                                        Yes
                                    </label>
                                </div>
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input" id="changed-name-2" name="changed-name" type="radio" value="no">
                                    <label class="govuk-label govuk-radios__label" for="changed-name-2">
                                        No
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <!-- End radios //-->

                    <div class="govuk-form-group">
                        <fieldset class="govuk-fieldset">
                            <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                <h1 class="govuk-fieldset__heading">
                                    Where do you live?
                                </h1>
                            </legend>
                            <div class="govuk-radios">
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input" id="where-do-you-live" name="where-do-you-live" type="radio" value="england">
                                    <label class="govuk-label govuk-radios__label" for="where-do-you-live">
                                        England
                                    </label>
                                </div>
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input" id="where-do-you-live-2" name="where-do-you-live" type="radio" value="scotland">
                                    <label class="govuk-label govuk-radios__label" for="where-do-you-live-2">
                                        Scotland
                                    </label>
                                </div>
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input" id="where-do-you-live-3" name="where-do-you-live" type="radio" value="wales">
                                    <label class="govuk-label govuk-radios__label" for="where-do-you-live-3">
                                        Wales
                                    </label>
                                </div>
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input" id="where-do-you-live-4" name="where-do-you-live" type="radio" value="northern-ireland">
                                    <label class="govuk-label govuk-radios__label" for="where-do-you-live-4">
                                        Northern Ireland
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>


                    <!-- Start radios - vertical with hints //-->
                    <div class="govuk-form-group">
                        <fieldset class="govuk-fieldset" aria-describedby="sign-in-hint">
                            <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
                                <h1 class="govuk-fieldset__heading">
                                    How do you want to sign in?
                                </h1>
                            </legend>
                            <span id="sign-in-hint" class="govuk-hint">
                                You’ll need an account to prove your identity and complete your Self Assessment.
                            </span>
                            <div class="govuk-radios">
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input" id="sign-in" name="sign-in" type="radio" value="government-gateway" aria-describedby="sign-in-item-hint">
                                    <label class="govuk-label govuk-radios__label govuk-label--s" for="sign-in">
                                        Sign in with Government Gateway
                                    </label>
                                    <span id="sign-in-item-hint" class="govuk-hint govuk-radios__hint">
                                        You’ll have a user ID if you’ve registered for Self Assessment or filed a tax return online before.
                                    </span>
                                </div>
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input" id="sign-in-2" name="sign-in" type="radio" value="govuk-verify" aria-describedby="sign-in-2-item-hint">
                                    <label class="govuk-label govuk-radios__label govuk-label--s" for="sign-in-2">
                                        Sign in with GOV.UK Verify
                                    </label>
                                    <span id="sign-in-2-item-hint" class="govuk-hint govuk-radios__hint">
                                        You’ll have an account if you’ve already proved your identity with either Barclays, CitizenSafe, Digidentity, Experian, Post Office, Royal Mail or SecureIdentity.
                                    </span>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <!-- End radios - vertical with hints //-->


                    <!-- Start text area //-->
                    <div class="govuk-form-group">
                        <label class="govuk-label" for="more-detail">
                            Can you provide more detail?
                        </label>
                        <span id="more-detail-hint" class="govuk-hint">
                            Do not include personal or financial information, like your National Insurance number or credit card details.
                        </span>
                        <textarea class="govuk-textarea" id="more-detail" name="more-detail" rows="5" aria-describedby="more-detail-hint"></textarea>
                    </div>
                    <!-- End text area //-->

                    <!-- Start character count //-->
                    <div class="govuk-character-count" data-module="govuk-character-count" data-maxlength="200">
                        <div class="govuk-form-group">
                            <label class="govuk-label" for="with-hint">Can you provide more detail?</label>
                            <span id="with-hint-hint" class="govuk-hint">Do not include personal or financial information like your National Insurance number or credit card details.</span>
                            <textarea class="govuk-textarea govuk-js-character-count" id="with-hint" name="with-hint" rows="5" aria-describedby="with-hint-info with-hint-hint"></textarea>
                        </div>

                        <span id="with-hint-info" class="govuk-hint govuk-character-count__message" aria-live="polite">
                            You can enter up to 200 characters
                        </span>
                    </div>
                    <!-- End character count //-->


                    <!-- Start button //-->
                    <button class="govuk-button" data-module="govuk-button">Save and continue</button>
                    <!-- End button //-->

                    <!-- Start table //-->
                    <table class="govuk-table">
                        <caption class="govuk-table__caption">Dates and amounts</caption>
                        <thead class="govuk-table__head">
                            <tr class="govuk-table__row">
                                <th scope="col" class="govuk-table__header">Date</th>
                                <th scope="col" class="govuk-table__header">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header">First 6 weeks</th>
                                <td class="govuk-table__cell">£109.80 per week</td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header">Next 33 weeks</th>
                                <td class="govuk-table__cell">£109.80 per week</td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header">Total estimated pay</th>
                                <td class="govuk-table__cell">£4,282.20</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End table //-->


                    <div class="govuk-tabs" data-module="govuk-tabs">
                        <h2 class="govuk-tabs__title">
                            Contents
                        </h2>
                        <ul class="govuk-tabs__list">
                            <li class="govuk-tabs__list-item govuk-tabs__list-item--selected">
                                <a class="govuk-tabs__tab" href="#past-day">
                                    Past day
                                </a>
                            </li>
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#past-week">
                                    Past week
                                </a>
                            </li>
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#past-month">
                                    Past month
                                </a>
                            </li>
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#past-year">
                                    Past year
                                </a>
                            </li>
                        </ul>
                        <section class="govuk-tabs__panel" id="past-day">
                            <h2 class="govuk-heading-l">Past day</h2>
                            <table class="govuk-table">
                                <thead class="govuk-table__head">
                                    <tr class="govuk-table__row">
                                        <th scope="col" class="govuk-table__header">Case manager</th>
                                        <th scope="col" class="govuk-table__header">Cases opened</th>
                                        <th scope="col" class="govuk-table__header">Cases closed</th>
                                    </tr>
                                </thead>
                                <tbody class="govuk-table__body">
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">David Francis</td>
                                        <td class="govuk-table__cell">3</td>
                                        <td class="govuk-table__cell">0</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">Paul Farmer</td>
                                        <td class="govuk-table__cell">1</td>
                                        <td class="govuk-table__cell">0</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">Rita Patel</td>
                                        <td class="govuk-table__cell">2</td>
                                        <td class="govuk-table__cell">0</td>
                                    </tr>
                                </tbody>
                            </table>

                        </section>
                        <section class="govuk-tabs__panel govuk-tabs__panel--hidden" id="past-week">
                            <h2 class="govuk-heading-l">Past week</h2>
                            <table class="govuk-table">
                                <thead class="govuk-table__head">
                                    <tr class="govuk-table__row">
                                        <th scope="col" class="govuk-table__header">Case manager</th>
                                        <th scope="col" class="govuk-table__header">Cases opened</th>
                                        <th scope="col" class="govuk-table__header">Cases closed</th>
                                    </tr>
                                </thead>
                                <tbody class="govuk-table__body">
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">David Francis</td>
                                        <td class="govuk-table__cell">24</td>
                                        <td class="govuk-table__cell">18</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">Paul Farmer</td>
                                        <td class="govuk-table__cell">16</td>
                                        <td class="govuk-table__cell">20</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">Rita Patel</td>
                                        <td class="govuk-table__cell">24</td>
                                        <td class="govuk-table__cell">27</td>
                                    </tr>
                                </tbody>
                            </table>

                        </section>
                        <section class="govuk-tabs__panel govuk-tabs__panel--hidden" id="past-month">
                            <h2 class="govuk-heading-l">Past month</h2>
                            <table class="govuk-table">
                                <thead class="govuk-table__head">
                                    <tr class="govuk-table__row">
                                        <th scope="col" class="govuk-table__header">Case manager</th>
                                        <th scope="col" class="govuk-table__header">Cases opened</th>
                                        <th scope="col" class="govuk-table__header">Cases closed</th>
                                    </tr>
                                </thead>
                                <tbody class="govuk-table__body">
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">David Francis</td>
                                        <td class="govuk-table__cell">98</td>
                                        <td class="govuk-table__cell">95</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">Paul Farmer</td>
                                        <td class="govuk-table__cell">122</td>
                                        <td class="govuk-table__cell">131</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">Rita Patel</td>
                                        <td class="govuk-table__cell">126</td>
                                        <td class="govuk-table__cell">142</td>
                                    </tr>
                                </tbody>
                            </table>

                        </section>
                        <section class="govuk-tabs__panel govuk-tabs__panel--hidden" id="past-year">
                            <h2 class="govuk-heading-l">Past year</h2>
                            <table class="govuk-table">
                                <thead class="govuk-table__head">
                                    <tr class="govuk-table__row">
                                        <th scope="col" class="govuk-table__header">Case manager</th>
                                        <th scope="col" class="govuk-table__header">Cases opened</th>
                                        <th scope="col" class="govuk-table__header">Cases closed</th>
                                    </tr>
                                </thead>
                                <tbody class="govuk-table__body">
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">David Francis</td>
                                        <td class="govuk-table__cell">1380</td>
                                        <td class="govuk-table__cell">1472</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">Paul Farmer</td>
                                        <td class="govuk-table__cell">1129</td>
                                        <td class="govuk-table__cell">1083</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <td class="govuk-table__cell">Rita Patel</td>
                                        <td class="govuk-table__cell">1539</td>
                                        <td class="govuk-table__cell">1265</td>
                                    </tr>
                                </tbody>
                            </table>

                        </section>
                    </div>

                    <!-- Start accordion //-->
                    <div class="govuk-accordion" data-module="govuk-accordion" id="accordion-default">
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-default-heading-1">
                                        Writing well for the web
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-default-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-default-heading-1">
                                <p class='govuk-body'>This is the content for Writing well for the web.</p>
                            </div>
                        </div>
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-default-heading-2">
                                        Writing well for specialists
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-default-content-2" class="govuk-accordion__section-content" aria-labelledby="accordion-default-heading-2">
                                <p class='govuk-body'>This is the content for Writing well for specialists.</p>
                            </div>
                        </div>
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-default-heading-3">
                                        Know your audience
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-default-content-3" class="govuk-accordion__section-content" aria-labelledby="accordion-default-heading-3">
                                <p class='govuk-body'>This is the content for Know your audience.</p>
                            </div>
                        </div>
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-default-heading-4">
                                        How people read
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-default-content-4" class="govuk-accordion__section-content" aria-labelledby="accordion-default-heading-4">
                                <p class='govuk-body'>This is the content for How people read.</p>
                            </div>
                        </div>
                    </div>
                    <!-- End accordion //-->


                    <!-- Start summary list //-->
                    <dl class="govuk-summary-list">
                        <div class="govuk-summary-list__row">
                            <dt class="govuk-summary-list__key">
                                Name
                            </dt>
                            <dd class="govuk-summary-list__value">
                                Sarah Philips
                            </dd>
                            <dd class="govuk-summary-list__actions">
                                <a class="govuk-link" href="#">
                                    Change<span class="govuk-visually-hidden"> name</span>
                                </a>
                            </dd>
                        </div>
                        <div class="govuk-summary-list__row">
                            <dt class="govuk-summary-list__key">
                                Date of birth
                            </dt>
                            <dd class="govuk-summary-list__value">
                                5 January 1978
                            </dd>
                            <dd class="govuk-summary-list__actions">
                                <a class="govuk-link" href="#">
                                    Change<span class="govuk-visually-hidden"> date of birth</span>
                                </a>
                            </dd>
                        </div>
                        <div class="govuk-summary-list__row">
                            <dt class="govuk-summary-list__key">
                                Contact information
                            </dt>
                            <dd class="govuk-summary-list__value">
                                72 Guild Street<br>London<br>SE23 6FH
                            </dd>
                            <dd class="govuk-summary-list__actions">
                                <a class="govuk-link" href="#">
                                    Change<span class="govuk-visually-hidden"> contact information</span>
                                </a>
                            </dd>
                        </div>
                        <div class="govuk-summary-list__row">
                            <dt class="govuk-summary-list__key">
                                Contact details
                            </dt>
                            <dd class="govuk-summary-list__value">
                                <p class="govuk-body">07700 900457</p>
                                <p class="govuk-body">sarah.phillips@example.com</p>
                            </dd>
                            <dd class="govuk-summary-list__actions">
                                <a class="govuk-link" href="#">
                                    Change<span class="govuk-visually-hidden"> contact details</span>
                                </a>
                            </dd>
                        </div>
                    </dl>
                    <!-- End summary list //-->

                    <!-- Start back link //-->
                    <a href="#" class="govuk-back-link">Back</a>
                    <!-- End back link //-->
                </div>
            </div>
        </main>
    </div>
    <?php
    require("includes/footer.php");
    ?>

</body>

</html>