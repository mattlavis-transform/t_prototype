<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$error_handler = new error_handler;
$application->init("workbaskets");
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
        $id = get_querystring("id");
        $workbasket = $application->session->get_workbasket_for_withdrawal($id);
        ?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Withdraw workbasket</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Workbasket "<?= $workbasket->title ?>"</h1>
                    <!-- End main title //-->
                    <?php
                    //require "../includes/workbasket.php";
                    ?>




                    <form action="actions.php" method="get">

                        <?php
                        new radio_control(
                            $label = "Are you sure you want to withdraw this workbasket?",
                            $label_style = "govuk-fieldset__legend--m",
                            $hint_text = "All activities that have been added to this workbasket will be removed, and this action cannot be undone.",
                            $control_name = "withdraw_workbasket",
                            $dataset = $application->get_yes_no(),
                            $selected = null,
                            $radio_control_style = "stacked",
                            $required = true,
                            $disabled_on_edit = false
                        );

                        ?>
                        <?php
                        new hidden_control("workbasket_id", $workbasket->workbasket_id);
                        new hidden_control("action", "withdraw");
                        $btn = new button_control("Continue", "withdraw", "primary");
                        $btn = new button_control("Cancel", "cancel", "text", "", "/");
                        ?>
                    </form>
                </div>
            </div>
        </main>

    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>