<?php
require(dirname(__FILE__) . "../../includes/db.php");
$error_handler = new error_handler();
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

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start panel //-->
                    <div class="govuk-panel govuk-panel--confirmation">
                        <h1 class="govuk-panel__title">
                            Geographical area membership terminated
                        </h1>
                        <div class="govuk-panel__body">
                            Antigua and Barbuda (AG)'s membership of CARIFORUM (1033) has been scheduled for termination on 23 Jan 2020, subject to approval.
                        </div>
                    </div>
                    <!-- End panel //-->
                    <h2 class="govuk-heading-m">Next steps</h2>
                    <ul class="govuk-list">
                        <li><a href="/geographical_areas/">Manage more geographical areas</a></li>
                        <li><a href="/workbaskets/view.html">View content of your workbasket</a></li>
                        <li><a href="/">Return to main menu</a></li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>