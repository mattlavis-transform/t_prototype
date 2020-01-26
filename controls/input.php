<?php
class input_control
{
    // Class properties and methods go here
    public $label = "";
    public $label_style = "";
    public $hint_text = "";
    public $control_name = "";
    public $control_style = "";
    public $default = "";
    public $size = "";
    public $maxlength = "";
    public $pattern = "";
    public $required = "";
    public $error_key = "";
    public $disabled = "";
    public $disabled_text = "";
    public $group_class = "";

    public function __construct($label, $label_style, $hint_text, $control_name, $control_style, $size, $maxlength, $pattern, $required, $default, $default_on_insert, $disabled_on_edit, $custom_errors = "", $group_class = "")
    {
        global $application;
        $this->label = $label;
        $this->label_style = $label_style;
        $this->hint_text = $hint_text;
        $this->control_name = $control_name;
        $this->error_key = $control_name;
        $this->group_class = $group_class;
        if ($custom_errors != "") {
            $this->error_key .= "|" . $custom_errors;
        }

        $this->control_style = $control_style;
        $this->size = $size;
        $this->maxlength = $maxlength;
        if ($pattern == "") {
            $this->pattern = "";
        } else {
            $this->pattern = " pattern = '$pattern'";
        }

        $this->required = to_required_string($required);

        // Get the default value
        if ($application->mode == "insert") {
            $this->default = $default_on_insert;
        } else {
            $this->default = $default;
        }
        $this->disabled_on_edit = $disabled_on_edit;
        if ($this->disabled_on_edit) {
            if ($application->mode == "update") {
                $this->disabled = true;
            } else {
                $this->disabled = false;
            }
        } else {
            $this->disabled = false;
        }

        if ($this->disabled == true) {
            $this->disabled_text = " disabled";
            $this->hint_text .= " The primary key is not editable.";
        }

        $this->display();
    }

    private function display()
    {
        global $error_handler;
        $this->hint_name = $this->control_name . "_hint";
?>

        <!-- Start text input <?= $this->control_name ?> //-->
        <div class="govuk-form-group <?= $this->group_class ?> <?= $error_handler->get_error($this->error_key); ?>">
            <label id="heading_<?= $this->control_name ?>" class="<?= $this->label_style ?>" for="<?= $this->control_name ?>"><?= $this->label ?></label>
            <span id="<?= $this->hint_name ?>" class="govuk-hint"><?= $this->hint_text ?></span>
            <?= $error_handler->display_error_message($this->error_key); ?>
            <input <?= $this->disabled_text ?> value="<?= $this->default ?>" class="govuk-input <?= $this->control_style ?>" id="<?= $this->control_name ?>" name="<?= $this->control_name ?>" type="text" size="<?= $this->size ?>" maxlength="<?= $this->maxlength ?>" <?= $this->required ?> <?= $this->pattern ?> />
        </div>
        <!-- End text input <?= $this->control_name ?> //-->
<?php
        if ($this->disabled) {
            echo ('<input type="hidden" name="' . $this->control_name . '" value="' . $this->default . '" />');
        }
    }
}
?>