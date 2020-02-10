<?php
require(dirname(__FILE__) . "../../includes/db.php");
$error_handler = new error_handler();
$application = new application;
$submitted = get_formvar("submitted");
$workbasket = new workbasket();
if ($submitted) {
    $application->session->create_workbasket();
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

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start panel //-->
                    <div class="govuk-panel govuk-panel--confirmation">
                        <h1 class="govuk-panel__title">
                            Your workbasket has been reassigned
                        </h1>
                        <div class="govuk-panel__body">
                            The workbasket <br><strong>jobby</strong><br />
                            has been reassigned to Marjorie Antrobus
                        </div>
                    </div>
                    <!-- End panel //-->
                    <h2 class="govuk-heading-m">Next steps</h2>
                    <li><a href="/workbaskets/view.html">View content of your workbasket</a></li>
                    <p class="govuk-body">Return to main menu to <a class='govuk-link' href="/">begin making changes</a>.</p>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>