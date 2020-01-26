<?php
require(dirname(__FILE__) . "/includes/db.php");
$application = new application;
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
                <!--
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="#">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="#">Passports, travel and living abroad</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Travel abroad</li>
                //-->
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Home</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">


            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">


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
        </main>
    </div>
    <?php
    require("includes/footer.php");
    ?>

</body>

</html>