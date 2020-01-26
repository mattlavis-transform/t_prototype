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


        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Heading goes here</h1>
                    <!-- End main title //-->
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    

                </div>
            </div>

        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>