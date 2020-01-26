<?php
class duty_expression
{
    // Class properties and methods go here
    public $action_code = "";
    public $description = "";
    public $validity_start_date = "";
    public $validity_end_date = "";
    public $duty_amount_applicability_code = "";
    public $measurement_unit_applicability_code = "";
    public $monetary_unit_applicability_code = "";
    public $duty_amount_applicability_code_description = "";
    public $measurement_unit_applicability_code_description = "";
    public $monetary_unit_applicability_code_description = "";

    public function __construct()
    {
        $this->duty_amount_applicability_codes = array();
        array_push($this->duty_amount_applicability_codes, new simple_object("0", "Permitted", ""));
        array_push($this->duty_amount_applicability_codes, new simple_object("1", "Mandatory", ""));
        array_push($this->duty_amount_applicability_codes, new simple_object("2", "Not permitted", ""));

        $this->measurement_unit_applicability_codes = array();
        array_push($this->measurement_unit_applicability_codes, new simple_object("0", "Permitted", ""));
        array_push($this->measurement_unit_applicability_codes, new simple_object("1", "Mandatory", ""));
        array_push($this->measurement_unit_applicability_codes, new simple_object("2", "Not permitted", ""));

        $this->monetary_unit_applicability_codes = array();
        array_push($this->monetary_unit_applicability_codes, new simple_object("0", "Permitted", ""));
        array_push($this->monetary_unit_applicability_codes, new simple_object("1", "Mandatory", ""));
        array_push($this->monetary_unit_applicability_codes, new simple_object("2", "Not permitted", ""));
    }

    public function get_descriptive_fields()
    {
        // duty_amount_applicability_codes
        foreach ($this->duty_amount_applicability_codes as $item) {
            if ($item->id == $this->duty_amount_applicability_code) {
                $this->duty_amount_applicability_code_description = $item->string;
            }
        }
        // measurement_unit_applicability_codes
        foreach ($this->measurement_unit_applicability_codes as $item) {
            if ($item->id == $this->measurement_unit_applicability_code) {
                $this->measurement_unit_applicability_code_description = $item->string;
            }
        }
        // monetary_unit_applicability_codes
        foreach ($this->monetary_unit_applicability_codes as $item) {
            if ($item->id == $this->monetary_unit_applicability_code) {
                $this->monetary_unit_applicability_code_description = $item->string;
            }
        }
    }
}
