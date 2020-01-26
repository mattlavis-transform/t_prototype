<?php
class filter_control
{
    // Class properties and methods go here
    public $label = "";
    public $control_name = "";
    public $dataset = "";
    public $height_string = "";
    public $truncate_at = 0;
    public $height = 0;
    public $set = false;

    public function __construct($label, $control_name, $dataset, $truncate_at, $height, $type = "checkboxes")
    {
        $this->label = $label;
        $this->control_name = $control_name;
        $this->truncate_at = intval($truncate_at);
        $this->dataset = $dataset;
        $this->height = $height;
        $this->type = $type;
        if ($this->height != 0) {
            $this->height_string = ' style="overflow-y: scroll; height:' . $this->height . ';" ';
        } else {
            $this->height_string = '';
        }
        if ($this->type == "checkboxes") {
            $this->display_checkboxes();
        } else {
            $this->display_input();
        }
    }

    private function display_input()
    {
        global $application;

        $freetext_search = "";
        //var_dump ($application->filter_options);
        foreach ($application->filter_options as $item => $value) {
            $trimmed = str_replace("filter_" . $application->tariff_object . "_", "", $item);
            if ($trimmed == "freetext") {
                $freetext_search = $value;
                break;
            }
        }
?>
        <div class="nav_filter_item">
            <h1 class="govuk-heading-s m0"><?= $this->label ?></h1>
            <input value="<?= $freetext_search ?>" class="govuk-input govuk-input-s" id="<?= $this->control_name ?>" name="<?= $this->control_name ?>" type="text">
        </div>
    <?php
    }

    private function remove_formatting($s)
    {
        $s = str_replace("<b>", "", $s);
        $s = str_replace("</b>", "", $s);
        return ($s);
    }

    private function display_checkboxes()

    {
        global $application;
    ?>

        <!-- Start filter item //-->
        <div class="nav_filter_item">
            <h1 class="govuk-heading-s m0"><?= $this->label ?></h1>
            <div class="govuk-checkboxes govuk-checkboxes--small" <?= $this->height_string ?>>
                <?php
                $control_name = "filter_" . $application->tariff_object . "_" . $this->control_name;
                if (!empty($_POST)) {
                    if (isset($_POST[$control_name])) {
                        $my_array = $_POST[$control_name];
                        $this->set = true;
                    }
                } else {
                    if (isset($_COOKIE[$control_name])) {
                        $my_string = $_COOKIE[$control_name];
                        $my_array = unserialize($my_string);
                        $this->set = true;
                    }
                }
                foreach ($this->dataset as $item) {
                    $selected = "";
                    $control_id = $this->control_name . $item->id;
                    $item->string = str_replace("/", " / ", $item->string);
                    $item->string = str_replace("  ", " ", $item->string);
                    $item->string = str_replace("< / b>", "</b>", $item->string);
                    if ($this->truncate_at != 0) {
                        if (strlen($item->string) > $this->truncate_at) {
                            $display_string = "<abbr title='" . $this->remove_formatting($item->string) . "'>" . trunc($item->string, $this->truncate_at) . "</abbr>";
                        } else {
                            $display_string = $item->string;
                        }
                    } else {
                        $display_string = $item->string;
                    }
                    if ($this->set) {
                        foreach ($my_array as $array_item) {
                            if ($item->id == $array_item) {
                                $selected = " checked";
                                break;
                            }
                        }
                    }
                ?>
                    <div class="govuk-checkboxes__item">
                        <input <?= $selected ?> class="govuk-checkboxes__input" id="<?= $control_id ?>" name="<?= $control_name ?>[]" type="checkbox" value="<?= $item->id ?>">
                        <label class="govuk-label govuk-label--t govuk-checkboxes__label" for="<?= $control_id ?>"><?= $display_string ?></label>
                    </div>
                <?php
                }
                ?><div class="clearer">
                    <!--&nbsp;//-->
                </div>
            </div>
        </div>
        <!-- End filter item //-->
<?php
    }
}
?>