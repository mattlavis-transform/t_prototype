<?php
class textarea_control
{
    // Class properties and methods go here
    public $label = "";
    public $label_style = "";
    public $hint_text = "";
    public $control_name = "";
    public $default = "";
    public $size = "";
    public $maxlength = "";
    public $pattern = "";
    public $required = "";
    public $error_key = "";


    public function __construct($label, $label_style, $hint_text, $control_name, $rows, $maxlength, $required, $default, $error_key)
    {
        $this->label  = $label;
        $this->label_style  = $label_style;
        $this->hint_text  = $hint_text;
        $this->control_name  = $control_name;
        $this->rows  = $rows;
        $this->maxlength  = $maxlength;
        $this->required  = to_required_string($required);
        $this->default  = $default;
        $this->error_key  = $error_key;

        $this->display();
    }

    private function display()
    {
        global $error_handler;
        $this->hint_name = $this->control_name . "_hint";
        $this->hint_name2 = $this->control_name . "-info";
?>
        <!-- Start text area control <?= $this->control_name ?> //-->
        <div id="heading_<?= $this->control_name ?>" class="govuk-form-group">
            <label class="<?= $this->label_style ?>" for="<?= $this->control_name ?>"><?= $this->label ?></label>
            <span id="<?= $this->hint_name ?>" class="govuk-hint"><?= $this->hint_text ?></span>
            <textarea <?= $this->required ?> maxlength="<?= $this->maxlength ?>" class="govuk-textarea" id="<?= $this->control_name ?>" name="<?= $this->control_name ?>" rows="<?= $this->rows ?>" aria-describedby="<?= $this->hint_name ?>"><?= $this->default ?></textarea>
        </div>
        <!-- End text area control <?= $this->control_name ?> //-->


<?php
    }
}
?>