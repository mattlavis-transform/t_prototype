<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';

//$application = new application;
$goods_nomenclature = new goods_nomenclature;
$goods_nomenclature_item_id = trim(get_querystring("goods_nomenclature_item_id"));

$goods_nomenclature->goods_nomenclature_item_id = $goods_nomenclature_item_id;
$goods_nomenclature->productline_suffix = "80";
$goods_nomenclature->populate_from_db();

if ($goods_nomenclature->goods_nomenclature_sid != null) {
    $parent_array = array();
    $parent_array["results"] = array();
    array_push($parent_array["results"], $goods_nomenclature);
    http_response_code(200);
    echo json_encode($parent_array);
} else {
    http_response_code(404);
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );

}

/*

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
*/
?>