<?php
class workbasket_control
{
    // Class properties and methods go here
    public $path = "";

    public function __construct()
    {
        global $application;
        if ($application->session->workbasket == null) {
            return;
        } else {
            $this->display();
        }
    }

    private function display()
    {
        global $application;
?>

        <!-- Start details component //-->
        <details class="govuk-details" data-module="govuk-details">
            <summary class="govuk-details__summary">
                <span class="govuk-details__summary-text">
                    Your workbasket
                </span>
            </summary>
            <div class="govuk-details__text">
                <p>Your data edits will be added to the existing workbasket <strong><?= $application->session->workbasket->title ?></strong></p>
                <?php
                $sfn = $_SERVER["SCRIPT_FILENAME"];
                $sn = $_SERVER["SCRIPT_NAME"];
                $temp = str_replace($sn, "", $sfn);
                $path = $temp . "/includes/workbasket.php";
                require($path);
                ?>

            </div>
        </details>
        <!-- End details component //-->
<?php
    }
}
