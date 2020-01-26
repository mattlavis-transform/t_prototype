<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("workbaskets");
$error_handler = new error_handler();
$submitted = get_formvar("submitted");

if ($submitted) {
    if (isset($_POST["reassign_workbasket"])) {
        $url = "reassign.html";
        header("Location: " . $url);
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



        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Workbasket &quot;<?= $application->session->workbasket->title ?>&quot;</h1>
                    <!-- End main title //-->
                    <?php
                    require "../includes/workbasket.php";
                    ?>


                    <table class="govuk-table">
                        <caption class="govuk-table__caption--m">Workbasket content</caption>
                        <thead class="govuk-table__head">
                            <tr class="govuk-table__row">
                                <th scope="col" class="govuk-table__header" style="width:25%">Created</th>
                                <th scope="col" class="govuk-table__header" style="width:65%">Item</th>
                                <th scope="col" class="govuk-table__header r" style="width:10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                            <tr class="govuk-table__row">
                                <td class="govuk-table__cell">01 Jan 2020 09:09</td>
                                <td class="govuk-table__cell">New regulation R1010101</td>
                                <td class="govuk-table__cell r"><a class='govuk-link' href="">View / edit</a></td>
                            </tr>
                        </tbody>
                    </table>

                    
                    <form method="post">

                        <?php
                        //var_dump ($_SESSION);
                        new hidden_control("workbasket_id", $application->session->workbasket->id);
                        new button_control("Submit workbasket for approval", "submit_workbasket", "primary", true);
                        new button_control("Reassign workbasket", "reassign_workbasket", "primary", false);
                        new button_control("Cancel", "cancel", "text", false, "/");
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