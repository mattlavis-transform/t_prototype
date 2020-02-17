<?php
class table_control
{
    // Class properties and methods go here
    public $dataset = Null;
    public $custom_no_results_message = "";

    public function __construct($dataset, $override_object = "", $custom_no_results_message = "")
    {
        global $application;
        $this->dataset = $dataset;
        $this->custom_no_results_message = $custom_no_results_message;
        if ($override_object == "") {
            $this->columns = $application->data[$application->tariff_object]["columns"];
        } else {
            $this->columns = $application->data[$override_object]["columns"];
        }
        $this->display();
    }

    private function display()
    {
        global $application;
        if (count($this->dataset) == 0) {
            if ($this->custom_no_results_message == "") {
?>
                <!-- Start error summary //-->
                <div class="govuk-error-summary" aria-labelledby="error-summary-title" role="alert" tabindex="-1" data-module="govuk-error-summary">
                    <h2 class="govuk-error-summary__title" id="error-summary-title">
                        No results
                    </h2>
                    <div class="govuk-error-summary__body">
                        <ul class="govuk-list govuk-error-summary__list">
                            <li>
                                Please try to be less specific in your search query.
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- End error summary //-->
        <?php
            } else {
                echo ($this->custom_no_results_message);
            }
            return;
        }

        ?>
        <!-- Start table control //-->
        <table class="govuk-table govuk-table--m sticky" id="results">
            <thead class="govuk-table__head">
                <tr class="govuk-table__row">
                    <?php
                    foreach ($this->columns as $column) {
                        $sort_field = $column["sort_field"];
                        $column_name = $column["column_name"];

                        $column_name2 = $this->cleanse_column_name($column_name);
                        $tooltip = $column["tooltip"];
                        $align = $column["align"];

                        //$up = $application->encrypt("asc|$sort_field");
                        //$down = $application->encrypt("desc|$sort_field");
                        if ($sort_field == "") {
                            $up_down = "";
                        } else {
                            $up = "asc~" . $sort_field;
                            $down = "desc~" . $sort_field;
                            $up_down = "<div class='arrows'>
                                <div class='up'><a id='sort_asc_$column_name2' href='#' title='Sort by $column_name ascending' rel='$up'><img alt='*' src='/assets/images/arrow_up_b.png' /></a></div>
                                <div class='down'><a id='sort_desc_$column_name2' href='#' title='Sort by $column_name descending' rel='$down'><img alt='*' src='/assets/images/arrow_down_b.png' /></a></div></div>";
                        }
                        if ($align != "") {
                            $align_class = " " . $align;
                        } else {
                            $align_class = "";
                        }
                        if ($tooltip != "") {
                            $tooltip_control = "tip_" . $column_name2;
                            $described_by = ' aria-describedby="' . $tooltip_control . '"';
                            $tooltip_content = '<span id="' . $tooltip_control . '" class="tooltip govuk-visually-hidden" role="tooltip" aria-hidden="true">' . $tooltip . '</span>';
                            //h1 ($tooltip_content);
                        } else {
                            $tooltip_control = "";
                            $described_by = "";
                            $tooltip_content = "";
                        }
                        echo ('<th scope="col" class="govuk-table__header tip ' . $align_class . '"' . $described_by . '>' . $column_name . $tooltip_content . $up_down . '</th>' . "\xA");
                    }
                    ?>
                </tr>
            </thead>
            <tbody class="govuk-table__body">
                <?php
                foreach ($this->dataset as $data_item) {
                ?>
                    <tr class="govuk-table__row">
                        <?php
                        foreach ($this->columns as $column) {
                            $align = $column["align"];
                            if ($align != "") {
                                $align_class = $align;
                            } else {
                                $align_class = "";
                            }
                            $data_column = $column["data_column"];
                            if (isset($column["treatment"])) {
                                $treatment = $column["treatment"];
                            } else {
                                $treatment = "";
                            }

                            echo ('<td class="govuk-table__cell ' . $align_class . '">');
                            switch ($treatment) {
                                case "checkbox":
                                    $control_id = $data_column . "_" . $data_item->{$data_column};
                                    echo ('<div class="govuk-checkboxes govuk-checkboxes--vsmall">');
                                    echo ('<div class="govuk-checkboxes__item">');
                                    echo ('<input class="govuk-checkboxes__input" id="' . $control_id . '" name="' . $data_column . '[]" type="checkbox" value="' . ($data_item->{$data_column}) . '">');
                                    echo ('<label class="govuk-label govuk-checkboxes__label" for="' . $control_id . '"><a class="govuk-link" href="/measures/view.html?mode=view&measure_sid=' . $data_item->{$data_column} . '">' . ($data_item->{$data_column}) . '</a></label>');
                                    echo ('</div>');
                                    echo ('</div>');
                                    break;
                                case "link_measure_type_id":
                                    echo ('<a class="govuk-link" href="/measure_types/view.html?mode=view&measure_type_id=' . $data_item->{$data_column} . '">' . $data_item->{$data_column} . "</a>");
                                    break;
                                case "link_regulation_id":
                                    echo ('<a class="govuk-link" href="/regulations/view.html?mode=view&base_regulation=' . $data_item->{$data_column} . '">' . $data_item->{$data_column} . "</a>");
                                    break;
                                case "link_geographical_area_id":
                                    echo ('<a class="govuk-link" href="/geographical_areas/view.html?mode=view&geographical_area_id=' . $data_item->{$data_column} . '">' . $data_item->{$data_column} . "</a>");
                                    break;    
                                case "link_additional_code":
                                    echo ('<a class="govuk-link" href="/additional_codes/view.html?mode=view&additional_code_sid=' . $data_item->additional_code_sid . '">' . $data_item->{$data_column} . "</a>");
                                    break;    
                                case "ordernumber":
                                case "quota_order_number_id":
                                    echo ('<a class="govuk-link" href="/quotas/view.html?mode=view&quota_order_number_id=' . $data_item->{$data_column} . '">' . $data_item->{$data_column} . "</a>");
                                    break;    
                                case "commodity":
                                    echo ('<a class="nodecorate" href="/goods_nomenclatures/goods_nomenclature_item_view.php?goods_nomenclature_item_id=' . $data_item->{$data_column} . '">' . format_goods_nomenclature_item_id($data_item->{$data_column}) . "</a>");
                                    break;
                                case "status":
                                    echo (status_image($data_item->{$data_column}));
                                    break;
                                default:
                                    echo ($data_item->{$data_column});
                                    break;
                            }
                            echo ('</td>');
                            
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <!-- End table control //-->
<?php
    }

    private function cleanse_column_name($s)
    {
        $s = strtolower($s);
        $s = str_replace(" ", "_", $s);
        return ($s);
    }
}
?>