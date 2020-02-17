<?php
class back_control
{
    // Class properties and methods go here

    public function __construct()
    {
        $this->display();
    }

    private function display()
    {

?>
        <!-- Start back link //-->
        <a href="javascript:history.back();" class="govuk-back-link">Back</a>
        <!-- End back link //-->
<?php
    }
}
?>