<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("../includes/metadata.php");
?>

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
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Help</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->


        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Help</h1>
                    <!-- End main title //-->

                    <h2 id="measure_conditions" class="govuk-heading-m help">Measure conditions</h2>
                    <div class="help_col1">
                        <img src="/assets/images/powerpoint.png" />
                    </div>
                    <div class="help_col2">
                        <p class="govuk-body">Powerpoint document illustrating the options for managing measure conditions</p>
                        <p class="govuk-body"><a href="">Download help on using measure conditions</a></p>
                    </div>

                    <h2 id="measure_conditions" class="govuk-heading-m help">Measure conditions</h2>
                    <div class="help_col1">
                        <img src="/assets/images/powerpoint.png" />
                    </div>
                    <div class="help_col2">
                        <p class="govuk-body">Dolor ex aliquip aliquip incididunt dolor sint. Tempor irure eu duis duis aliqua proident proident magna labore ea Lorem nulla amet officia. Tempor et do commodo in nostrud in elit.</p>
                        <p class="govuk-body"><a href="">Download help on using measure conditions</a></p>
                    </div>

                </div>
            </div>


            <div class="app-back-to-top" data-module="app-back-to-top">
                <a class="govuk-link govuk-link--no-visited-state app-back-to-top__link" href="#top">
                    <svg role="presentation" focusable="false" class="app-back-to-top__icon" xmlns="http://www.w3.org/2000/svg" width="13" height="17" viewBox="0 0 13 17">
                        <path fill="currentColor" d="M6.5 0L0 6.5 1.4 8l4-4v12.7h2V4l4.3 4L13 6.4z"></path>
                    </svg>Back to top
                </a>
            </div>

        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>