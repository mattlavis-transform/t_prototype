<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler;
$application->init("session");
$submitted = intval(get_formvar("submitted"));
if ($submitted == 1) {
    $application->session->sign_in();
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
                    <h1 class="govuk-heading-xl">Sign in to the Tariff Application</h1>
                    <form action="" method="post">
                    <!-- End main title //-->
                    <?php
                    new input_control(
                        $label = "User ID",
                        $label_style = "govuk-label--m",
                        $hint_text = "",
                        $control_name = "user_id",
                        $control_style = "govuk-input--width-10",
                        $size = 50,
                        $maxlength = 50,
                        $pattern = "",
                        $required = "required",
                        $default = "",
                        $disabled = false,
                        $custom_errors = ""
                    );

                    new input_control(
                        $label = "Email address",
                        $label_style = "govuk-label--m",
                        $hint_text = "",
                        $control_name = "email",
                        $control_style = "govuk-input--width-20",
                        $size = 100,
                        $maxlength = 100,
                        $pattern = "",
                        $required = "required",
                        $default = "",
                        $disabled = false,
                        $custom_errors = ""
                    );

                    new input_control(
                        $label = "First name",
                        $label_style = "govuk-label--m",
                        $hint_text = "",
                        $control_name = "first_name",
                        $control_style = "govuk-input--width-10",
                        $size = 50,
                        $maxlength = 50,
                        $pattern = "",
                        $required = "",
                        $default = "",
                        $disabled = false,
                        $custom_errors = ""
                    );

                    new input_control(
                        $label = "Last name",
                        $label_style = "govuk-label--m",
                        $hint_text = "",
                        $control_name = "last_name",
                        $control_style = "govuk-input--width-10",
                        $size = 50,
                        $maxlength = 50,
                        $pattern = "",
                        $required = false,
                        $default = "",
                        $disabled = false,
                        $custom_errors = ""
                    );

                    $btn = new button_cluster_control("Sign in to the Tariff Application");
                    $btn->submit_button_text = "Sign in to the Tariff Application";
                    
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