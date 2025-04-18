<?php

enable_CORS();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST"):
    check_api_key($_GET['api_key'] ?? []);
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    $dataset_filename = $data["dataset"] ?? null;
    if ($dataset_filename === null) {
        http_response_code(400);
        die(json_encode(["detail" => "Bad Request"]));
    }
    // $STATE_BASE_DIR = dirname(__DIR__) . DIRECTORY_SEPARATOR . "training_datasets";
    // $filepath = $STATE_BASE_DIR . DIRECTORY_SEPARATOR . $dataset_filename;
    // if (!is_file($filepath)) {
    //     http_response_code(400);
    //     die(json_encode(["detail" => "Already deleted"]));
    // }

    $filename = $dataset_filename;
    $folderPath = "/training_datasets/";
    // unlink($filepath);
    try {
        $respd = deleteFromStorageApi($filename, $folderPath);
        debug_out("DELETED DATASET: {$respd}");
    } catch (Exception $e) {
        http_response_code(400);
        die(json_encode(["detail" => "Already Deleted"]));
    }
    http_response_code(200);
    echo json_encode(["detail" => "Deleted dataset $dataset_filename"]);
else:
    http_response_code(401);
    echo json_encode(["detail" => "Invalid Access"]);
endif;