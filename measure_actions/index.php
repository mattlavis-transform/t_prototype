<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->get_measure_actions();
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
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Measure actions</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">View measure actions</h1>
                    <!-- End main title //-->
                    <?php
                    // Inset control
                    new inset_control(
                        $text = "Use this screen to view measure actions.  Measure actions cannot be modified, as doing so is likely
                        to cause significant issues in CDS and other downstream systems. This screen is read-only."
                    );
?>
                    <div class="govuk-grid-row">
                        <div class="govuk-grid-column-full">
                            <!-- Start table //-->
                            <table class="govuk-table govuk-table--m sticky">
                                <thead class="govuk-table__head">
                                    <tr class="govuk-table__row">
                                        <th scope="col" class="govuk-table__header" nowrap>Measure action</th>
                                        <th scope="col" class="govuk-table__header">Description</th>
                                        <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                        <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                    </tr>
                                </thead>
                                <tbody class="govuk-table__body">
                                    <?php
                                    foreach ($application->measure_actions as $measure_action) {
                                    ?>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell"><?= $measure_action->action_code ?></td>
                                            <td class="govuk-table__cell"><?= $measure_action->description ?></td>
                                            <td class="govuk-table__cell"><?= $measure_action->validity_start_date ?></td>
                                            <td class="govuk-table__cell"><?= $measure_action->validity_end_date ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!-- End table //-->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>