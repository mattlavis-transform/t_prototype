<?php
require(dirname(__FILE__) . "/includes/db.php");
$application = new application;
?>
<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("includes/metadata.php");
?>

<body class="govuk-template__body">
    <?php
    require("includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("includes/phase_banner.php");
        ?>


        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Main menu</h1>
                    <!-- End main title //-->
                </div>
            </div>

            <div class="govuk-grid-row">
                <!-- Start column one //-->
                <div class="govuk-grid-column-one-quarter">
                    <h2 class="govuk-heading-m govuk-!-margin-0">Workbaskets</h2>
                    <?php
                    $application->session->show_workbasket_component_home();
                    ?>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage regulations</h2>
                    <ul class="menu">
                        <li><a href="/regulations/create_edit.html">Create a new regulation</a></li>
                        <li><a href="/regulations/">Find and edit regulations</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage measures</h2>
                    <ul class="menu">
                        <li><a href="/measures/create_edit.html">Create new measures</a></li>
                        <li><a href="/measures/search.html">Find and edit measures</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage quotas</h2>
                    <ul class="menu">
                        <li><a href="/quotas/create_edit.html">Create a new quota</a></li>
                        <li><a href="/quotas/">Find and edit quotas</a></li>
                    </ul>

                </div>
                <!-- End column one //-->

                <!-- Start column two //-->
                <div class="govuk-grid-column-one-quarter">
                    <h2 class="govuk-heading-m govuk-!-margin-0">View goods classification</h2>
                    <!--<p class="govuk-body-xs">Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet </p>//-->
                    <ul class="menu">
                        <li><a href="/goods_nomenclatures/">Find commodities</a></li>
                        <!--<li><a href="/goods_nomenclatures/create_edit.html">Create commodities</a></li>//-->
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage geographical areas</h2>
                    <ul class="menu">
                        <li><a href="/geographical_areas/create_edit.html">Create a new geographical area</a></li>
                        <li><a href="/geographical_areas/">Find and edit geographical areas</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage footnotes</h2>
                    <ul class="menu">
                        <li><a href="/footnotes/create_edit.html">Create a new footnote</a></li>
                        <li><a href="/footnotes/">Find and edit footnotes</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage certificates</h2>
                    <ul class="menu">
                        <li><a href="/certificates/create_edit.html">Create a new certificate</a></li>
                        <li><a href="/certificates/">Find and edit certificates</a></li>
                    </ul>

                </div>
                <!-- End column two //-->

                <!-- Start column three //-->
                <div class="govuk-grid-column-one-quarter">
                    <h2 class="govuk-heading-m govuk-!-margin-0">Manage additional codes</h2>
                    <ul class="menu">
                        <li><a href="/additional_codes/create_edit.html">Create new additional code</a></li>
                        <li><a href="/additional_codes/">Find and edit additional codes</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Reference documents</h2>
                    <ul class="menu">
                        <li><a href="">Manage reference documents</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Reporting and auditing</h2>
                    <ul class="menu">
                        <li><a href="">Generate audit report</a></li>
                        <li><a href="/snapshot/">Measure snapshots</a></li>
                    </ul>

                    <h2 class="govuk-heading-m govuk-!-margin-0">Help</h2>
                    <ul class="menu">
                        <li><a href="/help">Help</a></li>
                    </ul>
                </div>
                <!-- End column three //-->

                <!-- Start column four //-->
                <div class="govuk-grid-column-one-quarter">
                    <h2 class="govuk-heading-m govuk-!-margin-0">Reference data</h2>
                    <ul class="menu">
                        <li><a href="/rules_of_origin_schemes/">Rules of origin schemes</a></li>
                        <li><a href="/measure_types/">Measure types</a></li>
                        <li><a href="/measure_type_series/">Measure type series</a></li>
                        <li><a href="/certificate_types/">Certificate types</a></li>
                        <li><a href="/additional_code_types/">Additional code types</a></li>
                        <li><a href="/footnote_types/">Footnote types</a></li>
                    </ul>
                    <h2 class="govuk-heading-m govuk-!-margin-0">Read-only reference data</h2>
                    <p class="govuk-body-xs">These data sets may not be modified, as they are critical
                        to the functioning of the downstream systems.
                    </p>
                    <ul class="menu">
                        <li><a href="/regulation_groups/">Regulation groups</a></li>
                        <li><a href="/measure_actions/">Measure actions</a></li>
                        <li><a href="/measure_condition_codes/">Measure condition codes</a></li>
                        <li><a href="/measurement_units/">Measurement units</a></li>
                        <li><a href="/measurement_unit_qualifiers/">Measurement qualifier units</a></li>
                        <li><a href="/duty_expressions/">Duty expressions</a></li>
                    </ul>

                </div>
                <!-- End column four //-->
            </div>

        </main>
    </div>
    <?php
    require("includes/footer.php");
    ?>

</body>

</html>