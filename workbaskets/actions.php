<?php 
    require(dirname(__FILE__) . "../../includes/db.php");
    $application = new application;
    $action = get_querystring("action");
    if ($action == "close") {
        h1 ($application->session->workbasket->id);
        $application->session->close_workbasket();

    }
    ?>