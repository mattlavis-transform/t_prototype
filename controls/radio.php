<?php
class radio_control
{
    // Class properties and methods go here
    public $label = "";
    public $label_style = "";
    public $hint_text = "";
    public $control_name = "";
    public $control_style = "";
    public $dataset = Null;
    public $selected = "";
    public $radio_control_style = "";
    public $disabled_on_edit = "";
    public $disabled_text = "";
    public $show_hint = false;

    public function __construct($label, $label_style, $hint_text, $control_name, $dataset, $selected, $radio_control_style, $required, $disabled_on_edit, $custom_errors = "")
    {
        global $application;

        $this->label = $label;
        $this->label_style = $label_style;
        $this->hint_text = $hint_text;
        $this->control_name = $control_name;
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
        }

        $this->error_key = $control_name;
        if ($custom_errors != "") {
            $this->error_key .= "|" . $custom_errors;
        }
        $this->dataset = $dataset;
        $this->selected = $selected;
        $this->radio_control_style = $radio_control_style;
        $this->required  = to_required_string($required);
        $this->display();
    }

    private function display()
    {
        global $error_handler;
        $this->hint_name = $this->control_name . "_hint";
        if ($this->radio_control_style == "inline") {
            $radio_control_style_style = " govuk-radios--inline";
        } else {
            $radio_control_style_style = "";
        }
        if ($this->radio_control_style == "stacked_detail") {
            $label_class = "govuk-label govuk-radios__label govuk-label--s";
            $this->show_hint = true;
        } else {
            $label_class = "govuk-label govuk-radios__label";
        }
        //prend ($this->error_key);
?>
        <!-- Start radio control <?= $this->control_name ?> //-->
        <div id="heading_<?= $this->control_name ?>" class="govuk-form-group <?= $error_handler->get_error($this->error_key); ?>">
            <fieldset class="govuk-fieldset" aria-describedby="<?= $this->hint_name ?>">
                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                    <h1 class="govuk-fieldset__heading"><?= $this->label ?></h1>
                </legend>
                <span id="<?= $this->hint_name ?>" class="govuk-hint"><?= $this->hint_text ?></span>
                <?= $error_handler->display_error_message($this->error_key); ?>
                <div class="govuk-radios <?= $radio_control_style_style ?>">
                    <?php
                    foreach ($this->dataset as $dataitem) {
                        $control_id = $this->control_name . "_" . $dataitem->id;
                        if (strval($dataitem->id) == strval($this->selected)) {
                            $checked_string = ' checked="checked"';
                        } else {
                            $checked_string = "";
                        }
                    ?>
                        <div class="govuk-radios__item">
                            <input <?= $this->disabled_text ?> <?= $this->required ?> <?= $checked_string ?> class="govuk-radios__input" id="<?= $control_id ?>" name="<?= $this->control_name ?>" type="radio" value="<?= $dataitem->id ?>">
                            <label class="<?= $label_class ?>" for="<?= $control_id ?>"><?= $dataitem->string ?></label>
                            <?php
                            if ($this->show_hint) {
                            ?>
                                <span class="govuk-hint govuk-radios__hint"><?= $dataitem->detail ?></span>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </fieldset>
        </div>
        <!-- End radio control <?= $this->control_name ?> //-->
<?php
    }
}
?>