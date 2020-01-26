<?php
class warning_control
{
    // Class properties and methods go here
    public $text = "";

    public function __construct($text)
    {
        $this->text = $text;
        $this->display();
    }

    private function display()
    {
?>
        <!-- Start warning //-->
        <div class="govuk-warning-text">
            <span class="govuk-warning-text__icon" aria-hidden="true">!</span>
            <strong class="govuk-warning-text__text">
                <span class="govuk-warning-text__assistive">Warning</span>
                <?=$this->text?>
            </strong>
        </div>
        <!-- End warning //-->
<?php
    }
}
?>