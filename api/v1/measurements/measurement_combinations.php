<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';
$application = new application;
$measurement_unit_code = trim(get_querystring("measurement_unit_code"));

$application->get_measurement_combinations();

if (sizeof($application->measurement_combinations) > 0) {
    $parent_array = array();
    $parent_array["results"] = array();
    if ($measurement_unit_code != "") {
        foreach ($application->measurement_combinations as $item) {
            if ($item->measurement_unit_code == $measurement_unit_code) {
                array_push($parent_array["results"], $item);
            }
        }
    } else {
        foreach ($application->measurement_combinations as $item) {
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
