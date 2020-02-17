<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures_activity");
$measure_activity = new measure_activity();
$measure_activity->measure_activity_sid = intval(get_querystring("measure_activity_sid"));
$measure_activity->populate_from_db();

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
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/measures/">Measures</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Measure activity &quot;<?= $measure_activity->activity_name ?>&quot;</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Measure activity <span class="subheading"><?= $measure_activity->activity_name ?></span></h1>
                    <!-- End main title //-->

                    <!-- Start task list //-->
                    <ol class="app-task-list">
                        <!--
                        <li>
                            <h2 class="app-task-list__section">
                                <span class="app-task-list__section-number">Activity data
                            </h2>
                            <dl class="govuk-summary-list">
                            <div class="govuk-summary-list__row">
                                    <dt class="govuk-summary-list__key">
                                        Activity name
                                    </dt>
                                    <dd class="govuk-summary-list__value">
                                        My activity
                                    </dd>
                                    <dd class="govuk-summary-list__actions">
                                        <a class="govuk-link" href="#">
                                            Change<span class="govuk-visually-hidden"> name</span>
                                        </a>
                                    </dd>
                                </div>
                                <div class="govuk-summary-list__row">
                                    <dt class="govuk-summary-list__key">
                                        Activity name
                                    </dt>
                                    <dd class="govuk-summary-list__value">
                                        My activity
                                    </dd>
                                    <dd class="govuk-summary-list__actions">
                                        <a class="govuk-link" href="#">
                                            Change<span class="govuk-visually-hidden"> name</span>
                                        </a>
                                    </dd>
                                </div>
                                <div class="govuk-summary-list__row">
                                    <dt class="govuk-summary-list__key">
                                        Activity name
                                    </dt>
                                    <dd class="govuk-summary-list__value">
                                        My activity
                                    </dd>
                                    <dd class="govuk-summary-list__actions">
                                        <a class="govuk-link" href="#">
                                            Change<span class="govuk-visually-hidden"> name</span>
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                            <ul class="app-task-list__items">
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="eligibility-completed">
                                            Check eligibility
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="eligibility-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="read-declaration-completed">
                                            Read declaration
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="read-declaration-completed">Completed</strong>
                                </li>
                            </ul>
                        </li>
                        //-->
                        <li>
                            <h2 class="app-task-list__section">
                                <span class="app-task-list__section-number">1. </span> Build measure activity
                            </h2>
                            <ul class="app-task-list__items">
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="company-information-completed">
                                            Activity name (<?= $measure_activity->activity_name ?>)
                                        </a>
                                    </span>
                                    <?php if ($measure_activity->activity_name_complete) { ?>
                                        <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                    <?php } ?>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="company-information-completed">
                                            Core measure data
                                        </a>
                                    </span>
                                    <?php if ($measure_activity->core_data_complete) { ?>
                                    <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                    <?php } ?>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="contact-details-completed">
                                            Commodities
                                        </a>
                                    </span>
                                    <?php if ($measure_activity->commodity_data_complete) { ?>
                                    <strong class="govuk-tag app-task-list__task-completed" id="contact-details-completed">Completed</strong>
                                    <?php } ?>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#">
                                            Duties
                                        </a>
                                    </span>
                                    <?php if ($measure_activity->duty_data_complete) { ?>
                                    <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                    <?php } ?>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#">
                                            Conditions
                                        </a>
                                    </span>
                                    <?php if ($measure_activity->condition_data_complete) { ?>
                                    <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                    <?php } ?>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#" aria-describedby="medical-information-completed">
                                            Footnotes
                                        </a>
                                    </span>
                                    <?php if ($measure_activity->footnote_data_complete) { ?>
                                    <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                    <?php } ?>
                                    </li>
                            </ul>
                        </li>
                        <li>
                            <h2 class="app-task-list__section">
                                <span class="app-task-list__section-number">2. </span> Submit
                            </h2>
                            <ul class="app-task-list__items">
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a class="govuk-link" href="#">
                                            Submit for approval
                                        </a>
                                    </span>
                                </li>
                            </ul>
                        </li>
                    </ol>

                    <!-- End task list //-->

                </div>
            </div>
        </main>

    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>