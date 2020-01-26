<?php
// required headers
//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';
$application = new application;

$quota_mechanism = trim(get_querystring("quota_mechanism"));
$quota_category = trim(get_querystring("quota_category"));
$quota_order_number_id = $application->get_next_available_quota_order_number($quota_mechanism, $quota_category);
$my_array = array();
$my_array["next_quota_order_number"] = $quota_order_number_id;
$parent_array = array();
$parent_array["results"] = array();
array_push($parent_array["results"], $my_array);
http_response_code(200);
echo json_encode($parent_array);
?>