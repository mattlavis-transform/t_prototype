<?php
class measures_table_control
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
?>
            <!-- Start detail table control //-->
            <table class="govuk-table sticky" id="<?= $this->control_name ?>">
                <caption class="govuk-table__caption--m"><?= $this->caption ?> </caption>
                <thead class="govuk-table__head">
                    <tr class="govuk-table__row">
                        <th scope="col" class="govuk-table__header">Start&nbsp;date</th>
                        <th scope="col" class="govuk-table__header">Description</th>
                        <th scope="col" class="govuk-table__header r">Actions</th>
                    </tr>
                </thead>
                <tbody class="govuk-table__body">
                    <?php
                    $count = count($this->dataset);
                    $index = 0;
                    foreach ($this->dataset as $item) {
                        $index += 1;
                        $edit_url = $this->edit_url .= "&validity_start_date=" .  $item->validity_start_date;
                    ?>
                        <tr class="govuk-table__row">
                            <td class="govuk-table__cell"><?= short_date($item->validity_start_date) ?></td>
                            <td class="govuk-table__cell"><?= $item->description ?></td>
                            <td class="govuk-table__cell r" nowrap>
                                <?php
                                if ($index != $count) {
                                ?>
                                    <a class="govuk-link" title="Delete this <?= $this->object_description ?>" href="<?= $this->delete_url ?>">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php
                                }
                                ?>
                                <a class="govuk-link" title="Edit this <?= $this->object_description ?>" href="<?= $edit_url ?>">Edit</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <p class="govuk-body"><a class="govuk-link" href="<?= $this->create_url ?>">Create a new <?= strtolower($this->object_description) ?></a></p>
            <!-- End detail table control //-->
<?php
        }
    }
}
?>