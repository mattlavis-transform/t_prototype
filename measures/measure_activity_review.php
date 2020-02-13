<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures_activity");
$error_handler = new error_handler();

// If there is no measure activity in place, then create it
$measure_activity = new measure_activity();

$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $a = 1;
    $_SESSION["actvity_name"] = get_formvar("activity_name");
    $measure_activity->validate_form_activity_name();
} else {
    $measure_sid = null;
    if (isset($_SESSION["measure_sid"])) {
        $measure_sid = $_SESSION["measure_sid"];
        $measure_activity->measure_activity_sid = $measure_activity_sid;
        $measure_activity->populate_activity_from_db();
    } else {
        //$measure_sid = $application->session->workbasket->create_measure();
    }
}

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
                    <a class="govuk-breadcrumbs__link" href="#">Measures</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Measure activity x</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Measure activity x</h1>
                    <!-- End main title //-->

                    <!-- Start task list //-->
                    <ol class="app-task-list">
                        
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
                        //-->
                        <li>
                            <h2 class="app-task-list__section">
                                <span class="app-task-list__section-number">1. </span> Build measure activity
                            </h2>
                            <ul class="app-task-list__items">
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a href="#" aria-describedby="company-information-completed">
                                            Activity name
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a href="#" aria-describedby="company-information-completed">
                                            Core measure data
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="company-information-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a href="#" aria-describedby="contact-details-completed">
                                            Commodities
                                        </a>
                                    </span>
                                    <strong class="govuk-tag app-task-list__task-completed" id="contact-details-completed">Completed</strong>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a href="#">
                                            Duties
                                        </a>
                                    </span>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a href="#">
                                            Conditions
                                        </a>
                                    </span>
                                </li>
                                <li class="app-task-list__item">
                                    <span class="app-task-list__task-name">
                                        <a href="#" aria-describedby="medical-information-completed">
                                            Footnotes
                                        </a>
                                    </span>
                                    <!--<strong class="govuk-tag app-task-list__task-completed" id="medical-information-completed">Completed</strong>//-->
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
                                        <a href="#">
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