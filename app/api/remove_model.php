<?php

enable_CORS();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST"):
    check_api_key($_GET['api_key'] ?? []);
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    $model_filepath = $data["model"] ?? null;
    if ($model_filepath === null) {
        http_response_code(400);
        die(json_encode(["detail" => "Bad Request"]));
    }
    // $STATE_BASE_DIR = dirname(__DIR__);
    // $filepath = $STATE_BASE_DIR . DIRECTORY_SEPARATOR . $model_filepath;
    $filename = basename($model_filepath);
    debug_out("Model filename for removal: {$filename}");
    $folderPath = rtrim($model_filepath, $filename);

    // if (!is_file($filepath)) {
    //     http_response_code(400);
    //     die(json_encode(["detail" => "Already deleted"]));
    // }

    // unlink($filepath);
    try {
        $respd = deleteFromStorageApi($filename, $folderPath);
        debug_out("DELETED MODEL: {$respd}");
    } catch (Exception $e) {
        http_response_code(400);
        die(json_encode(["detail" => "Already deleted"]));
    }
    http_response_code(200);
    echo json_encode(["detail" => "Deleted model $model_filepath"]);
else:
    http_response_code(401);
    echo json_encode(["detail" => "Invalid Access"]);
endif;