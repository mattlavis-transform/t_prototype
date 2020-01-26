<?php
class mode_control
{
    // Class properties and methods go here
    public $mode = "";

    public function __construct()
    {
        global $application;
        if ($application->mode == ""){
            $application->mode = "insert";
        }
        $this->display();
    }

    private function display()
    {
        global $application;
?>
        <!-- Start mode control //-->
        <input type="hidden" name="mode" value="<?= $application->mode ?>" />
        <!-- End mode control //-->
        <?php
    }
}
?>