<?php
class conditional_date_picker_control
{
    // Class properties and methods go here
    public $label = "";
    public $label_style = "";
    public $hint_text = "";
    public $control_name = "";
    public $control_style = "";
    public $default = "";
    public $required = "";
    public $day = "";
    public $month = "";
    public $year = "";
    public $suppress_control = false;

    public function __construct($label, $label_style, $hint_text, $control_name, $control_scope, $default, $required, $custom_errors = "")
    {
        $this->label = $label;
        $this->label_style = $label_style;
        $this->hint_text = $hint_text;
        $this->control_name = $control_name;
        $this->control_scope = $control_scope;
        $this->default = $default;
        $this->required = to_required_string($required);
        $this->error_key = $control_name;
        if ($custom_errors != "") {
            $this->error_key .= "|" . $custom_errors;
        }
        $this->suppress_control = false;
        $this->display();
    }

    private function display()
    {
        global $error_handler, $application;

        if ($this->control_scope != "") {
            if (strpos($this->control_scope, $application->mode) === false) {
                $this->suppress_control = true;
            }
        }
        if (!$this->suppress_control) {
            $this->hint_name = $this->control_name . "_hint";
            if (trim($this->default) != "") {
                $date = date_parse_from_format('Y-m-d h:i:s', $this->default);
                $this->day = str_pad($date["day"], 2, "0", STR_PAD_LEFT);
                $this->month = str_pad($date["month"], 2, "0", STR_PAD_LEFT);
                $this->year = $date["year"];
            }
?>


            <!-- Start date input <?= $this->control_name ?> //-->
            <div id="heading_<?= $this->control_name ?>" class="govuk-form-group <?= $error_handler->get_error($this->error_key); ?>">
                <fieldset class="govuk-fieldset" role="group" aria-describedby="<?= $this->hint_name ?>">
                    <legend class="govuk-fieldset__legend <?= $this->label_style ?>">
                        <h1 class="govuk-fieldset__heading"><?= $this->label ?></h1>
                    </legend>
                    <span id="<?= $this->hint_name ?>" class="govuk-hint"><?= $this->hint_text ?></span>
                    <?= $error_handler->display_error_message($this->error_key); ?>


                    <div class="govuk-form-group">
                        <fieldset class="govuk-fieldset">
                            <div class="govuk-radios">
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input conditional_date_entry_fields_off" id="where-do-you-live" name="where-do-you-live" type="radio" value="england">
                                    <label class="govuk-label govuk-radios__label" for="where-do-you-live">
                                        Use the measures' start dates for change
                                    </label>
                                </div>
                                <div class="govuk-radios__item">
                                    <input class="govuk-radios__input conditional_date_entry_fields_on" id="where-do-you-live-2" name="where-do-you-live" type="radio" value="scotland">
                                    <label class="govuk-label govuk-radios__label" for="where-do-you-live-2">
                                        Enter specific date  
                                    </label>
                                    
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="govuk-date-input conditional_date_entry_fields">
                        <div class="govuk-date-input__item">
                            <div class="govuk-form-group">
                                <label class="govuk-label govuk-date-input__label" for="<?= $this->control_name ?>_day">Day</label>
                                <input value="<?= $this->day ?>" <?= $this->required ?> class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="<?= $this->control_name ?>_day" name="<?= $this->control_name ?>_day" type="text" pattern="[0-9]{1,2}">
                            </div>
                        </div>
                        <div class="govuk-date-input__item">
                            <div class="govuk-form-group">
                                <label class="govuk-label govuk-date-input__label" for="<?= $this->control_name ?>_month">Month</label>
                                <input value="<?= $this->month ?>" <?= $this->required ?> class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="<?= $this->control_name ?>_month" name="<?= $this->control_name ?>_month" type="text" pattern="[0-9]{1,2}">
                            </div>
                        </div>
                        <div class="govuk-date-input__item">
                            <div class="govuk-form-group">
                                <label class="govuk-label govuk-date-input__label" for="<?= $this->control_name ?>_year">Year</label>
                                <input value="<?= $this->year ?>"" <?= $this->required ?> class=" govuk-input govuk-date-input__input govuk-input--width-4" id="<?= $this->control_name ?>_year" name="<?= $this->control_name ?>_year" type="text" pattern="[0-9]{2,4}">
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!-- End date input <?= $this->control_name ?> //-->
<?php
        }
    }
}
?>