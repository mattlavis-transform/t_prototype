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
                    <a class="govuk-breadcrumbs__link" href="/regulations">Regulations</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">New regulation</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Create a new regulation</h1>
                    <!-- End main title //-->

                    <form action="">
                        <!-- Start text input //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label" for="regulation_id">Regulation ID</label>
                            <span id="passport-issued-hint" class="govuk-hint">This must be exactly 8 characters long</span>
                            <input class="govuk-input govuk-!-width-one-quarter" id="regulation_id" name="regulation_id" size="8" maxlength="8" type="text" pattern="[CPUSXNMQA][0-9]{7}">
                        </div>
                        <!-- End text input //-->

                        <!-- Start details component //-->
                        <details class="govuk-details" data-module="govuk-details">
                            <summary class="govuk-details__summary">
                                <span class="govuk-details__summary-text">Help with regulation identifiers</span>
                            </summary>
                            <div class="govuk-details__text govuk-\!-width-one-half">
                                <p>Please use the following structure for regulations. Start with one of the following characters:</p>
                                <table class="govuk-table" cellspacing="0">
                                    <tbody>
                                        <tr class="govuk-table__row">
                                            <th class="govuk-table__header medium b">Prefix</th>
                                            <th class="govuk-table__header medium b">Usage</th>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">C</td>
                                            <td class="govuk-table__cell medium">Draft regulation</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">P</td>
                                            <td class="govuk-table__cell medium">Preferential Trade Agreement / FTA</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">U</td>
                                            <td class="govuk-table__cell medium">Unilateral preferences (GSP)</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">S</td>
                                            <td class="govuk-table__cell medium">Suspensions and reliefs</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">X</td>
                                            <td class="govuk-table__cell medium">Import and Export control</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">N</td>
                                            <td class="govuk-table__cell medium">Trade remedies</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">M</td>
                                            <td class="govuk-table__cell medium">MFN</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">Q</td>
                                            <td class="govuk-table__cell medium">Quotas</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">A</td>
                                            <td class="govuk-table__cell medium">Agri measures</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </details>
                        <!-- End details component //-->

                        <!-- Start text input //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label" for="regulation_id">What is the public-facing regulation name?</label>
                            <span id="passport-issued-hint" class="govuk-hint">This is the name of the regulation as it would appear on (for example) legislation.gov.uk.</span>
                            <input class="govuk-input govuk-!-width-one-half" id="regulation_name" name="regulation_name" size="20" maxlength="20" type="text">
                        </div>
                        <!-- End text input //-->


                        <!-- Start description //-->
                        <div class="govuk-character-count" data-module="govuk-character-count" data-maxlength="200">
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="with-hint">What is the description of this regulation?</label>
                                <span id="with-hint-hint" class="govuk-hint">This is for information purposes only.</span>
                                <textarea class="govuk-textarea govuk-js-character-count" id="with-hint" name="with-hint" rows="3" aria-describedby="with-hint-info with-hint-hint"></textarea>
                            </div>

                            <span id="with-hint-info" class="govuk-hint govuk-character-count__message" aria-live="polite">
                                You can enter up to 200 characters
                            </span>
                        </div>
                        <!-- End description //-->

                        <!-- Start URL //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label" for="url">What is the URL of the regulation?</label>
                            <span id="passport-issued-hint" class="govuk-hint">Please enter the absolute URL of the regulation.</span>
                            <input class="govuk-input govuk-!-width-one-half" id="url" name="url" size="20" maxlength="20" type="text">
                        </div>
                        <!-- End URL //-->

                        <!-- Start regulation group //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label" for="sort">What type of regulation do you want to create?</label>
                            <select class="govuk-select" id="sort" name="sort">
                                <option value="published">Recently published</option>
                                <option value="updated" selected>Recently updated</option>
                                <option value="views">Most views</option>
                                <option value="comments">Most comments</option>
                            </select>
                        </div>
                        <!-- End regulation group //-->

                        <!-- Start details component //-->
                        <details class="govuk-details" data-module="govuk-details">
                            <summary class="govuk-details__summary">
                                <span class="govuk-details__summary-text">Help with regulation groups</span>
                            </summary>
                            <div class="govuk-details__text govuk-\!-width-one-half">

                                <p>It's crucial that you select the right regulation group or a trader's declaration may not process correctly. Please use the following regulation groups:</p>
                                <table class="govuk-table" cellspacing="0">
                                    <tbody>
                                        <tr class="govuk-table__row">
                                            <th class="govuk-table__header medium b">ID</th>
                                            <th class="govuk-table__header medium b">Usage</th>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">DNC</td>
                                            <td class="govuk-table__cell medium">MFN duties</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">DUM</td>
                                            <td class="govuk-table__cell medium">Anti-dumping and anti-subsidy duties</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">FTA</td>
                                            <td class="govuk-table__cell medium">Preferential tariffs and quotas as part of an FTA</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">KON</td>
                                            <td class="govuk-table__cell medium">Non-preferential quotas</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">MLA</td>
                                            <td class="govuk-table__cell medium">Import and export restrictions</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">PRF</td>
                                            <td class="govuk-table__cell medium">Preferential duties outside of a FTA</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">PRS</td>
                                            <td class="govuk-table__cell medium">Sanctions</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">SPG</td>
                                            <td class="govuk-table__cell medium">Unilateral preferences (GSP)</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">SUR</td>
                                            <td class="govuk-table__cell medium">Surveillance &amp; credibility checks</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">SUS</td>
                                            <td class="govuk-table__cell medium">Reliefs and suspensions</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">TXC</td>
                                            <td class="govuk-table__cell medium">Retaliatory measures and safeguard additional duties (not quotas)</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">UTS</td>
                                            <td class="govuk-table__cell medium">Supplementary units</td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell medium">VAU</td>
                                            <td class="govuk-table__cell medium">Unit Prices and Standard Import Values</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </details>
                        <!-- End details component //-->


                        <!-- Start button //-->
                        <button class="govuk-button" data-module="govuk-button">Create regulation</button>
                        <a href="/" class="textual_button govuk-link">Cancel</a>
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