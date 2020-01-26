<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$accept_all_cookies = get_formvar("accept-all-cookies");
if (isset($accept_all_cookies)) {
    if ($accept_all_cookies == "yes") {
        $application->session->accept_cookies();
        $referer = $_SERVER["HTTP_REFERER"] . "?accepted=yes";
        header("Location: " . $referer);
       
        //die();
    }
}
//prend ($_REQUEST);
//accept-all-cookies
//h1 ($accept_all_cookies);
?>