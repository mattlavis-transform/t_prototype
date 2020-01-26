<?php
class title_control
{
    // Class properties and methods go here
    public $title_object = "";
    public $insert_or_update = "";
    public $identifier = "";
    public $override = "";

    public function __construct($title_object, $insert_or_update, $identifier, $override = "")
    {
        $this->title_object = $title_object;
        $this->insert_or_update = $insert_or_update;
        $this->identifier = $identifier;
        $this->override = $override;
        $this->display();
    }

    private function display()
    {
        if (strtolower($this->insert_or_update) == "insert") {
            $this->heading_text = "Create new " . strtolower($this->title_object);
        } else {
            $this->heading_text = "Update " . strtolower($this->title_object) . " " . $this->identifier;
        }
        if ($this->override != "") {
?>
            <!-- Start main title //-->
            <h1 class="govuk-heading-xl"><?= $this->override ?></h1>
            <!-- End main title //-->
        <?php
        } else {
        ?>
            <!-- Start main title //-->
            <h1 class="govuk-heading-xl"><?= $this->heading_text ?></h1>
            <!-- End main title //-->
<?php
        }
    }
}
?>