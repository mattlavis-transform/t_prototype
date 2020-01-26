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
            
        </main>
    </div>
    <?php
    require("includes/footer.php");
    ?>

</body>

</html>