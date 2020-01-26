<?php
class include_control
{
    // Class properties and methods go here
    public $path = "";
    public $control_scope = "";
    public $suppress_control = false;

    public function __construct($path, $control_scope = "")
    {
        $this->path = $path;
        $this->control_scope = $control_scope;
        $this->suppress_control = false;
        $this->display();
    }

    private function display()
    {
        global $application;
        if ($this->control_scope != "") {
            if (strpos($this->control_scope, $application->mode) === false) {
                $this->suppress_control = true;
            }
        }
        if (!$this->suppress_control) {
            $sfn = $_SERVER["SCRIPT_FILENAME"];
            $sn = $_SERVER["SCRIPT_NAME"];
            $temp = str_replace($sn, "", $sfn);
            $path = $temp . $this->path;
            require($path);
        }
    }
}
