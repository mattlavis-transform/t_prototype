<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';
$application = new application;
$application->get_footnote_types();

$footnote_type_id = trim(get_querystring("footnote_type_id"));

if (sizeof($application->footnote_types) > 0) {
    $parent_array = array();
    $parent_array["results"] = array();
    if ($footnote_type_id != "") {
        foreach ($application->footnote_types as $item) {
            if ($item->footnote_type_id == $footnote_type_id) {
                array_push($parent_array["results"], $item);
            }
        }
    } else {
        foreach ($application->footnote_types as $item) {
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
