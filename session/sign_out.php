<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("session");
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $application->session->sign_out();
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
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Sign out</h1>
                    <form action="" method="post">
                    <!-- End main title //-->
                    <?php
                    new inset_control(
                        $text = "When you sign out, if you have an incomplete workbasket, this will be available again for your next session.",
                    );
                    new button_cluster_control();
                    
                    ?>
                    </form>
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