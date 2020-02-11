<?php
class duty
{
    // Class properties and methods go here
    public $duty_expression_id                = null;
    public $duty_amount                      = null;
    public $monetary_unit_code                = null;
    public $measurement_unit_code            = null;
    public $measurement_unit_qualifier_code  = null;

    public function __construct()
    {
        $this->validity                = 0;
        $this->measure_sid                = 0;
        $this->geographical_area_id        = "";
        $this->additional_code_id        = "";
        $this->additional_code_type_id  = "";
        $this->entry_price_string       = "";
        $this->entry_price_applied      = false;
        $this->perceived_value          = 0;
        $this->used = false;
    }

    public function validate($i)
    {
        global $conn;
        $valid = 0;

        // Check on empty
        if (($this->duty_expression_id == "") or (($this->duty_amount == "") && ($this->measurement_unit_code == ""))) {
            $valid = 0;
            return;
        }

        // Check that the duty expression is valid
        $sql = "select duty_expression_id from duty_expressions where duty_expression_id = $1";
        $stmt = "validate_duty_expression_id_" . $this->duty_expression_id . $i;
        pg_prepare($conn, $stmt, $sql);
        $result = pg_execute($conn, $stmt, array($this->duty_expression_id));
        if ($result) {
            $row_count = pg_num_rows($result);
            if ($row_count > 0) {
                $valid += 1;
            }
        }

        // Check that the measurement unit is valid
        if (is_null($this->measurement_unit_code)) {
            $valid += 2;
        } else {
            $sql = "select measurement_unit_code from measurement_units where measurement_unit_code = $1";
            $stmt = "validate_duty_measurement_unit_code_" . $this->measurement_unit_code . $i;
            pg_prepare($conn, $stmt, $sql);
            $result = pg_execute($conn, $stmt, array($this->measurement_unit_code));
            if ($result) {
                $row_count = pg_num_rows($result);
                if ($row_count > 0) {
                    $valid += 2;
                }
            }
        }

        // Check that the measurement qualifier unit is valid
        if (is_null($this->measurement_unit_qualifier_code)) {
            $valid += 12;
        } else {
            $sql = "select measurement_unit_qualifier_code from measurement_unit_qualifiers where measurement_unit_qualifier_code = $1";
            $stmt = "validate_duty_measurement_unit_qualifier_code_" . $this->measurement_unit_qualifier_code . $i;
            pg_prepare($conn, $stmt, $sql);
            $result = pg_execute($conn, $stmt, array($this->measurement_unit_qualifier_code));
            if ($result) {
                $row_count = pg_num_rows($result);
                if ($row_count > 0) {
                    $valid += 4;
                }
            }

            // Check the combo is okay
            $sql = "select * from measurements m where measurement_unit_code = $1 and measurement_unit_qualifier_code = $2";
            $stmt = "validate_duty_measurement_" . $this->measurement_unit_code . "_" . $this->measurement_unit_qualifier_code . $i;
            pg_prepare($conn, $stmt, $sql);
            $result = pg_execute($conn, $stmt, array($this->measurement_unit_code, $this->measurement_unit_qualifier_code));
            if ($result) {
                $row_count = pg_num_rows($result);
                if ($row_count > 0) {
                    $valid += 8;
                }
            }
        }
        //h1($valid);
        $this->validity = $valid;

        return (true);
    }

    public function set($duty_expression_id)
    {
        $this->duty_expression_id = $duty_expression_id;
    }

    public function set_properties(
        $goods_nomenclature_item_id,
        $additional_code_type_id,
        $additional_code_id,
        $measure_type_id,
        $duty_expression_id,
        $duty_amount,
        $monetary_unit_code,
        $measurement_unit_code,
        $measurement_unit_qualifier_code,
        $measure_sid,
        $quota_order_number_id,
        $geographical_area_id,
        $validity_start_date,
        $validity_end_date
    ) {

        $this->goods_nomenclature_item_id       = $goods_nomenclature_item_id;
        $this->additional_code_type_id            = $additional_code_type_id;
        $this->additional_code_id                = $additional_code_id;
        $this->measure_type_id                    = $measure_type_id;
        $this->duty_expression_id                = $duty_expression_id;
        $this->duty_amount                      = $duty_amount;
        $this->monetary_unit_code                = $monetary_unit_code;
        $this->measurement_unit_code            = $measurement_unit_code;
        $this->measurement_unit_qualifier_code  = $measurement_unit_qualifier_code;
        $this->measure_sid                        = $measure_sid;
        $this->quota_order_number_id            = $quota_order_number_id;
        $this->geographical_area_id                = $geographical_area_id;
        $this->validity_start_date                = $validity_start_date;
        $this->validity_end_date                = $validity_end_date;

        $this->get_duty_string();
        #echo ("setting duty properties");
        #echo ("Duty string " . $this->duty_string);
    }

    public function get_duty_string($decimal_places = 3)
    {
        # This bit of code removes the Meursing data from the duty expressions
        # This will be artifically re-inserted from an external data source,
        # i.e. not from data that is stored in the database (check meursing_products.csv in /source folder)
        # Confirmed 24/01/19 by Daren Timson-Hunt and David Owen that this will not be needed on the Tariff
        # but should be used in the reference documents in order to back up the legislation

        #if $this->duty_expression_id in ('12', '13', '14', '21', '23', '25', '27', '29'):
        #	$this->duty_string = ""
        #	return

        $this->duty_string = "";
        $duty_amount = number_format($this->duty_amount, $decimal_places);

        switch ($this->duty_expression_id) {
            case "01":
                // Do stuff
                if ($this->monetary_unit_code == "") {
                    $this->duty_string .= $duty_amount . "%";
                    $this->perceived_value = 100000 * $duty_amount;
                } else {
                    $this->perceived_value = $duty_amount;
                    $this->duty_string .= $duty_amount . " " . $this->monetary_unit_code;
                    if ($this->measurement_unit_code != "") {
                        $this->duty_string .= " / " . $this->getMeasurementUnit();
                        if ($this->measurement_unit_qualifier_code != "") {
                            $this->duty_string .= " / " . $this->getQualifier();
                        }
                    }
                }
                break;
            case "04": // All three of these together
            case "19":
            case "20":
                // Do stuff
                if ($this->monetary_unit_code == "") {
                    $this->duty_string .= " + " . $duty_amount . "%";
                    $this->perceived_value = 100000 * $duty_amount;
                } else {
                    $this->perceived_value = $duty_amount;
                    $this->duty_string .= " + " . $duty_amount . " " . $this->monetary_unit_code;
                    if ($this->measurement_unit_code != "") {
                        $this->duty_string .= " / " . $this->getMeasurementUnit();
                        if ($this->measurement_unit_qualifier_code != "") {
                            $this->duty_string .= " / " . $this->getQualifier();
                        }
                    }
                }
                break;
            case "12":
                $this->duty_string .= " + AC";
                $this->perceived_value = 0;
                break;
            case "15":
                $this->perceived_value = 0;
                // Do stuff
                if ($this->monetary_unit_code == "") {
                    $this->duty_string .= "MIN " . $duty_amount . "%";
                } else {
                    $this->duty_string .= "MIN " . $duty_amount . " " . $this->monetary_unit_code;
                    if ($this->measurement_unit_code != "") {
                        $this->duty_string .= " / " . $this->getMeasurementUnit();
                        if ($this->measurement_unit_qualifier_code != "") {
                            $this->duty_string .= " / " . $this->getQualifier();
                        }
                    }
                }
                break;

            case "17":
            case "35":
                // Do stuff
                $this->perceived_value = 0;
                if ($this->monetary_unit_code == "") {
                    $this->duty_string .= "MAX " . $duty_amount . "%";
                } else {
                    $this->duty_string .= "MAX " . $duty_amount  . " " . $this->monetary_unit_code;
                    if ($this->measurement_unit_code != "") {
                        $this->duty_string .= " / " . $this->getMeasurementUnit();
                        if ($this->measurement_unit_qualifier_code != "") {
                            $this->duty_string .= " / " . $this->getQualifier();
                        }
                    }
                }
                break;
            case "21":
                $this->duty_string .= " + SD";
                $this->perceived_value = 0;
                break;
            case "27":
                $this->duty_string .= " + FD";
                $this->perceived_value = 0;
                break;
            case "25":
                $this->duty_string .= " + SD (reduced)";
                $this->perceived_value = 0;
                break;
            case "29":
                $this->duty_string .= " + FD (reduced)";
                $this->perceived_value = 0;
                break;
            case "14":
                $this->duty_string .= " + AC (reduced)";
                $this->perceived_value = 0;
                break;
        }
    }

    function getMeasurementUnit()
    {
        switch ($this->measurement_unit_code) {
            case "ASV":
                return ("% vol");
                break;
            case "NAR":
                return ("item");
                break;
            case "CCT":
                return ("ct/l");
                break;
            case "CEN":
                return ("100 p/st");
                break;
            case "CTM":
                return ("c/k");
                break;
            case "DTN":
                return ("100 kg");
                break;
            case "GFI":
                return ("gi F/S");
                break;
            case "GRM":
                return ("g");
                break;
            case "HLT":
                return ("hl");
                break;
            case "HMT":
                return ("100 m");
                break;
            case "KGM":
                return ("kg");
                break;
            case "KLT":
                return ("1,000 l");
                break;
            case "KMA":
                return ("kg met.am.");
                break;
            case "KNI":
                return ("kg N");
                break;
            case "KNS":
                return ("kg H2O2");
                break;
            case "KPH":
                return ("kg KOH");
                break;
            case "KPO":
                return ("kg K2O");
                break;
            case "KPP":
                return ("kg P2O5");
                break;
            case "KSD":
                return ("kg 90 % sdt");
                break;
            case "KSH":
                return ("kg NaOH");
                break;
            case "KUR":
                return ("kg U");
                break;
            case "LPA":
                return ("l alc. 100%");
                break;
            case "LTR":
                return ("l");
                break;
            case "MIL":
                return ("1,000 items");
                break;
            case "MTK":
                return ("m2");
                break;
            case "MTQ":
                return ("m3");
                break;
            case "MTR":
                return ("m");
                break;
            case "MWH":
                return ("1,000 kWh");
                break;
            case "NCL":
                return ("ce/el");
                break;
            case "NPR":
                return ("pa");
                break;
            case "TJO":
                return ("TJ");
                break;
            case "TNE":
                return ("tonne");
                break;
            default:
                return ($this->measurement_unit_code);
                break;
        }
    }

    function getQualifier()
    {
        $qual_desc = "";
        switch ($this->measurement_unit_qualifier_code) {
            case "A":
                $qual_desc = "tot alc";
                break;
            case "C":
                $qual_desc = "1 000";
                break;
            case "E":
                $qual_desc = "net drained wt";
                break;
            case "G":
                $qual_desc = "gross";
                break;
            case "M":
                $qual_desc = "net dry";
                break;
            case "P":
                $qual_desc = "lactic matter";
                break;
            case "R":
                $qual_desc = "std qual";
                break;
            case "S":
                $qual_desc = " raw sugar";
                break;
            case "T":
                $qual_desc = "dry lactic matter";
                break;
            case "X":
                $qual_desc = " hl";
                break;
            case "Z":
                $qual_desc = "% sacchar.";
                break;
        }
        return ($qual_desc);
    }
}
