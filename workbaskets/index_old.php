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
    <script src="/js/application.js"></script>

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
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Regulations</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-two-thirds">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Find a regulation</h1>
                    <p class="govuk-body-s">Enter criteria to help find a regulation. Alternatively, <a class='govuk-link' href="/regulations/regulation_new.html">create a new regulation</a>.</p>
                    <!-- End main title //-->

                    <form action="#results">
                        <!-- Start regulation group //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="sort">Select the regulation group?</label>
                            <select class="govuk-select" id="sort" name="sort">
                                <option value="">- Please select a regulation group -</option>
                                <option value="ADD">ADD Additional duties</option>
                            </select>
                        </div>
                        <!-- End regulation group //-->


                        <!-- Start date input //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" role="group" aria-describedby="passport-issued-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">Start date from</h1>
                                </legend>
                                <span id="more-detail-hint" class="govuk-hint">
                                    Enter a date in the fields below to find any regulations that started after this date.
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

                        <!-- Start text area //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="more-detail"> Enter keyword(s)</label>
                            <span id="more-detail-hint" class="govuk-hint">
                                If you know the ID of the regulation, then you can enter the ID in the box below. Alternatively, enter any other keyword(s) to help locate the regulation.
                            </span>
                            <textarea class="govuk-textarea" id="more-detail" name="more-detail" rows="3" aria-describedby="more-detail-hint"></textarea>
                        </div>
                        <!-- End text area //-->


                        <!-- Start button //-->
                        <button class="govuk-button" data-module="govuk-button">Search</button>
                        <!-- End button //-->
                    </form>

                </div>
            </div>


            <hr id="results" />
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start table //-->
                    <table class="govuk-table">
                        <caption class="govuk-table__caption--m">Regulation search results</caption>
                        <thead class="govuk-table__head">
                            <tr class="govuk-table__row">
                                <th scope="col" class="govuk-table__header">ID</th>
                                <th scope="col" class="govuk-table__header">Legal base</th>
                                <th scope="col" class="govuk-table__header">Regulation group</th>
                                <th scope="col" class="govuk-table__header">Start date</th>
                                <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                <th scope="col" class="govuk-table__header">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header">P10234456</th>
                                <td class="govuk-table__cell"><a class='govuk-link' href="">Agreement between the European Union and Japan and Japan and Japan and Japan for an Economic Partnership AGRI red ind 4-6</a></td>
                                <td class="govuk-table__cell" nowrap>ADD - Additional duties</td>
                                <td class="govuk-table__cell" nowrap>01 Sep 2020</td>
                                <td class="govuk-table__cell" nowrap></td>
                                <td class="govuk-table__cell" nowrap><a class='govuk-link' href="">View measures</a></td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header">P10234456</th>
                                <td class="govuk-table__cell"><a class='govuk-link' href="">Agreement between the European Union and Japan for an Economic Partnership AGRI red ind 4-6</a></td>
                                <td class="govuk-table__cell" nowrap>ADD - Additional duties</td>
                                <td class="govuk-table__cell" nowrap>01 Sep 2020</td>
                                <td class="govuk-table__cell" nowrap></td>
                                <td class="govuk-table__cell" nowrap><a class='govuk-link' href="">View measures</a></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End table //-->
                    <nav>
                        <ul class="pagination">
                            <li><span>1</span></li>
                            <li><a rel="next" class="pagination-link" href="#">2</a></li>
                            <li><a rel="next" class="pagination-link" href="#">3</a></li>
                            <li><a rel="next" class="pagination-link" href="#">4</a></li>
                            <li><a rel="next" class="pagination-link" href="#">5</a></li>
                            <li><a rel="next" class="pagination-link" href="#">6</a></li>
                            <li><a rel="next" class="pagination-link" href="#">7</a></li>
                            <li><a rel="next" class="pagination-link" href="#">8</a></li>
                            <li><a rel="next" class="pagination-link" href="#">Last</a></li>
                        </ul>
                    </nav>
                </div>



            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>