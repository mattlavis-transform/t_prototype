<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
?>
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
                    <a class="govuk-breadcrumbs__link" href="#">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/measures">Measures</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Create new measures</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Create measures</h1>
                    <!-- End main title //-->
                </div>
            </div>


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
                                        <a class="govuk-link" href="#" aria-describedby="eligibility-completed">
                                            Core measure data
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="eligibility-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="create_edit_commodities.html" aria-describedby="read-declaration-completed">
                                            Commodites
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="read-declaration-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="create_edit_commodities.html" aria-describedby="read-declaration-completed">
                                            Duties
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="read-declaration-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="create_edit_commodities.html" aria-describedby="read-declaration-completed">
                                            Measure conditions
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="read-declaration-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="create_edit_commodities.html" aria-describedby="read-declaration-completed">
                                            Footnotes
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="read-declaration-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="create_edit_commodities.html" aria-describedby="read-declaration-completed">
                                            Confirmation
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="read-declaration-completed">Completed</strong>
                                </li>
                            </ul>
                        </li>
                        <!--
                        <li>
                            <h2 class="app-task-list__section">
                                <span class="app-task-list__section-number">2. </span> Prepare application
                            </h2>
                            <ul class="app-task-list__items">
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="company-information-completed">
                                            Company information
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="contact-details-completed">
                                            Your contact details
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="contact-details-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#">
                                            List convictions
                                        </a>
                                    </span>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#">
                                            Provide financial evidence
                                        </a>
                                    </span>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="medical-information-completed">
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
                                        <a class="govuk-link" href="#">
                                            Submit and pay
                                        </a>
                                    </span>
                                </li>
                            </ul>
                        </li>
                        //-->
                    </ol>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>