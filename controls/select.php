<?php
class select_control
{
    // Class properties and methods go here
    public $label = "";
    public $label_style = "";
    public $hint_text = "";
    public $control_name = "";
    public $dataset = Null;
    public $default_value = "";
    public $default_string = "";
    public $selected = "";
    public $disabled = "";
    public $disabled_text = "";
    public $group_by = "";
    public $group_class = "";
    public $control_class = "";

    public function __construct($label, $label_style, $hint_text, $control_name, $dataset, $default_value, $default_string, $default_on_insert, $selected, $required, $disabled_on_edit, $group_by = "", $custom_errors = "", $group_class = "", $control_class = "")
    {
        global $application;

        $this->control_name = $control_name;
        $this->error_key = $control_name;
        if ($custom_errors != "") {
            $this->error_key .= "|" . $custom_errors;
        }
        $this->label = $label;
        $this->label_style = $label_style;
        $this->hint_text = $hint_text;
        $this->dataset = $dataset;
        $this->default_value = $default_value;
        $this->default_string = $default_string;
        $this->group_class = $group_class;
        $this->control_class = $control_class;

        // Get the default value
        if ($application->mode == "insert") {
            $this->selected = $default_on_insert;
        } else {
            $this->selected = $selected;
        }

        $this->group_by = $group_by;
        if ($this->group_by == "") {
            $this->default_group = "";
        } else {
            $this->default_group = ' group="unspecified"';
        }
        $this->required = to_required_string($required);

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
        $last_opgroup = "random_text";
?>
        <!-- Start select control <?= $this->control_name ?> //-->
        <div id="heading_<?= $this->control_name ?>" class="govuk-form-group <?= $this->group_class ?> <?= $error_handler->get_error($this->error_key); ?>">
            <label class="<?= $this->label_style ?>" for="<?= $this->control_name ?>"><?= $this->label ?></label>
            <span id="<?= $this->hint_name ?>" class="govuk-hint"><?= $this->hint_text ?></span>
            <?= $error_handler->display_error_message($this->error_key); ?>
            <select <?= $this->disabled_text ?> <?= $this->required ?> class="govuk-select <?= $this->control_class ?>" id="<?= $this->control_name ?>" name="<?= $this->control_name ?>">
                <option <?= $this->default_group ?> value="<?= $this->default_value ?>"><?= $this->default_string ?></option>
                <?php
                $count = 0;
                foreach ($this->dataset as $dataitem) {

                    if (strval($dataitem->id) == strval($this->selected)) {
                        $this->selected_text = " selected";
                    } else {
                        $this->selected_text = " ";
                    }
                    if ($this->group_by != "") {
                        if ($dataitem->optgroup != $last_opgroup) {
                            if ($count != 0) {
                                echo ('</optgroup>');
                            }
                            echo ('<optgroup label="' . $dataitem->optgroup . '">');
                        }
                    }
                    echo ('<option group="' . $dataitem->optgroup . '" ' . $this->selected_text . ' value="' . $dataitem->id . '">' . $dataitem->string . '</option>');
                    $last_opgroup = $dataitem->optgroup;
                    $count += 1;
                }
                if ($this->group_by != "") {
                    echo ('</optgroup>');
                }
                ?>
            </select>
        </div>
        <!-- End select control <?= $this->control_name ?> //-->
<?php
    }
}
?>