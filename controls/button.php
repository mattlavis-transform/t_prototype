<?php
class button_control
{
    // Class properties and methods go here
    public $text = "";
    public $type_class = "";

    public function __construct($text, $name, $type, $include_submitted = true, $link_href = "")
    {
        $this->text = $text;
        $this->name = $name;
        $this->type = $type;
        if ($this->type == "secondary") {
            $this->type_class = "govuk-button govuk-button--secondary";
        } else {
            $this->type_class = "govuk-button";
        }
        $this->include_submitted = $include_submitted;
        $this->link_href = $link_href;
        if ($this->link_href == "x") {
            $this->link_href = "javascript:return (false);";
        }

        $this->display();
    }

    private function display()
    {
        if ($this->type != "text") {
            if ($this->include_submitted == true) {
                echo ('<input type="hidden" name="submitted" id="submitted" value="1" />');
            }
?>
            <!-- Start button //-->
            <button name="<?= $this->name ?>" id="<?= $this->name ?>" value="<?= $this->name ?>" class="<?= $this->type_class ?>" data-module="govuk-button"><?= $this->text ?></button>
            <!-- End button //-->
        <?php
        } else {
        ?>
            <a id="<?= $this->name ?>" href="<?= $this->link_href ?>" class="textual_button govuk-link"><?= $this->text ?></a>
<?php
        }
    }
}
?>