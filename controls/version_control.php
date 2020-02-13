<?php
class version_control
{
    // Class properties and methods go here
    public $control_name = "";
    public $caption = "";
    public $dataset = array();
    public $description_keys = array();
    public $suppress_control = false;

    public function __construct($control_name, $control_scope, $caption, $dataset, $description_keys)
    {
        global $application;
        $this->control_name = $control_name;
        $this->control_scope = $control_scope;
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

        $this->create_url = "/" . str_replace(" ", "_", $this->object_name) . "_descriptions/create_edit.html?" . $this->querystring;
        $this->edit_url = "/" . str_replace(" ", "_", $this->object_name) . "_descriptions/create_edit.html?mode=update&" . $this->querystring;

        $this->display();
    }


    private function display()
    {
        if ($this->suppress_control == false) {
            //pre ($this->dataset);
            $field_count = pg_num_fields($this->dataset);

?>
            <!-- Start version control //-->
            <table class="govuk-table sticky" id="<?= $this->control_name ?>">
                <caption class="govuk-table__caption--m"><?= $this->caption ?> </caption>
                <thead class="govuk-table__head">
                    <tr class="govuk-table__row">
                        <?php
                        for ($i = 0; $i < $field_count; $i++) {
                            $field = pg_field_name($this->dataset, $i);
                            echo ('<th  scope="col" class="govuk-table__header">' . format_field_name($field) . '</th>');
                        }
                        ?>
                    </tr>
                </thead>
                <tbody class="govuk-table__body">
                    <?php
                    while ($row = pg_fetch_object($this->dataset)) {
                        echo ('<tr class="govuk-table__row">');
                        for ($i = 0; $i < $field_count; $i++) {
                            $field = pg_field_name($this->dataset, $i);
                            echo ('<td class="govuk-table__cell">' . format_value($row, $field) . '</td>');
                        }
                        echo ('</tr>');
                    }
                    ?>
                </tbody>
            </table>
            <!-- End version control //-->
<?php
        }
    }
}
?>