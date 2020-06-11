<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "/home1/gobinitc/public_html/bucketlist/api/api-config.php";
$api_key = $_GET["key"];
$is_valid = True;
if($api_key != "api-key-for-tylers-data-retrieve-device09347298434036798345"){
    $is_valid = False;
}

if($is_valid){
    $api_data = new ApiData($api_key);
    $num_users = $api_data::getTotalUserCount();
    $num_buckets = $api_data::getTotalBucketCount();
    $num_list_items = $api_data::getTotalBucketItemCount();
    $most_popular_bucket = $api_data::getMostPopularBucket();


    http_response_code(202);
    echo json_encode(
        array("num_users" => $num_users,
            "num_buckets" => $num_buckets,
            "num_list_items" => $num_list_items,
            "Most_Popular_Bucket" => $most_popular_bucket)
    );
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "API key not found.")
    );
}


?>