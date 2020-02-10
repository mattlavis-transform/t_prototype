<?php
class footnote_assignment_table_control
{
    // Class properties and methods go here
    public $control_name = "";
    public $caption = "";
    public $dataset = array();
    public $description_keys = array();
    public $suppress_control = false;
    public $application_code_description = "";

    public function __construct($control_name, $control_scope, $caption, $dataset, $application_code_description, $description_keys)
    {
        global $application;
        $this->control_name = $control_name;
        $this->control_scope = $control_scope;
        $this->application_code_description = $application_code_description;
        if ($this->control_scope != "") {
            if (strpos($this->control_scope, $application->mode) === false) {
                $this->suppress_control = true;
                return;
            }
        }

        $this->caption = $caption;
        $this->dataset = $dataset;
        $this->object_name = strtolower($application->object_name);
        $this->object_name = rtrim($this->object_name, "s");
        $this->object_description = $this->object_name . " description";
        $this->description_key_string = $description_keys;
        $this->description_keys = explode("|", $this->description_key_string);
        $this->querystring = "";
        foreach ($this->description_keys as $description_key) {
            $this->querystring .= $description_key . "=" .  $_GET[$description_key] . "&";
        }
        $this->querystring = rtrim($this->querystring, "&");
        $this->create_url = "geographical_area_add_member.html?" . $this->querystring;
        $this->terminate_url = "geographical_area_terminate_member.html?" . $this->querystring;

        if ($this->application_code_description == "Nomenclature-related footnote") {
            $this->display_nomenclature_related();
            $this->display_new_nomenclature_association_form();
        } else {
            $this->display_measure_related();
        }
    }

    private function display_new_nomenclature_association_form()
    {
?>
        <form>
            <hr>
            <?php

            new textarea_control(
                $label = "Associate footnote to commodity codes",
                $label_style = "govuk-label--m",
                $hint_text = "Please enter the 10-digit commodity codes of the products to which you would like to associate this footnote.",
                $control_name = "name",
                $rows = 5,
                $maxlength = 2000,
                $required = true,
                $default = "",
                $error_key = ""
            );
            new date_picker_control(
                $label = "Please select the date of which to associate the commodities",
                $label_style = "govuk-label--s",
                $hint_text = "",
                $control_name = "date",
                $control_scope = "view",
                $default = "",
                $required = true,
                $custom_errors = ""
            );
            new button_control("Associate footnote", "associate_footnotes", "primary", true, "");
            new back_to_top_control();
            ?>
        </form>
        <?php
    }

    private function display_nomenclature_related()
    {
        if ($this->suppress_control == false) {
        ?>
            <!-- Start detail table control //-->
            <form>
                <table class="govuk-table govuk-table--m sticky" id="<?= $this->control_name ?>">
                    <caption class="govuk-table__caption--m"><?= $this->caption ?> </caption>
                    <thead class="govuk-table__head">
                        <tr class="govuk-table__row">
                            <th scope="col" class="govuk-table__header">&nbsp;</th>
                            <th scope="col" class="govuk-table__header">Commodity</th>
                            <th scope="col" class="govuk-table__header">Association start&nbsp;date</th>
                            <th scope="col" class="govuk-table__header">Association end&nbsp;date</th>
                        </tr>
                    </thead>
                    <tbody class="govuk-table__body">
                        <?php
                        foreach ($this->dataset as $item) {
                        ?>
                            <tr class="govuk-table__row">
                                <td class="govuk-table__cell">
                                    <div class="govuk-checkboxes govuk-checkboxes--small">
                                        <div class="govuk-checkboxes__item" style="padding:0px;margin:0px;top:-10px;position:relative;">
                                            <input class="govuk-checkboxes__input" style="padding:0px;margin:0px;" id="measure_<?= $item->measure_sid ?>" name="measure[]" type="checkbox" value="hmrc">
                                            <label class="govuk-label govuk-checkboxes__label" style="padding:0px;margin:0px;">&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                    </div>
                                </td>
                                <!--<td class="govuk-table__cell"><label for="measure_<?= $item->goods_nomenclature_sid ?>"><?= $item->goods_nomenclature_sid ?></label></td>//-->
                                <td class="govuk-table__cell nw"><?= $item->goods_nomenclature_url ?></td>
                                <td class="govuk-table__cell"><?= short_date($item->validity_start_date) ?></td>
                                <td class="govuk-table__cell"><?= short_date($item->validity_end_date) ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr class="govuk-table__row noborder">
                            <td colspan="4" class="govuk-table__cell">
                                <div class="govuk-checkboxes govuk-checkboxes--small">
                                    <div class="govuk-checkboxes__item" style="top:-10px;position:relative;">
                                        <input class="govuk-checkboxes__input" id="select_all_footnotes" name="select_all_footnotes" type="checkbox" value="hmrc">
                                        <label class="govuk-label govuk-checkboxes__label" id="label_select_all_footnotes" for="select_all_footnotes">Select all</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php

                new date_picker_control(
                    $label = "Dissociate footnote from commodity codes",
                    $label_style = "govuk-label--m",
                    $hint_text = "Please select the commodity codes above that you would like to dissociate from this footnote, then enter the date on which you would like to break the link.",
                    $control_name = "date",
                    $control_scope = "view",
                    $default = "",
                    $required = true,
                    $custom_errors = ""
                );
                new button_control("Break selected associations", "break_association", "primary", true, "");
                ?>
            </form>

        <?php
        }
    }

    private function display_measure_related()
    {
        if ($this->suppress_control == false) {
        ?>
            <!-- Start detail table control //-->
            <form>
                <table class="govuk-table govuk-table--m sticky" id="<?= $this->control_name ?>">
                    <caption class="govuk-table__caption--m"><?= $this->caption ?> </caption>
                    <thead class="govuk-table__head">
                        <tr class="govuk-table__row">
                            <th scope="col" class="govuk-table__header">#</th>
                            <th scope="col" class="govuk-table__header">Measure SID</th>
                            <th scope="col" class="govuk-table__header">Commodity</th>
                            <th scope="col" class="govuk-table__header">Measure start&nbsp;date</th>
                            <th scope="col" class="govuk-table__header">Measure end&nbsp;date</th>
                            <th scope="col" class="govuk-table__header">Geography</th>
                            <th scope="col" class="govuk-table__header">Measure type</th>
                        </tr>
                    </thead>
                    <tbody class="govuk-table__body">
                        <?php
                        foreach ($this->dataset as $item) {
                            //$this->edit_url .= "&validity_start_date=" .  $item->validity_start_date;
                            //$terminate_url = $this->terminate_url . "&sid=" .  $item->geographical_area_sid . "&id=" . $item->geographical_area_id;
                        ?>
                            <tr class="govuk-table__row">
                                <td class="govuk-table__cell">
                                    <div class="govuk-checkboxes govuk-checkboxes--small">
                                        <div class="govuk-checkboxes__item" style="padding:0px;margin:0px;top:-10px;position:relative;">
                                            <input class="govuk-checkboxes__input" style="padding:0px;margin:0px;" id="measure_<?= $item->measure_sid ?>" name="measure[]" type="checkbox" value="hmrc">
                                            <label class="govuk-label govuk-checkboxes__label" style="padding:0px;margin:0px;">&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                    </div>
                                </td>
                                <td class="govuk-table__cell"><label for="measure_<?= $item->measure_sid ?>"><?= $item->measure_sid ?></label></td>
                                <td class="govuk-table__cell nw"><?= $item->goods_nomenclature_url ?></td>
                                <td class="govuk-table__cell"><?= short_date($item->validity_start_date) ?></td>
                                <td class="govuk-table__cell"><?= short_date($item->validity_end_date) ?></td>
                                <td class="govuk-table__cell"><?= $item->geographical_area_id_url ?></td>
                                <td class="govuk-table__cell"><?= $item->measure_type_id_description_url ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr class="govuk-table__row">
                            <td colspan="7" class="govuk-table__cell">
                                <div class="govuk-checkboxes govuk-checkboxes--small">
                                    <div class="govuk-checkboxes__item" style="top:-10px;position:relative;">
                                        <input class="govuk-checkboxes__input" id="select_all_footnotes" name="select_all_footnotes" type="checkbox" value="hmrc">
                                        <label class="govuk-label govuk-checkboxes__label" id="label_select_all_footnotes" for="select_all_footnotes">Select all</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
                new button_control("Break selected associations", "break_association", "primary", true, "");
                ?>
            </form>
            <!--<p class="govuk-body"><a class="govuk-link" href="<?= $this->create_url ?>">Create a new membership</a></p>//-->

<?php
            new back_to_top_control();
        }
    }
}
?>