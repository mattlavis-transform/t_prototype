<?php
class hidden_control
{
    // Class properties and methods go here
    public $control_name = "";

    public function __construct($control_name, $value)
    {
        global $application;
        if ($application->mode == "") {
            $application->mode = "insert";
        }
        $this->control_name = $control_name;
        $this->value = $value;
        $this->display();
    }

    private function display()
    {
        global $application;
        //if ($application->mode != "insert") {
?>
            <!-- Start mode control //-->
            <input type="hidden" name="<?= $this->control_name ?>" id="<?= $this->control_name ?>" value="<?= $this->value ?>" />
            <!-- End mode control //-->
        <?php
        //}
        ?>
<?php
    }
}
?>