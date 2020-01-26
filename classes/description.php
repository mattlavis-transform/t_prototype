<?php
class description
{
    // Class properties and methods go here
    public $validity_start_date = null;
    public $description = "";

    public function __construct($validity_start_date, $description)
    {
        $this->validity_start_date = $validity_start_date;
        $this->description = $description;
    }
}
