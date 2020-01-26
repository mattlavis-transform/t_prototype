<?php
class button_cluster_control
{
    // Class properties and methods go here
    public $heading_text = "";
    public $info_text = "";
    public $submit_button_text = "Submit";
    public $cancel_button_text = "Cancel";
    public $cancel_button_href = "javascript:history.back();";
    public $save_progress_button_text = "";

    public function __construct($override_text = "")
    {
        global $application;
        if (!isset($application->tariff_object)) {
            return;
        }
        if (!isset($application->data[$application->tariff_object]["config"])) {
            return;
        }
        $config = $application->data[$application->tariff_object]["config"];

        // Get button cluster heading
        if (isset($config["buttons"]["heading_text"])) {
            $this->heading_text = $config["buttons"]["heading_text"];
        }

        // Get button info text
        if (isset($config["buttons"]["info_text"])) {
            $this->info_text = $config["buttons"]["info_text"];
        }

        // Get submit button caption
        if ($override_text != "") {
            $this->submit_button_text = $override_text;
        } else {
            if ($application->mode == "insert") {
                if (isset($config["buttons"]["submit_button_text_create"])) {
                    $this->submit_button_text = $config["buttons"]["submit_button_text_create"];
                }
            } else {
                if (isset($config["buttons"]["submit_button_text_edit"])) {
                    $this->submit_button_text = $config["buttons"]["submit_button_text_edit"];
                }
            }
        }

        // Get cancel button text
        if (isset($config["buttons"]["cancel_button_text"])) {
            $this->cancel_button_text = $config["buttons"]["cancel_button_text"];
        }

        // Get cancel button href
        if (isset($config["buttons"]["cancel_button_href"])) {
            $this->cancel_button_href = $config["buttons"]["cancel_button_href"];
        }

        // Get save progress button text
        if (isset($config["buttons"]["save_progress_button_text"])) {
            $this->save_progress_button_text = $config["buttons"]["save_progress_button_text"];
        }

        $this->display();
    }

    private function display()
    {
?>
        <!-- Start button //-->
        <input type="hidden" name="submitted" id="submitted" value="1" />
        <div class="govuk-form-group">
            <?php
            if ($this->heading_text != "") {
            ?>
                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                    <h1 class="govuk-fieldset__heading"><?= $this->heading_text ?></h1>
                </legend>
                <p class="govuk-body"><?= $this->info_text ?></p>
            <?php
            }
            ?>
            <button class="govuk-button" data-module="govuk-button"><?= $this->submit_button_text ?></button>
            <?php
            if ($this->save_progress_button_text != "") {
            ?>
            <button class="govuk-button" data-module="govuk-button"><?= $this->save_progress_button_text ?></button>
            <?php
            }
            ?>
            <?php
            if ($this->cancel_button_text != "") {
            ?>
                <a href="<?= $this->cancel_button_href ?>" class="textual_button govuk-link"><?= $this->cancel_button_text ?></a>
            <?php
            }
            ?>
        </div>
        <!-- End button //-->
<?php
    }
}
?>