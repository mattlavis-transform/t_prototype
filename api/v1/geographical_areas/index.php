<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../../../includes/db.php';

$geographical_area_sid = trim(get_querystring("parent"));

if ($geographical_area_sid == "") {
    $sql = "select geographical_area_sid, geographical_area_id, geographical_code, description, validity_start_date, validity_end_date
    from ml.ml_geographical_areas mga order by 1;";
    $stmt = "get_geography";
    pg_prepare($conn, $stmt, $sql);
    $result = pg_execute($conn, $stmt, array());
} else {
    $sql = "select mga.geographical_area_id, mga.description, mga.geographical_area_sid, mga.geographical_code, 
    mga.validity_start_date, mga.validity_end_date
    from ml.ml_geo_memberships mgm, ml.ml_geographical_areas mga
    where mgm.child_sid = mga.geographical_area_sid
    and mgm.parent_sid = $1
    order by 1, 2;";
    $stmt = "get_geography";
    pg_prepare($conn, $stmt, $sql);
    $result = pg_execute($conn, $stmt, array($geographical_area_sid));

}
$array = array();
if ($result) {
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_array($result)) {
            $geo = new geographical_area();
            $geo->validity_start_date = short_date_rev($row["validity_start_date"], true);
            $geo->validity_end_date = short_date_rev($row["validity_end_date"], true);
            $geo->geographical_area_sid = $row["geographical_area_sid"];
            $geo->geographical_area_id = $row["geographical_area_id"];
            $geo->geographical_code = $row["geographical_code"];
            $geo->description = $row["description"];
            $geo->id = $geo->geographical_area_id;
            $geo->description = $geo->id . " " . $row["description"];
            $geo->text = $geo->description;
            array_push($array, $geo);
        }
    }
}

if (sizeof($array) > 0) {
    $parent_array = array();
    $parent_array["results"] = array();
    foreach ($array as $item) {
        array_push($parent_array["results"], $item);
    }
    http_response_code(200);
    echo json_encode($parent_array);
    //echo json_encode($array);
} else {
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
