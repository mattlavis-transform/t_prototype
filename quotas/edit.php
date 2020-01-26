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
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/quotas">Quotas</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Edit quota 091243</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Edit quota 091243</h1>
                    <!-- End main title //-->
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-four-fifths">



                    <div class="govuk-tabs" data-module="govuk-tabs">
                        <h2 class="govuk-tabs__title">
                            Contents
                        </h2>
                        <ul class="govuk-tabs__list">
                            <li class="govuk-tabs__list-item govuk-tabs__list-item--selected">
                                <a class="govuk-tabs__tab" href="#details">Quota details</a>
                            </li>
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#origins">Origins</a>
                            </li>
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#commodities">Commodities</a>
                            </li>
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#definitions">Definitions</a>
                            </li>
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#measures">Measures</a>
                            </li>
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#blocks_suspensions">Blocks, suspensions, associations</a>
                            </li>
                            <!--
                            <li class="govuk-tabs__list-item">
                                <a class="govuk-tabs__tab" href="#associations">Associations</a>
                            </li>
                            //-->
                        </ul>

                        <section class="govuk-tabs__panel" id="details">
                            <?php require("tabs/tab01_details.php"); ?>
                        </section>

                        <section class="govuk-tabs__panel" id="origins">
                            <?php require("tabs/tab02_origins.php"); ?>
                        </section>

                        <section class="govuk-tabs__panel" id="commodities">
                            <?php require("tabs/tab03_commodities.php"); ?>
                        </section>

                        <section class="govuk-tabs__panel" id="definitions">
                            <?php require("tabs/tab04_definitions.php"); ?>
                        </section>

                        <section class="govuk-tabs__panel" id="measures">
                            <?php require("tabs/tab05_measures.php"); ?>
                        </section>

                        <section class="govuk-tabs__panel" id="blocks_suspensions">
                            <?php require("tabs/tab06_blocks_suspensions_associations.php"); ?>
                        </section>
                        <!--
                        <section class="govuk-tabs__panel" id="associations">
                            <?php require("tabs/tab07_associations.php"); ?>
                        </section>
                        //-->

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