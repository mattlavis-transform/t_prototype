<?php
class character_count_control
{
    // Class properties and methods go here
    public $label = "";
    public $label_style = "";
    public $hint_text = "";
    public $control_name = "";
    public $control_scope = "";
    public $default = "";
    public $size = "";
    public $maxlength = "";
    public $pattern = "";
    public $required = "";
    public $error_key = "";

    public function __construct($label, $label_style, $hint_text, $control_name, $rows, $maxlength, $required, $default, $pattern, $control_scope = "", $custom_errors = "")
    {
        $this->label = $label;
        $this->label_style = $label_style;
        $this->hint_text = $hint_text;
        $this->control_name = $control_name;
        $this->control_scope = $control_scope;
        $this->error_key = $control_name;
        if ($custom_errors != "") {
            $this->error_key .= "|" . $custom_errors;
        }
        $this->rows = $rows;
        $this->maxlength = $maxlength;
        if ($this->maxlength != null) {
            $this->maxlength_string = ' maxlength="' . $this->maxlength . '"';
            $this->data_maxlength_string = ' data-maxlength="' . $this->maxlength . '"';
        } else {
            $this->maxlength_string = "";
            $this->data_maxlength_string = "";
        }

        $this->required = to_required_string($required);
        $this->default = $default;
        $this->suppress_control = false;

        // Get pattern string
        $this->pattern = $pattern;

        $this->display();
    }

    private function display()
    {
        global $error_handler, $application;
        $this->hint_name = $this->control_name . "_hint";
        $this->hint_name2 = $this->control_name . "-info";
        if ($this->control_scope != "") {
            if (strpos($this->control_scope, $application->mode) === false) {
                $this->suppress_control = true;
            }
        }
        if (!$this->suppress_control) {
            if ($this->maxlength == null) {
?>
                <!-- Start text area control <?= $this->control_name ?> //-->
                <div id="heading_<?= $this->control_name ?>" class="govuk-form-group">
                    <label class="<?= $this->label_style ?>" for="<?= $this->control_name ?>"><?= $this->label ?></label>
                    <span id="<?= $this->hint_name ?>" class="govuk-hint"><?= $this->hint_text ?></span>
                    <textarea pattern="<?= $this->pattern?>" <?= $this->required ?> maxlength="<?= $this->maxlength ?>" class="govuk-textarea" id="<?= $this->control_name ?>" name="<?= $this->control_name ?>" rows="<?= $this->rows ?>" aria-describedby="<?= $this->hint_name ?>"><?= $this->default ?></textarea>
                </div>
                <!-- End text area control <?= $this->control_name ?> //-->

            <?php
            } else {
            ?>
                <!-- Start character count control <?= $this->control_name ?> //-->
                <div id="heading_<?= $this->control_name ?>" class="govuk-character-count <?= $error_handler->get_error($this->error_key); ?>" data-module="govuk-character-count" <?= $this->data_maxlength_string ?>>
                    <div class="govuk-form-group <?= $error_handler->get_error($this->error_key); ?>">
                        <label class="<?= $this->label_style ?>" for="<?= $this->control_name ?>"><?= $this->label ?></label>
                        <span id="<?= $this->hint_name ?>" class="govuk-hint"><?= $this->hint_text ?></span>
                        <?= $error_handler->display_error_message($this->error_key); ?>
                        <textarea pattern="<?= $this->pattern?>" <?= $this->required ?> <?= $this->maxlength_string ?> class="govuk-textarea govuk-js-character-count" id="<?= $this->control_name ?>" name="<?= $this->control_name ?>" rows="<?= $this->rows ?>" aria-describedby="<?= $this->hint_name2 ?> <?= $this->hint_name ?>"><?= $this->default ?></textarea>
                    </div>

                    <span id="<?= $this->hint_name2 ?>" class="govuk-hint govuk-character-count__message" aria-live="polite">
                        You can enter up to <?= $this->maxlength ?> characters
                    </span>
                </div>
                <!-- End character count control <?= $this->control_name ?> //-->
            <?php
            }
            ?>


<?php
        }
    }
}
?>