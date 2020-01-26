<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("workbaskets");
$error_handler = new error_handler();
$submitted = get_formvar("submitted");
if ($submitted) {
    //h1 ("su");
}
$other_users = $application->get_other_users();

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
                    <h1 class="govuk-heading-xl">Workbasket "<?=$application->session->workbasket->title?>"</h1>
                    <!-- End main title //-->
<?php
    require "../includes/workbasket.php";
?>




<form action="" method="post">

<?php
new radio_control(
                            $label = "Which Tariff Manager would you like to progress this workbasket?",
                            $label_style = "govuk-fieldset__legend--m",
                            $hint_text = "Please select the tariff manager to whom to assign the workbasket.",
                            $control_name = "user",
                            $dataset = $other_users,
                            $selected = null,
                            $radio_control_style = "stacked",
                            $required = true
                        );
?>
                    <?php
                    $btn = new button_control("Reassign workbasket", "reassign", "primary");
                    $btn = new button_control("Cancel", "cancel", "link", "", "/");
                    ?>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>