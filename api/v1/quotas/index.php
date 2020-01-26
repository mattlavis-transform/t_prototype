<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';
$application = new application;
$application->get_certificates(true);

$certificate_type_code = trim(get_querystring("certificate_type_code"));

if (sizeof($application->certificates) > 0) {
    $parent_array = array();
    $parent_array["results"] = array();
    if ($certificate_type_code != "") {
        foreach ($application->certificates as $item) {
            if ($item->certificate_type_code == $certificate_type_code) {
                array_push($parent_array["results"], $item);
            }
        }
    } else {
        foreach ($application->certificates as $item) {
            array_push($parent_array["results"], $item);
        }
    }
    http_response_code(200);
    echo json_encode($parent_array);
} else {
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
